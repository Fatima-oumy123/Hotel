<?php
namespace App\Jobs;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendReservationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public Reservation $reservation) {}

    public function handle(): void
    {
        if (!$this->reservation->guest_email) return;

        Mail::send('emails.reservation-confirmation', ['reservation' => $this->reservation], function($mail) {
            $mail->to($this->reservation->guest_email, $this->reservation->guest_full_name)
                 ->subject("Confirmation de votre réservation {$this->reservation->booking_number}");
        });
    }

    public function failed(\Throwable $exception): void
    {
        \Log::error("SendReservationEmail failed for reservation {$this->reservation->id}: " . $exception->getMessage());
    }
}
