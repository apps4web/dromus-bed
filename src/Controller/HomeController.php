<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Response;

class HomeController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions(['index', 'reserve', 'robots']);
        $this->Authorization->skipAuthorization();
    }

    public function robots(): Response
    {
        $content = Configure::read('debug')
            ? "User-agent: *\nDisallow: /\n"
            : "User-agent: *\nAllow: /\n";

        return $this->response
            ->withType('text/plain')
            ->withStringBody($content);
    }

    public function index(): void
    {
        $this->viewBuilder()->disableAutoLayout();

        $textRows = $this->fetchTable('SiteTexts')
            ->find()
            ->where([
                'locale' => 'nl',
                'is_active' => true,
            ])
            ->all();

        $texts = [];
        foreach ($textRows as $row) {
            $texts[$row->section_key . '.' . $row->field_key] = (string)$row->content;
        }

        $photoRows = $this->fetchTable('SitePhotos')
            ->find()
            ->where(['is_active' => true])
            ->orderBy(['section_key' => 'ASC', 'sort_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        $photosBySection = [];
        foreach ($photoRows as $photo) {
            $photosBySection[$photo->section_key][] = $photo;
        }

        $reviews = $this->fetchTable('SiteReviews')
            ->find()
            ->where(['is_published' => true])
            ->orderBy(['sort_order' => 'ASC', 'id' => 'ASC'])
            ->all();

        // Get confirmed reservation date ranges for homepage date picker
        $confirmed = $this->fetchTable('Reservations')
            ->find()
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
        $this->set(compact('texts', 'photosBySection', 'reviews', 'confirmedRanges'));
    }

    public function reserve(): Response
    {
        $this->request->allowMethod(['post']);

        $payload = [
            'full_name' => trim((string)$this->request->getData('name')),
            'email' => trim((string)$this->request->getData('email')),
            'phone' => trim((string)$this->request->getData('phone')),
            'checkin_date' => $this->request->getData('checkin'),
            'checkout_date' => $this->request->getData('checkout'),
            'guests' => (int)$this->request->getData('guests'),
            'message' => trim((string)$this->request->getData('message')),
            'source' => 'website',
            'status' => 'new',
        ];

        $reservations = $this->fetchTable('Reservations');
        $reservation = $reservations->newEntity($payload);

        if ($reservations->save($reservation)) {
            $this->Flash->success('Bedankt! We nemen zo snel mogelijk contact met u op.');
        } else {
            $this->Flash->error('Gelieve alle verplichte velden correct in te vullen.');
        }

        return $this->redirect('/#reservation');
    }
}
