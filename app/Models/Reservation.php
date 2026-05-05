<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Reservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_number',
        'room_id',
        'customer_id',
        'user_id',
        'guest_first_name',
        'guest_last_name',
        'guest_dob',
        'guest_id_number',
        'guest_phone',
        'guest_email',
        'guest_token',
        'check_in',
        'check_out',
        'actual_check_in',
        'actual_check_out',
        'price_per_night',
        'total_amount',
        'discount',
        'tax_amount',
        'final_amount',
        'status',
        'cancellation_reason',
        'adults',
        'children',
        'special_requests',
    ];

    protected $casts = [
        'check_in'  => 'date',
        'check_out' => 'date',
        'price_per_night' => 'decimal:2',
        'total_amount'    => 'decimal:2',
        'discount'        => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'final_amount'    => 'decimal:2',
    ];

    // Auto-génération booking_number et guest_token
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reservation) {
            if (empty($reservation->booking_number)) {
                $year  = date('Y');
                $count = self::whereYear('created_at', $year)->count() + 1;
                $reservation->booking_number = 'REZ-' . $year . '-' . str_pad($count, 5, '0', STR_PAD_LEFT);
            }

            if (empty($reservation->guest_token)) {
                $reservation->guest_token = Str::random(32);
            }
        });
    }

    // Relations
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // Accessors
    public function getGuestFullNameAttribute(): string
    {
        return trim($this->guest_first_name . ' ' . $this->guest_last_name);
    }

    public function getNightsAttribute(): int
    {
        if (!$this->check_in || !$this->check_out) return 0;
        return (int) $this->check_in->diffInDays($this->check_out);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'confirmed'   => 'badge-success',
            'checked_in'  => 'badge-info',
            'checked_out' => 'badge-secondary',
            'cancelled'   => 'badge-danger',
            default       => 'badge-warning',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending'     => 'En attente',
            'confirmed'   => 'Confirmée',
            'checked_in'  => 'En cours',
            'checked_out' => 'Terminée',
            'cancelled'   => 'Annulée',
            default       => $this->status,
        };
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['confirmed', 'checked_in']);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('check_in', today())
                     ->orWhereDate('check_out', today());
    }
}
