<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_number','reservation_id','subtotal','tax_rate',
        'tax_amount','stay_tax','total','status','pdf_path','issued_at',
    ];
    protected $casts = ['issued_at' => 'datetime'];

    public function reservation() { return $this->belongsTo(Reservation::class); }

    public function getDownloadUrlAttribute(): ?string
    {
        return $this->pdf_path ? asset('storage/' . $this->pdf_path) : null;
    }
}
