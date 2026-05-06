<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reservation_id','amount','method','status',
        'local_channel','payer_phone','transaction_id','reference','notes','paid_at',
    ];
    protected $casts = ['paid_at' => 'datetime', 'amount' => 'decimal:2'];

    public function reservation() { return $this->belongsTo(Reservation::class); }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'card'     => 'Carte bancaire',
            'cash'     => 'Espèces',
            'check'    => 'Chèque',
            'transfer' => $this->local_channel === 'mobile_money' ? 'Mobile Money' : 'Virement',
            default    => $this->method,
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'completed' => 'badge-success',
            'pending'   => 'badge-warning',
            'failed'    => 'badge-danger',
            'refunded'  => 'badge-secondary',
            default     => 'badge-secondary',
        };
    }
}
