<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'logo_url',
    ];

    public function models(): HasMany
    {
        return $this->hasMany(CarModel::class, 'brand_id');
    }

    public function cars(): HasMany
    {
        return $this->hasMany(Car::class, 'brand_id');
    }

    public function getLogoUrlAttribute(): string
    {
        if (is_string($this->attributes['logo_url'] ?? null) && trim($this->attributes['logo_url']) !== '') {
            return $this->attributes['logo_url'];
        }

        $make = $this->name;
        $localName = strtolower(str_replace([' ', '-'], ['', ''], $make));

        $map = [
            'mercedesbenz' => 'mercedes',
            'volkswagen' => 'volkswagen',
            'landrover' => 'land-rover',
            'astonmartin' => 'astonmartin',
            'alfaromeo' => 'alfaromeo',
            'rollsroyce' => 'rolls-royce',
        ];

        $searchName = $map[$localName] ?? $localName;
        $files = [$searchName . '.svg', $searchName . '.png', $localName . '.svg', $localName . '.png'];

        foreach ($files as $file) {
            if (file_exists(public_path('images/brands/' . $file))) {
                return asset('images/brands/' . $file);
            }
        }

        return 'https://cdn.simpleicons.org/' . $searchName . '/000000';
    }
}
