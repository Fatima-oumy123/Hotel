<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripeIntentTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_intent_endpoint_returns_clear_error_when_not_configured(): void
    {
        config([
            'services.stripe.key' => null,
            'services.stripe.secret' => null,
        ]);

        $user = User::factory()->create();
        $reservation = $this->createReservation();

        $response = $this->actingAs($user)->postJson(route('stripe.intent'), [
            'amount' => 91500,
            'reservation_id' => $reservation->id,
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'message' => 'Le paiement par carte n\'est pas configuré.',
        ]);
    }

    private function createReservation(): Reservation
    {
        $roomType = RoomType::create([
            'name' => 'Suite Test',
            'capacity' => 2,
            'base_price' => 30000,
        ]);

        $room = Room::create([
            'number' => 'T201',
            'room_type_id' => $roomType->id,
            'floor' => 2,
            'status' => 'reserved',
        ]);

        return Reservation::create([
            'room_id' => $room->id,
            'guest_first_name' => 'Awa',
            'guest_last_name' => 'Test',
            'guest_phone' => '+221780000000',
            'guest_email' => 'awa@example.com',
            'guest_token' => 'token-stripe-'.$room->id,
            'check_in' => now()->addDay()->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'adults' => 2,
            'children' => 0,
            'price_per_night' => 30000,
            'total_amount' => 60000,
            'discount' => 0,
            'tax_amount' => 10800,
            'final_amount' => 72800,
            'status' => 'pending',
        ]);
    }
}
