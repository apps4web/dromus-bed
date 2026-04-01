<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Response;

class UsersController extends AppController
{
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);

        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login(): ?Response
    {
        $this->Authorization->skipAuthorization();

        $this->viewBuilder()->setLayout('default');

        $result = $this->Authentication->getResult();
        if ($result && $result->isValid()) {
            $target = $this->request->getQuery('redirect');

            if (is_string($target) && $target !== '') {
                return $this->redirect($target);
            }

            return $this->redirect(['controller' => 'Admin', 'action' => 'dashboard']);
        }

        if ($this->request->is('post') && (!$result || !$result->isValid())) {
            $this->Flash->error('Ongeldig e-mailadres of wachtwoord.');
        }

        return null;
    }

    public function logout(): Response
    {
        $this->Authorization->skipAuthorization();

        $this->request->allowMethod(['get', 'post']);

        $this->Authentication->logout();
        $this->Flash->success('U bent uitgelogd.');

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
