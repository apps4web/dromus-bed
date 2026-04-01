<?php
namespace App\Policy;

use Authorization\IdentityInterface;
use App\Model\Entity\Reservation;

class ReservationPolicy
{
    /**
     * All users can add reservations by default
     */
    public function canAdd(IdentityInterface $user, Reservation $reservation)
    {
        return true;
    }

    /**
     * All users can view reservations by default
     */
    public function canView(IdentityInterface $user, Reservation $reservation)
    {
        return true;
    }

    /**
     * All users can edit reservations by default
     */
    public function canEdit(IdentityInterface $user, Reservation $reservation)
    {
        return true;
    }

    /**
     * All users can delete reservations by default
     */
    public function canDelete(IdentityInterface $user, Reservation $reservation)
    {
        return true;
    }
}
