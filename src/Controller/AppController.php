<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
    }

    public function beforeRender(EventInterface $event): void
    {
        parent::beforeRender($event);

        if (Configure::read('debug')) {
            $this->setResponse(
                $this->getResponse()->withHeader('X-Robots-Tag', 'noindex, nofollow, noarchive')
            );
        }
    }
}
