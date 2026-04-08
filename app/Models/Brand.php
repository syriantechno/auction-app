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
        // Normalize name for search
        $clean = strtolower(trim($make));
        // Handle variations like "Mercedes-Onyx" -> "mercedes"
        $base = explode(' ', str_replace(['-', '_'], ' ', $clean))[0];
        // Special case mapping
        $localMap = [
            'mercedes-benz' => 'mercedes',
            'mercedes' => 'mercedes',
            'land rover' => 'land-rover',
            'rolls-royce' => 'rollsroyce',
            'aston martin' => 'astonmartin',
            'alfa romeo' => 'alfaromeo',
            'toyota' => 'toyota',
            'nissan' => 'nissan',
            'ford' => 'ford',
            'honda' => 'honda',
            'hyundai' => 'hyundai',
            'kia' => 'kia',
            'porsche' => 'porsche',
            'volkswagen' => 'volkswagen',
            'audi' => 'audi',
            'lexus' => 'lexus',
            'mitsubishi' => 'mitsubishi',
            'chevrolet' => 'chevrolet',
            'chrysler' => 'chrysler',
            'dodge' => 'dodge',
            'jeep' => 'jeep',
            'geely' => 'geely',
            'byd' => 'byd',
            'chery' => 'chery',
            'haval' => 'haval',
            'jac' => 'jac',
            'changan' => 'changan',
            'jetour' => 'jetour',
            'mg' => 'mg',
            'genesis' => 'genesis',
            'saab' => 'saab',
            'citroen' => 'citroen',
            'citroën' => 'citroen',
            'opel' => 'opel',
            'seat' => 'seat',
            'skoda' => 'skoda',
            'peugeot' => 'peugeot',
            'renault' => 'renault',
        ];

        $searchName = $localMap[$clean] ?? $localMap[$base] ?? $clean;
        $searchName = str_replace([' ', '-'], ['', ''], $searchName);

        // 1. Check Local Local Files First (SVG/PNG)
        $files = [$searchName . '.svg', $searchName . '.png', $base . '.svg', $base . '.png'];
        foreach ($files as $file) {
            if (file_exists(public_path('images/brands/' . $file))) {
                return asset('images/brands/' . $file);
            }
        }

        // 2. Primary External Source: fawazahmed0/car-logos (Most robust for cars)
        // Patten: https://cdn.jsdelivr.net/gh/fawazahmed0/car-logos@master/logos/[name].svg
        $cdnSlug = str_replace(' ', '-', $searchName);
        return "https://cdn.jsdelivr.net/gh/fawazahmed0/car-logos@master/logos/{$cdnSlug}.svg";
    }
}
