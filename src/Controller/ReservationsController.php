<?php
declare(strict_types=1);

namespace App\Controller;

use App\Mailer\ReservationMailer;
use App\Model\Entity\Reservation;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Client;
use Cake\Http\Response;
use Cake\Log\Log;
use DateTimeImmutable;
use Throwable;

/**
 * Reservations Controller
 */
class ReservationsController extends AppController
{
    /**
     * Send reservation emails after a successful frontend submission.
     *
     * @param \App\Model\Entity\Reservation $reservation Saved reservation entity.
     * @return array<string, bool>
     */
    private function sendReservationEmails(Reservation $reservation): array
    {
        $delivery = [
            'adminSent' => false,
            'guestSent' => false,
        ];

        $transportConfig = \Cake\Mailer\TransportFactory::getConfig('default');
        Log::debug('SMTP transport config: ' . json_encode(array_merge($transportConfig, isset($transportConfig['password']) ? ['password' => '***'] : [])));

        try {
            (new ReservationMailer('default'))->send('adminNotification', [$reservation]);
            $delivery['adminSent'] = true;
        } catch (Throwable $exception) {
            Log::error('Failed to send reservation admin notification: ' . $exception->getMessage());
        }

        if (trim((string)$reservation->email) === '') {
            return $delivery;
        }

        try {
            (new ReservationMailer('default'))->send('guestConfirmation', [$reservation]);
            $delivery['guestSent'] = true;
        } catch (Throwable $exception) {
            Log::error('Failed to send reservation guest confirmation: ' . $exception->getMessage());
        }

        return $delivery;
    }

    /**
     * Read reCAPTCHA configuration.
     *
     * @return array<string, string>
     */
    private function getRecaptchaConfig(): array
    {
        return [
            'siteKey' => trim((string)Configure::read('Recaptcha.siteKey', '')),
            'secretKey' => trim((string)Configure::read('Recaptcha.secretKey', '')),
            'verifyUrl' => trim((string)Configure::read(
                'Recaptcha.verifyUrl',
                'https://www.google.com/recaptcha/api/siteverify'
            )),
        ];
    }

    /**
     * Check whether reCAPTCHA should be enforced.
     */
    private function isRecaptchaEnabled(): bool
    {
        return $this->getRecaptchaConfig()['siteKey'] !== '';
    }

    /**
     * Verify a submitted reCAPTCHA token with Google.
     *
     * @param string|null $token Browser token.
     * @return bool
     */
    private function verifyRecaptchaResponse(?string $token): bool
    {
        if (!$this->isRecaptchaEnabled()) {
            return true;
        }

        $token = trim((string)$token);
        if ($token === '') {
            return false;
        }

        $config = $this->getRecaptchaConfig();
        if ($config['secretKey'] === '') {
            return false;
        }

        $client = new Client([
            'timeout' => 10,
        ]);
        $response = $client->post($config['verifyUrl'], [
            'secret' => $config['secretKey'],
            'response' => $token,
            'remoteip' => (string)$this->request->clientIp(),
        ]);

        if (!$response->isOk()) {
            return false;
        }

        $payload = $response->getJson();

        return is_array($payload) && !empty($payload['success']);
    }

    /**
     * Normalize supported incoming date formats to Y-m-d for ORM patching.
     *
     * @param array<string, mixed> $data Request data.
     * @return array<string, mixed>
     */
    private function normalizeReservationDates(array $data): array
    {
        if (isset($data['checkin_date']) && is_string($data['checkin_date'])) {
            $rangeParts = preg_split('/\s+-\s+/', trim($data['checkin_date']));
            if (is_array($rangeParts) && isset($rangeParts[0])) {
                $normalizedCheckin = $this->normalizeDateString($rangeParts[0]);
                if ($normalizedCheckin !== null) {
                    $data['checkin_date'] = $normalizedCheckin;
                }

                if ((!isset($data['checkout_date']) || $data['checkout_date'] === '') && isset($rangeParts[1])) {
                    $normalizedCheckout = $this->normalizeDateString($rangeParts[1]);
                    if ($normalizedCheckout !== null) {
                        $data['checkout_date'] = $normalizedCheckout;
                    }
                }
            }
        }

        foreach (['checkin_date', 'checkout_date'] as $field) {
            if (!isset($data[$field]) || !is_string($data[$field])) {
                continue;
            }

            $normalized = $this->normalizeDateString($data[$field]);
            if ($normalized !== null) {
                $data[$field] = $normalized;
            }
        }

        return $data;
    }

