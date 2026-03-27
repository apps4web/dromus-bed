<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Table\ReservationsTable;
use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;
use Cake\ORM\Query\SelectQuery;

class ReservationsTablePolicy implements BeforePolicyInterface
{
    public function before(?IdentityInterface $identity, mixed $resource, string $action): bool|null
    {
        return $this->isAdmin($identity) ? true : null;
    }

    public function canIndex(?IdentityInterface $identity, ReservationsTable $reservations): bool
    {
        return false;
    }

    public function scopeIndex(?IdentityInterface $identity, SelectQuery $query): SelectQuery
    {
        if ($this->isAdmin($identity)) {
            return $query;
        }

        return $query->where(['1 = 0']);
    }

    private function isAdmin(?IdentityInterface $identity): bool
    {
        return $identity !== null && (string)$identity->get('role') === 'admin';
    }
}
