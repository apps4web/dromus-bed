<?php
declare(strict_types=1);

namespace App\Controller;

use DateTimeImmutable;
use Cake\Http\Response;

/**
 * Reservations Controller
 */
class ReservationsController extends AppController
{
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

    public function beforeFilter(\Cake\Event\EventInterface $event): void
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
            $reservation = $this->Reservations->patchEntity($reservation, $data);
            if ($this->Reservations->save($reservation)) {
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
            $data = $this->normalizeReservationDates($this->request->getData());
            $reservation = $this->Reservations->patchEntity($reservation, $data);
            if ($this->Reservations->save($reservation)) {
                return $this->response
                    ->withType('application/json')
                    ->withStringBody((string)json_encode([
                        'success' => true,
                        'message' => __('Bedankt! We nemen zo snel mogelijk contact met u op.'),
                    ]));
            }
            $debug = (bool)\Cake\Core\Configure::read('debug');
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
        $this->set(compact('reservation','confirmedRanges', 'csrfToken'));
        $this->render('ajax_add');

        return null;
    }
}
