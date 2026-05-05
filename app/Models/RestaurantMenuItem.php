<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantMenuItem extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(RestaurantOrderItem::class, 'menu_item_id');
    }
}
