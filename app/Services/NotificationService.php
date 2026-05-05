<?php
namespace App\Services;

use App\Models\{Reservation, User};
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public function sendReservationConfirmation(Reservation $reservation): void
    {
        if (!$reservation->guest_email) return;
        Mail::send('emails.reservation-confirmation', compact('reservation'), function($mail) use ($reservation) {
            $mail->to($reservation->guest_email, $reservation->guest_full_name)
                 ->subject("✅ Confirmation — {$reservation->booking_number}");
        });
    }

    public function sendReservationCancellation(Reservation $reservation): void
    {
        if (!$reservation->guest_email) return;
        Mail::send('emails.reservation-cancellation', compact('reservation'), function($mail) use ($reservation) {
            $mail->to($reservation->guest_email, $reservation->guest_full_name)
                 ->subject("❌ Annulation — {$reservation->booking_number}");
        });
    }

    public function sendPaymentConfirmation(Reservation $reservation): void
    {
        if (!$reservation->guest_email) return;
        Mail::send('emails.payment-confirmation', compact('reservation'), function($mail) use ($reservation) {
            $mail->to($reservation->guest_email, $reservation->guest_full_name)
                 ->subject("💳 Paiement reçu — {$reservation->booking_number}");
        });
    }

    public function notifyManagers(string $subject, string $message): void
    {
        $managers = User::role('manager')->where('is_active', true)->get();
        foreach ($managers as $manager) {
            Mail::raw($message, fn($mail) => $mail->to($manager->email)->subject($subject));
        }
    }
}
