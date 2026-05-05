<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_cash_guest_payment_keeps_payment_pending_and_confirms_reservation(): void
    {
        $reservation = $this->createPendingReservation();

        $response = $this->post(route('guest.payment.process', $reservation->guest_token), [
            'payment_method' => 'cash',
        ]);

        $response->assertRedirect(route('guest.success', $reservation->guest_token));

        $reservation->refresh();

        $this->assertSame('confirmed', $reservation->status);
        $this->assertNotNull($reservation->payment);
        $this->assertSame('cash', $reservation->payment->method);
        $this->assertSame('pending', $reservation->payment->status);
        $this->assertNull($reservation->invoice);
    }

    public function test_card_guest_payment_marks_payment_completed_and_generates_invoice(): void
    {
        $reservation = $this->createPendingReservation();

        $response = $this->post(route('guest.payment.process', $reservation->guest_token), [
            'payment_method' => 'card',
            'payment_intent' => 'pi_test_123',
        ]);

        $response->assertRedirect(route('guest.success', $reservation->guest_token));

        $reservation->refresh();

        $this->assertSame('confirmed', $reservation->status);
        $this->assertNotNull($reservation->payment);
        $this->assertSame('completed', $reservation->payment->status);
        $this->assertSame('pi_test_123', $reservation->payment->transaction_id);
        $this->assertNotNull($reservation->invoice);
    }

    private function createPendingReservation(): Reservation
    {
        $roomType = RoomType::create([
            'name' => 'Chambre Test',
            'capacity' => 2,
            'base_price' => 25000,
        ]);

        $room = Room::create([
            'number' => 'T101',
            'room_type_id' => $roomType->id,
            'floor' => 1,
            'status' => 'reserved',
        ]);

        return Reservation::create([
            'room_id' => $room->id,
            'guest_first_name' => 'Jean',
            'guest_last_name' => 'Client',
            'guest_phone' => '+221770000000',
            'guest_email' => 'jean@example.com',
            'guest_token' => 'token-test-'.$room->id,
            'check_in' => now()->addDays(2)->toDateString(),
            'check_out' => now()->addDays(5)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'price_per_night' => 25000,
            'total_amount' => 75000,
            'discount' => 0,
            'tax_amount' => 13500,
            'final_amount' => 91500,
            'status' => 'pending',
        ]);
    }
}
