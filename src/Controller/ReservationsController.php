<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Reservations Controller
 */
class ReservationsController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event): void
    {
        parent::beforeFilter($event);
        if (isset($this->Authentication)) {
            $this->Authentication->addUnauthenticatedActions(['ajaxAdd']);
        }
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
            $reservation = $this->Reservations->patchEntity($reservation, $this->request->getData());
            if ($this->Reservations->save($reservation)) {
                if ($this->request->is('ajax')) {
                    $this->response = $this->response->withType('application/json');
                    $this->set([ 'success' => true, 'message' => __('Bedankt! We nemen zo snel mogelijk contact met u op.'), '_serialize' => ['success', 'message'] ]);
                    return;
                }
                $this->Flash->success(__('The reservation has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            if ($this->request->is('ajax')) {
                $this->response = $this->response->withType('application/json');
                $debug = (bool)\Cake\Core\Configure::read('debug');
                $errors = $debug ? $reservation->getErrors() : null;
                $this->set([
                    'success' => false,
                    'message' => __('Gelieve alle verplichte velden correct in te vullen.'),
                    'errors' => $errors,
                    '_serialize' => ['success', 'message', 'errors']
                ]);
                return;
            }
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
            $reservation = $this->Reservations->patchEntity($reservation, $this->request->getData());
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
}
