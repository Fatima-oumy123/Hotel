<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RestaurantSale extends Model
{
    protected $fillable = [
        'item_name','category','quantity','unit_price','total',
        'payment_method','table_number','notes','status',
        'sale_date','cashier_id','cancellation_reason',
    ];
    protected $casts = ['sale_date' => 'datetime', 'total' => 'decimal:2'];

    public function cashier() { return $this->belongsTo(User::class, 'cashier_id'); }
}
