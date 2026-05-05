<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        $rooms = Room::with('roomType')->get();
        if ($rooms->isEmpty()) {
            $this->command?->warn('Aucune chambre trouvée. Lancez RoomTypeSeeder + RoomSeeder avant TestDataSeeder.');
            return;
        }

        // ─── Customers ─────────────────────────────────────────────────────
        $customers = collect();
        for ($i = 0; $i < 30; $i++) {
            $first = $faker->firstName();
            $last = $faker->lastName();
            $phone = '+221 7' . $faker->numberBetween(0, 9) . ' ' .
                $faker->numberBetween(10, 99) . ' ' .
                $faker->numberBetween(10, 99) . ' ' .
                $faker->numberBetween(10, 99);

            $customers->push(Customer::firstOrCreate(
                ['phone' => $phone],
                [
                    'first_name' => $first,
                    'last_name' => $last,
                    'email' => $faker->boolean(65) ? $faker->safeEmail() : null,
                    'address' => $faker->boolean(45) ? $faker->address() : null,
                    'national_id' => $faker->boolean(40) ? strtoupper(Str::random(2)) . $faker->numberBetween(100000, 999999) : null,
                    'loyalty_points' => $faker->numberBetween(0, 120),
                    'is_vip' => $faker->boolean(12),
                    'notes' => $faker->boolean(18) ? $faker->sentence(10) : null,
                ]
            ));
        }

        // ─── Reservations + Payments ──────────────────────────────────────
        $statuses = ['confirmed', 'checked_in', 'pending', 'cancelled', 'checked_out'];
        $methods = ['cash', 'card', 'transfer'];
        $mmOperators = ['Orange Money', 'Wave', 'Free Money'];

        for ($i = 0; $i < 24; $i++) {
            $room = $rooms->random();
            $customer = $customers->random();

            $checkIn = Carbon::now()->startOfDay()->addDays($faker->numberBetween(-5, 12));
            $nights = $faker->numberBetween(1, 6);
            $checkOut = (clone $checkIn)->addDays($nights);

            $pricePerNight = (float) ($room->roomType?->base_price ?? 25000);
            $subtotal = $pricePerNight * $nights;
            $taxRate = (float) config('hotel.tax_rate', 18);
            $taxAmount = round($subtotal * ($taxRate / 100), 2);
            $final = $subtotal + $taxAmount;

            $status = $statuses[$faker->numberBetween(0, count($statuses) - 1)];
            $adults = min(4, max(1, (int) ($room->roomType?->capacity ?? 2)));

            $reservation = Reservation::create([
                'room_id' => $room->id,
                'customer_id' => $customer->id,
                'guest_first_name' => $customer->first_name,
                'guest_last_name' => $customer->last_name,
                'guest_phone' => $customer->phone,
                'guest_email' => $customer->email,
                'check_in' => $checkIn->toDateString(),
                'check_out' => $checkOut->toDateString(),
                'adults' => $adults,
                'children' => $faker->numberBetween(0, 2),
                'price_per_night' => $pricePerNight,
                'total_amount' => $subtotal,
                'discount' => 0,
                'tax_amount' => $taxAmount,
                'final_amount' => $final,
                'status' => $status,
                'special_requests' => $faker->boolean(25) ? $faker->sentence(8) : null,
            ]);

            if (in_array($status, ['confirmed', 'checked_in', 'checked_out'], true) && $faker->boolean(75)) {
                $method = $methods[$faker->numberBetween(0, count($methods) - 1)];
                $localChannel = null;
                $payerPhone = null;

                if ($method === 'transfer' && $faker->boolean(70)) {
                    $localChannel = 'mobile_money';
                    $payerPhone = $customer->phone;
                }

                Payment::create([
                    'reservation_id' => $reservation->id,
                    'amount' => $final,
                    'method' => $method,
                    'status' => $faker->boolean(85) ? 'completed' : 'pending',
                    'local_channel' => $localChannel,
                    'payer_phone' => $payerPhone,
                    'reference' => $localChannel === 'mobile_money' ? $mmOperators[$faker->numberBetween(0, count($mmOperators) - 1)] : null,
                    'paid_at' => $faker->boolean(85) ? Carbon::now()->subDays($faker->numberBetween(0, 8)) : null,
                ]);
            }
        }

        $this->command?->info('✅ Données de test ajoutées (clients, réservations, paiements).');
    }
}

