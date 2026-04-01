<?php
namespace App\Policy;

use Authorization\IdentityInterface;
use App\Model\Table\ReservationsTable;

class ReservationsTablePolicy
{
    /**
     * Scope for index action (list reservations)
     */
    public function scopeIndex($user, $query)
    {
        // Only show non-archived reservations, order by checkin_date desc, id desc
        return $query
            ->where(['status !=' => 'archived'])
            ->order(['checkin_date' => 'DESC', 'id' => 'DESC']);
    }

    /**
     * Scope for add action (create reservation)
     */
    public function scopeAdd($user, $query)
    {
        return $query;
    }

    /**
     * Scope for edit action (update reservation)
     */
    public function scopeEdit($user, $query)
    {
        return $query;
    }
    /**
     * Check if $user can view reservations.
     */
    public function canIndex(IdentityInterface $user, ReservationsTable $table)
    {
        // Allow all authenticated users by default
        return true;
    }

    /**
     * Check if $user can view a reservation.
     */
    public function canView(IdentityInterface $user, ReservationsTable $table, $reservation)
    {
        return true;
    }

    /**
     * Check if $user can add a reservation.
     */
    public function canAdd(IdentityInterface $user, ReservationsTable $table)
    {
        return true;
    }

    /**
     * Check if $user can edit a reservation.
     */
    public function canEdit(IdentityInterface $user, ReservationsTable $table, $reservation)
    {
        return true;
    }

    /**
     * Check if $user can delete a reservation.
     */
    public function canDelete(IdentityInterface $user, ReservationsTable $table, $reservation)
    {
        return true;
    }
}
