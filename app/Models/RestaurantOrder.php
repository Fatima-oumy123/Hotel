<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RestaurantOrder extends Model
{
    protected $fillable = [
        'order_number',
        'reservation_id',
        'room_id',
        'customer_name',
        'status',
        'payment_status',
        'total_amount',
        'notes',
        'ordered_at',
        'served_at',
    ];

    protected $casts = [
        'ordered_at' => 'datetime',
        'served_at' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (RestaurantOrder $order) {
            if (!$order->order_number) {
                $order->order_number = 'CMD-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
            }
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(RestaurantOrderItem::class);
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
