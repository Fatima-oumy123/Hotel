<?php
namespace App\Services;

use App\Models\Room;
use App\Models\Reservation;
use Carbon\Carbon;

class AvailabilityService
{
    /**
     * Retourne toutes les chambres disponibles pour les dates données
     */
    public function getAvailableRooms(string $checkIn, string $checkOut, int $roomTypeId = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = Room::with('roomType')
            ->whereNotIn('status', ['maintenance']);

        if ($roomTypeId) {
            $query->where('room_type_id', $roomTypeId);
        }

        $rooms = $query->get();

        return $rooms->filter(function ($room) use ($checkIn, $checkOut) {
            return $room->isAvailable($checkIn, $checkOut);
        });
    }

    /**
     * Vérifie si une chambre spécifique est disponible
     */
    public function checkRoomAvailability(
        int $roomId,
        string $checkIn,
        string $checkOut,
        int $excludeReservationId = null
    ): bool {
        $room = Room::find($roomId);
        if (!$room) return false;

        return $room->isAvailable($checkIn, $checkOut, $excludeReservationId);
    }

    /**
     * Calcule le taux d'occupation actuel
     */
    public function getOccupancyRate(Carbon $date = null): float
    {
        $date  = $date ?? Carbon::today();
        $total = Room::count();

        if ($total === 0) return 0;

        $occupied = Reservation::where('check_in', '<=', $date)
            ->where('check_out', '>', $date)
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->count();

        return round(($occupied / $total) * 100, 2);
    }

    /**
     * Retourne le planning des réservations pour une période
     */
    public function getPlanningForPeriod(Carbon $from, Carbon $to): \Illuminate\Support\Collection
    {
        return Reservation::with('room.roomType')
            ->where(function ($q) use ($from, $to) {
                $q->whereBetween('check_in', [$from, $to])
                  ->orWhereBetween('check_out', [$from, $to])
                  ->orWhere(function ($q2) use ($from, $to) {
                      $q2->where('check_in', '<=', $from)
                         ->where('check_out', '>=', $to);
                  });
            })
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->get();
    }
}
