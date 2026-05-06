<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class RoomType extends Model
{
    protected $fillable = [
        'name',
        'capacity',
        'base_price',
        'description',
        'amenities',
        'image',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'capacity' => 'integer',
        'amenities' => 'array',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function seasonalRates(): HasMany
    {
        return $this->hasMany(SeasonalRate::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->base_price, 0, ',', ' ').' FCFA';
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Storage::url($this->image);
    }

    public function getAmenitiesListAttribute(): array
    {
        return $this->amenities ?? [];
    }
}