    private function normalizeDateString(string $value): ?string
    {
        $value = trim($value);
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d', 'd-m-Y', 'j-n-Y', 'd-m-y', 'j-n-y', 'n/j/y', 'n/j/Y', 'j/n/y', 'j/n/Y', 'm/d/y', 'm/d/Y', 'd/m/y', 'd/m/Y'];
        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat('!' . $format, $value);
            if ($date !== false) {
                return $date->format('Y-m-d');
            }
        }

        $timestamp = strtotime($value);
        if ($timestamp !== false) {
            return date('Y-m-d', $timestamp);
        }

        return null;
    }

    /**
     * @param \Cake\Event\EventInterface $event Event instance.
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
        $this->Authentication->allowUnauthenticated(['ajaxAdd']);
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Reservations->find();
        // Apply filters from query params
        $q = $this->request->getQueryParams();
        if (!empty($q['name'])) {
            $query->where([
                'full_name LIKE' => '%' . $q['name'] . '%'
            ]);
        }
        if (!empty($q['email'])) {
            $query->where([
                'email LIKE' => '%' . $q['email'] . '%'
            ]);
        }
        if (isset($q['status']) && $q['status'] !== '') {
            $query->where(['status' => $q['status']]);
        }
        if (!empty($q['checkin_from'])) {
            $query->where(['checkin_date >=' => $q['checkin_from']]);
        }
        if (!empty($q['checkin_to'])) {
            $query->where(['checkin_date <=' => $q['checkin_to']]);
        }

        $query = $this->Authorization->applyScope($query);
        $reservations = $this->paginate($query);

        $this->set(compact('reservations'));
    }

    /**
     * View method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $reservation = $this->Reservations->get($id, contain: []);
        $this->Authorization->authorize($reservation);
        $this->set(compact('reservation'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $reservation = $this->Reservations->newEmptyEntity();
        $this->Authorization->authorize($reservation);
        // Get confirmed reservation date ranges (excluding current reservation)
        $confirmed = $this->Reservations->find()
            ->select(['checkin_date', 'checkout_date'])
            ->where(['status' => 'confirmed'])
            ->all();
        $confirmedRanges = [];
        foreach ($confirmed as $r) {
            $confirmedRanges[] = [
                $r->checkin_date,
                $r->checkout_date
            ];
        }
        if ($this->request->is('post')) {
            $data = $this->normalizeReservationDates($this->request->getData());
            $data['status'] = 'new';
            $reservation = $this->Reservations->patchEntity($reservation, $data);
            if ($this->Reservations->save($reservation)) {
                $this->Flash->success(__('The reservation has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            // debug($reservation->getErrors());
            $this->Flash->error(__('The reservation could not be saved. Please, try again.'));
        }
        $this->set(compact('reservation', 'confirmedRanges'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $reservation = $this->Reservations->get($id, contain: []);
        $this->Authorization->authorize($reservation);
        // Get confirmed reservation date ranges (excluding this reservation)
        $confirmed = $this->Reservations->find()
            ->select(['checkin_date', 'checkout_date'])
            ->where([
                'status' => 'confirmed',
                'id !=' => $id
            ])
            ->all();
        $confirmedRanges = [];
        foreach ($confirmed as $r) {
            $confirmedRanges[] = [
                $r->checkin_date,
                $r->checkout_date
            ];
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->normalizeReservationDates($this->request->getData());
            $sendStatusEmail = !empty($data['send_status_email']);
            unset($data['send_status_email']);
            $previousStatus = trim((string)$reservation->status);
            if (isset($data['status']) && trim((string)$data['status']) === '') {
                unset($data['status']);
            }
            $reservation = $this->Reservations->patchEntity($reservation, $data);
            if ($this->Reservations->save($reservation)) {
                $currentStatus = trim((string)$reservation->status);
                if ($sendStatusEmail) {
                    if ($previousStatus !== $currentStatus && trim((string)$reservation->email) !== '') {
                        try {
                            (new ReservationMailer('default'))->send('guestStatusUpdate', [$reservation, $previousStatus]);
                        } catch (Throwable $exception) {
                            Log::error('Failed to send reservation status update email: ' . $exception->getMessage());
                            $this->Flash->warning(__('The reservation was saved, but the status update email could not be sent.'));
                        }
                    } elseif (trim((string)$reservation->email) === '') {
                        $this->Flash->warning(__('The reservation was saved, but no visitor email address is available.'));
                    } else {
                        $this->Flash->warning(__('The reservation was saved, but no status change was detected so no email was sent.'));
                    }
                }
                $this->Flash->success(__('The reservation has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The reservation could not be saved. Please, try again.'));
        }
        $this->set(compact('reservation', 'confirmedRanges'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Reservation id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $reservation = $this->Reservations->get($id);
        $this->Authorization->authorize($reservation);
        if ($this->Reservations->delete($reservation)) {
            $this->Flash->success(__('The reservation has been deleted.'));
        } else {
            $this->Flash->error(__('The reservation could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Agenda (calendar) view for reservations
     */
    public function agenda()
    {
        $this->Authorization->skipAuthorization();
        $reservations = $this->Reservations->find()
            ->where(['status' => 'confirmed'])
            ->all();

        $calendarData = [];
        foreach ($reservations as $r) {
            $calendarData[] = [
                'title' => $r->full_name . ' (Bevestigd)',
                'start' => $r->checkin_date,
                'end' => date('Y-m-d', strtotime($r->checkout_date . ' +1 day')),
                'status' => $r->status,
            ];
        }
        $this->set(compact('calendarData'));
    }

    /**
     * AJAX endpoint for reservation form
     */
    public function ajaxAdd(): ?Response
    {
        $this->viewBuilder()->setLayout('ajax');
        $reservation = $this->Reservations->newEmptyEntity();
        // $this->Authorization->authorize($reservation);
        $recaptchaSiteKey = $this->getRecaptchaConfig()['siteKey'];

        $this->Authorization->skipAuthorization();
        // Get confirmed reservation date ranges
        $confirmed = $this->Reservations->find()
            ->select(['checkin_date', 'checkout_date'])
            ->where(['status' => 'confirmed'])
            ->all();
        $confirmedRanges = [];
        foreach ($confirmed as $r) {
            $confirmedRanges[] = [
                $r->checkin_date,
                $r->checkout_date
            ];
        }
        if ($this->request->is('post')) {
            if (!$this->verifyRecaptchaResponse($this->request->getData('g-recaptcha-response'))) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'success' => false,
                        'message' => __('Bevestig eerst dat u geen robot bent.'),
                        'errors' => [
                            'recaptcha' => [
                                'validation' => __('De reCAPTCHA-validatie is mislukt.'),
                            ],
                        ],
                    ]));
            }

            $data = $this->normalizeReservationDates($this->request->getData());
            $reservation = $this->Reservations->patchEntity($reservation, $data);
            if ($this->Reservations->save($reservation)) {
                $mailDelivery = $this->sendReservationEmails($reservation);
                $successMessage = $mailDelivery['guestSent']
                    ? __('Bedankt! We hebben uw aanvraag ontvangen en een bevestigingsmail verstuurd.')
                    : __('Bedankt! We hebben uw aanvraag ontvangen en nemen zo snel mogelijk contact met u op.');

                return $this->response
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'success' => true,
                        'message' => $successMessage,
                    ]));
            }
            $debug = (bool)Configure::read('debug');
            $errors = $debug ? $reservation->getErrors() : null;

            return $this->response
                ->withType('application/json')
                ->withStringBody((string)json_encode([
                    'success' => false,
                    'message' => __('Gelieve alle verplichte velden correct in te vullen.'),
                    'errors' => $errors,
                ]));
        }
        $csrfToken = $this->request->getAttribute('csrfToken');
        $this->set(compact('reservation', 'confirmedRanges', 'csrfToken', 'recaptchaSiteKey'));
        $this->render('ajax_add');

        return null;
    }
}
