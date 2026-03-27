<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\IdentityInterface;
use Authorization\Policy\BeforePolicyInterface;

class ReservationPolicy implements BeforePolicyInterface
{
    public function before(?IdentityInterface $identity, mixed $resource, string $action): bool|null
    {
        return $this->isAdmin($identity) ? true : null;
    }

    public function canView(?IdentityInterface $identity, mixed $reservation): bool
    {
        return false;
    }

    public function canAdd(?IdentityInterface $identity, mixed $reservation): bool
    {
        return false;
    }

    public function canEdit(?IdentityInterface $identity, mixed $reservation): bool
    {
        return false;
    }

    public function canDelete(?IdentityInterface $identity, mixed $reservation): bool
    {
        return false;
    }

    private function isAdmin(?IdentityInterface $identity): bool
    {
        return $identity !== null && (string)$identity->get('role') === 'admin';
    }
}
