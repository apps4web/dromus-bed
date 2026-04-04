<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Reservation;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use DateTimeInterface;

class ReservationMailer extends Mailer
{
    /**
     * Read reservation mail settings.
     *
     * @return array<string, string>
     */
    private function getReservationEmailConfig(): array
    {
        return [
            'notificationTo' => trim((string)Configure::read('ReservationEmail.notificationTo', '')),
            'fromEmail' => trim((string)Configure::read('ReservationEmail.fromEmail', '')),
            'fromName' => trim((string)Configure::read('ReservationEmail.fromName', 'Dromus Bed & Boetiek')),
        ];
    }

    /**
     * Format a reservation date for emails.
     *
     * @param mixed $value Date value.
     * @return string
     */
    private function formatReservationDate(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('d-m-Y');
        }

        return trim((string)$value);
    }

    /**
     * Build the shared email template variables.
     *
     * @param \App\Model\Entity\Reservation $reservation Reservation entity.
     * @return array<string, mixed>
     */
    private function buildReservationViewVars(Reservation $reservation): array
    {
        return [
            'reservation' => $reservation,
            'reservationId' => (string)$reservation->id,
            'fullName' => trim((string)$reservation->full_name),
            'email' => trim((string)$reservation->email),
            'phone' => trim((string)$reservation->phone),
            'checkinDate' => $this->formatReservationDate($reservation->checkin_date),
            'checkoutDate' => $this->formatReservationDate($reservation->checkout_date),
            'guests' => (string)$reservation->guests,
            'message' => trim((string)$reservation->message),
            'status' => trim((string)$reservation->status),
            'source' => trim((string)$reservation->source),
        ];
    }

    /**
     * Send the internal reservation notification.
     *
     * @param \App\Model\Entity\Reservation $reservation Reservation entity.
     * @return void
     */
    public function adminNotification(Reservation $reservation): void
    {
        $config = $this->getReservationEmailConfig();
        if ($config['notificationTo'] === '') {
            return;
        }

        $this
            ->setTo($config['notificationTo'])
            ->setFrom($config['fromEmail'], $config['fromName'])
            ->setReplyTo($reservation->email, $reservation->full_name)
            ->setSubject('Nieuwe reserveringsaanvraag van ' . (string)$reservation->full_name)
            ->setEmailFormat('both');

        $this->viewBuilder()
            ->setTemplate('reservation_admin_notification')
            ->disableAutoLayout();

        $this->set($this->buildReservationViewVars($reservation));
    }

    /**
     * Send the guest confirmation email.
     *
     * @param \App\Model\Entity\Reservation $reservation Reservation entity.
     * @return void
     */
    public function guestConfirmation(Reservation $reservation): void
    {
        $config = $this->getReservationEmailConfig();

        $this
            ->setTo($reservation->email, $reservation->full_name)
            ->setFrom($config['fromEmail'], $config['fromName'])
            ->setSubject('Bevestiging van uw reserveringsaanvraag')
            ->setEmailFormat('both');

        if ($config['notificationTo'] !== '') {
            $this->setReplyTo($config['notificationTo'], $config['fromName']);
        }

        $this->viewBuilder()
            ->setTemplate('reservation_guest_confirmation')
            ->disableAutoLayout();

        $this->set($this->buildReservationViewVars($reservation));
    }
}
