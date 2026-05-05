<?php
namespace App\Services;

use App\Models\RoomType;
use App\Models\SeasonalRate;
use Carbon\Carbon;

class PricingService
{
    /**
     * Calcule le prix complet pour un séjour
     */
    public function calculatePrice(int $roomTypeId, string $checkIn, string $checkOut): array
    {
        $roomType = RoomType::findOrFail($roomTypeId);

        $checkInDate  = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);
        $nights       = $checkInDate->diffInDays($checkOutDate);

        if ($nights <= 0) {
            throw new \InvalidArgumentException('La date de départ doit être après la date d\'arrivée.');
        }

        // Cherche un tarif saisonnier actif
        $seasonalRate = SeasonalRate::where('room_type_id', $roomTypeId)
            ->where('start_date', '<=', $checkIn)
            ->where('end_date',   '>=', $checkIn)
            ->first();

        $pricePerNight = $seasonalRate
            ? $seasonalRate->price_per_night
            : $roomType->base_price;

        $subtotal = $pricePerNight * $nights;

        // Réductions longue durée
        $discount = 0;
        if ($nights >= 30) {
            $discount = $subtotal * 0.15; // 15% pour 30+ nuits
        } elseif ($nights >= 14) {
            $discount = $subtotal * 0.10; // 10% pour 14+ nuits
        } elseif ($nights >= 7) {
            $discount = $subtotal * 0.05; // 5% pour 7+ nuits
        }

        $taxRate       = (float) config('hotel.tax_rate', 18);
        $stayTax       = (float) config('hotel.stay_tax_per_night', 1000);
        $taxAmount     = ($subtotal - $discount) * ($taxRate / 100);
        $stayTaxTotal  = $stayTax * $nights;
        $finalAmount   = $subtotal - $discount + $taxAmount + $stayTaxTotal;

        return [
            'price_per_night' => round($pricePerNight, 2),
            'nights'          => (int) $nights,
            'subtotal'        => round($subtotal, 2),
            'discount'        => round($discount, 2),
            'tax_rate'        => $taxRate,
            'tax_amount'      => round($taxAmount, 2),
            'stay_tax'        => round($stayTaxTotal, 2),
            'final_amount'    => round($finalAmount, 2),
            'seasonal_rate'   => $seasonalRate ? $seasonalRate->name : null,
        ];
    }
}
