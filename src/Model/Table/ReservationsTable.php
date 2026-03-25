<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class ReservationsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('reservations');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('full_name')
            ->maxLength('full_name', 150)
            ->requirePresence('full_name', 'create')
            ->notEmptyString('full_name');

        $validator
            ->email('email')
            ->maxLength('email', 190)
            ->requirePresence('email', 'create')
            ->notEmptyString('email');

        $validator
            ->scalar('phone')
            ->maxLength('phone', 40)
            ->allowEmptyString('phone');

        $validator
            ->date('checkin_date')
            ->requirePresence('checkin_date', 'create')
            ->notEmptyDate('checkin_date');

        $validator
            ->date('checkout_date')
            ->requirePresence('checkout_date', 'create')
            ->notEmptyDate('checkout_date')
            ->add('checkout_date', 'afterCheckin', [
                'rule' => function ($value, $context): bool {
                    $checkin = $context['data']['checkin_date'] ?? null;
                    if (!$checkin || !$value) {
                        return true;
                    }

                    return (string)$value > (string)$checkin;
                },
                'message' => 'De vertrekdatum moet na de aankomstdatum liggen.',
            ]);

        $validator
            ->integer('guests')
            ->requirePresence('guests', 'create')
            ->notEmptyString('guests')
            ->add('guests', 'range', [
                'rule' => ['range', 0, 11],
                'message' => 'Aantal gasten moet tussen 1 en 10 liggen.',
            ]);

        $validator
            ->scalar('message')
            ->allowEmptyString('message');

        return $validator;
    }
}
