<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'number',
        'room_type_id',
        'floor',
        'status',
        'notes',
    ];

    const STATUS_AVAILABLE   = 'available';
    const STATUS_RESERVED    = 'reserved';
    const STATUS_OCCUPIED    = 'occupied';
    const STATUS_MAINTENANCE = 'maintenance';

    // Relations
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenanceTickets(): HasMany
    {
        return $this->hasMany(MaintenanceTicket::class);
    }

    // Vérifier disponibilité pour des dates
    public function isAvailable(string $checkIn, string $checkOut, int $excludeId = null): bool
    {
        if ($this->status === self::STATUS_MAINTENANCE) {
            return false;
        }

        $query = $this->reservations()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where(function ($q) use ($checkIn, $checkOut) {
                $q->whereBetween('check_in', [$checkIn, $checkOut])
                  ->orWhereBetween('check_out', [$checkIn, $checkOut])
                  ->orWhere(function ($q2) use ($checkIn, $checkOut) {
                      $q2->where('check_in', '<=', $checkIn)
                         ->where('check_out', '>=', $checkOut);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    // Accessors
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'Disponible',
            'reserved'    => 'Réservée',
            'occupied'    => 'Occupée',
            'maintenance' => 'Maintenance',
            default       => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'available'   => 'green',
            'reserved'    => 'amber',
            'occupied'    => 'blue',
            'maintenance' => 'red',
            default       => 'gray',
        };
    }
}
