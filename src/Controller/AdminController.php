<?php
declare(strict_types=1);

namespace App\Controller;

use Authentication\IdentityInterface;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class AdminController extends AppController
{
    private const CMS_ROLES = ['admin', 'editor'];
    private const RESERVATION_STATUSES = ['new', 'confirmed', 'cancelled', 'completed'];

    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authorization->skipAuthorization();
    }

    public function dashboard(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot het CMS.');

            return $this->redirect('/');
        }

        $this->set([
            'name' => (string)$identity->get('full_name'),
            'email' => (string)$identity->get('email'),
            'role' => $role,
            'isAdmin' => $role === 'admin',
        ]);

        return null;
    }

    public function reservations(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot reservaties.');

            return $this->redirect('/');
        }

        $selectedStatus = (string)$this->request->getQuery('status', 'all');
        $reservationsQuery = $this->fetchTable('Reservations')
            ->find()
            ->orderBy(['created_at' => 'DESC', 'id' => 'DESC']);

        if (in_array($selectedStatus, self::RESERVATION_STATUSES, true)) {
            $reservationsQuery->where(['status' => $selectedStatus]);
        }

        $reservations = $reservationsQuery->all();
        $this->set([
            'reservations' => $reservations,
            'statusOptions' => self::RESERVATION_STATUSES,
            'selectedStatus' => $selectedStatus,
            'isAdmin' => $role === 'admin',
        ]);

        return null;
    }

    public function updateReservationStatus(int $id): Response
    {
        $this->request->allowMethod(['post']);

        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot reservaties.');

            return $this->redirect('/');
        }

        $status = (string)$this->request->getData('status');
        if (!in_array($status, self::RESERVATION_STATUSES, true)) {
            $this->Flash->error('Ongeldige reservatiestatus.');

            return $this->redirect('/admin/reservations');
        }

        $reservationsTable = $this->fetchTable('Reservations');
        $reservation = $reservationsTable->get($id);
        $reservation = $reservationsTable->patchEntity($reservation, ['status' => $status], [
            'validate' => false,
            'accessibleFields' => ['status' => true],
        ]);

        if ($reservationsTable->save($reservation, ['checkRules' => false])) {
            $this->Flash->success('Reservatiestatus bijgewerkt.');
        } else {
            $this->Flash->error('Kon reservatiestatus niet opslaan.');
        }

        $selectedStatus = (string)$this->request->getQuery('status', 'all');
        if (in_array($selectedStatus, self::RESERVATION_STATUSES, true)) {
            return $this->redirect('/admin/reservations?status=' . urlencode($selectedStatus));
        }

        return $this->redirect('/admin/reservations');
    }

    public function users(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if ($role !== 'admin') {
            $this->Flash->error('Alleen admins hebben toegang tot gebruikersbeheer.');

            return $this->redirect('/admin');
        }

        $users = $this->fetchTable('Users')
            ->find()
            ->select(['id', 'full_name', 'email', 'role', 'status', 'last_login_at', 'updated_at'])
            ->orderBy(['id' => 'ASC'])
            ->all();

        $this->set(compact('users'));

        return null;
    }

    public function texts(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot teksten.');

            return $this->redirect('/');
        }

        $texts = $this->fetchTable('SiteTexts')
            ->find()
            ->orderBy(['section_key' => 'ASC', 'field_key' => 'ASC'])
            ->all();

        $this->set(compact('texts'));

        return null;
    }

    public function editText(int $id): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot teksten.');

            return $this->redirect('/');
        }

        $textsTable = $this->fetchTable('SiteTexts');
        $text = $textsTable->get($id);

        if ($this->request->is('post')) {
            $text = $textsTable->patchEntity($text, $this->request->getData(), [
                'accessibleFields' => ['content' => true, 'is_active' => true],
            ]);
            if ($textsTable->save($text)) {
                $this->Flash->success('Tekst opgeslagen.');

                return $this->redirect(['action' => 'texts']);
            }
            $this->Flash->error('Kon tekst niet opslaan.');
        }

        $this->set(compact('text'));

        return null;
    }

    public function photos(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot foto\'s.');

            return $this->redirect('/');
        }

        $photos = $this->fetchTable('SitePhotos')
            ->find()
            ->orderBy(['section_key' => 'ASC', 'sort_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        $this->set(compact('photos'));

        return null;
    }

    public function editPhoto(int $id): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot foto\'s.');

            return $this->redirect('/');
        }

        $photosTable = $this->fetchTable('SitePhotos');
        $photo = $photosTable->get($id);

        if ($this->request->is('post')) {
            $photo = $photosTable->patchEntity($photo, $this->request->getData(), [
                'accessibleFields' => ['title' => true, 'alt_text' => true, 'image_url' => true, 'sort_order' => true, 'is_active' => true],
            ]);
            if ($photosTable->save($photo)) {
                $this->Flash->success('Foto opgeslagen.');

                return $this->redirect(['action' => 'photos']);
            }
            $this->Flash->error('Kon foto niet opslaan.');
        }

        $this->set(compact('photo'));

        return null;
    }

    public function reviews(): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot reviews.');

            return $this->redirect('/');
        }

        $reviews = $this->fetchTable('SiteReviews')
            ->find()
            ->orderBy(['sort_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        $this->set(compact('reviews'));

        return null;
    }

    public function editReview(int $id): ?Response
    {
        $identity = $this->Authentication->getIdentity();
        if (!$identity) {
            return $this->redirect('/login');
        }

        $role = (string)$identity->get('role');
        if (!in_array($role, self::CMS_ROLES, true)) {
            $this->Flash->error('U heeft geen toegang tot reviews.');

            return $this->redirect('/');
        }

        $reviewsTable = $this->fetchTable('SiteReviews');
        $review = $reviewsTable->get($id);

        if ($this->request->is('post')) {
            $review = $reviewsTable->patchEntity($review, $this->request->getData(), [
                'accessibleFields' => ['guest_name' => true, 'initials' => true, 'location' => true, 'rating' => true, 'review_text' => true, 'review_date' => true, 'is_published' => true, 'sort_order' => true],
            ]);
            if ($reviewsTable->save($review)) {
                $this->Flash->success('Review opgeslagen.');

                return $this->redirect(['action' => 'reviews']);
            }
            $this->Flash->error('Kon review niet opslaan.');
        }

        $this->set(compact('review'));

        return null;
    }
}
