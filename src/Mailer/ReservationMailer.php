<?php
declare(strict_types=1);

namespace App\Mailer;

use App\Model\Entity\Reservation;
use Cake\Core\Configure;
use Cake\Mailer\Mailer;
use Cake\Log\Log;
use DateTimeInterface;

class ReservationMailer extends Mailer
{
    /**
     * Convert reservation status codes into readable labels for guests.
     */
    private function statusLabel(string $status): string
    {
        $labels = [
            'new' => 'Nieuw',
            'confirmed' => 'Bevestigd',
            'cancelled' => 'Geannuleerd',
        ];

        return $labels[$status] ?? ucfirst($status);
    }

    /**
     * Read reservation mail settings.
     *
     * @return array<string, string>
     */
    private function getReservationEmailConfig(): array
    {
        $notificationTo = trim((string)Configure::read('ReservationEmail.notificationTo', ''));
        $fromEmail = trim((string)Configure::read('ReservationEmail.fromEmail', ''));
        if ($fromEmail === '') {
            $fromEmail = $notificationTo !== '' ? $notificationTo : 'info@dromuszierikzee.nl';
        }

        return [
            'notificationTo' => $notificationTo,
            'fromEmail' => $fromEmail,
            'fromName' => trim((string)Configure::read('ReservationEmail.fromName', 'Dromus Bed & Boetiek')),
            'devEmail' => trim((string)Configure::read('ReservationEmail.devEmail', 'info@niels-mulder.nl')),
        ];
    }

    /**
     * Build BCC list for guest emails: admin + developer.
     *
     * @param array<string, string> $config Email config from getReservationEmailConfig().
     * @return array<int, string>
     */
    private function buildGuestBcc(array $config): array
    {
        $bcc = [];
        if ($config['notificationTo'] !== '') {
            $bcc[] = $config['notificationTo'];
        }
        if ($config['devEmail'] !== '' && $config['devEmail'] !== $config['notificationTo']) {
            $bcc[] = $config['devEmail'];
        }

        return $bcc;
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
        $recipient = $config['notificationTo'] !== ''
            ? $config['notificationTo']
            : $config['fromEmail'];

        if ($recipient === '') {
            Log::warning('Reservation admin notification skipped because no recipient is configured.');
            return;
        }

        $bcc = $this->buildGuestBcc($config);

        $mailer = $this
            ->setTo($recipient)
            ->setFrom($config['fromEmail'], $config['fromName'])
            ->setReplyTo($reservation->email, $reservation->full_name)
            ->setSubject('Nieuwe reserveringsaanvraag van ' . (string)$reservation->full_name)
            ->setEmailFormat('both');

        if ($bcc !== []) {
            $mailer->setBcc($bcc);
        }

        $this->viewBuilder()
            ->setTemplate('reservation_admin_notification')
            ->setLayout('default');

        $this->setViewVars($this->buildReservationViewVars($reservation));
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

        $bcc = $this->buildGuestBcc($config);

        $mailer = $this
            ->setTo($reservation->email, $reservation->full_name)
            ->setFrom($config['fromEmail'], $config['fromName'])
            ->setSubject('Bevestiging van uw reserveringsaanvraag')
            ->setEmailFormat('both');

        if ($bcc !== []) {
            $mailer->setBcc($bcc);
        }

        if ($config['notificationTo'] !== '') {
            $this->setReplyTo($config['notificationTo'], $config['fromName']);
        }

        $this->viewBuilder()
            ->setTemplate('reservation_guest_confirmation')
            ->setLayout('default');

        $this->setViewVars($this->buildReservationViewVars($reservation));
    }

    /**
     * Send a guest email when reservation status changes.
     *
     * @param \App\Model\Entity\Reservation $reservation Reservation entity.
     * @param string $previousStatus Previous status value.
     * @return void
     */
    public function guestStatusUpdate(Reservation $reservation, string $previousStatus): void
    {
        $config = $this->getReservationEmailConfig();
        $currentStatus = trim((string)$reservation->status);

        $bcc = $this->buildGuestBcc($config);

        $mailer = $this
            ->setTo($reservation->email, $reservation->full_name)
            ->setFrom($config['fromEmail'], $config['fromName'])
            ->setSubject('Update van uw reserveringsstatus')
            ->setEmailFormat('both');

        if ($bcc !== []) {
            $mailer->setBcc($bcc);
        }

        if ($config['notificationTo'] !== '') {
            $this->setReplyTo($config['notificationTo'], $config['fromName']);
        }

        $this->viewBuilder()
            ->setTemplate('reservation_guest_status_update')
            ->setLayout('default');

        $viewVars = $this->buildReservationViewVars($reservation);
        $viewVars['previousStatus'] = $this->statusLabel(trim($previousStatus));
        $viewVars['status'] = $this->statusLabel($currentStatus);

        $this->setViewVars($viewVars);
    }
}
