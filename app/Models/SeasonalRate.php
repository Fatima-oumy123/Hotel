<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeasonalRate extends Model
{
    protected $fillable = [
        'room_type_id','name','start_date','end_date',
        'price_per_night','discount_percent',
    ];
    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'price_per_night'  => 'decimal:2',
        'discount_percent' => 'decimal:2',
    ];

    public function roomType() { return $this->belongsTo(RoomType::class); }
}
