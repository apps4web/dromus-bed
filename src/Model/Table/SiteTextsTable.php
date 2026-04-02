<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

class SiteTextsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('site_texts');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }
}
