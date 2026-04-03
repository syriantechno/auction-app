<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Car extends Model
{
    protected static array $fallbackImages = [
        '/images/cars/home-car.png',
        '/images/cars/car-silver.png',
        '/images/cars/elite-navy-car.png',
        '/images/cars/mclaren.png',
    ];

    protected $fillable = [
        'brand_id',
        'car_model_id',
        'make',
        'model',
        'year',
        'vin',
        'ownership_type',
        'status',
        'base_price',
        'image_url',
        'inspection_data',
        'inspection_report_pdf',
    ];

    protected $casts = [
        'inspection_data' => 'array',
    ];

    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }

    public function inspectionReports()
    {
        return $this->hasMany(InspectionReport::class);
    }

    public function latestInspection()
    {
        return $this->hasOne(InspectionReport::class)->latestOfMany();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class, 'car_model_id');
    }

    public function getLogoUrlAttribute(): string
    {
        $make = $this->brand?->name ?: $this->make;
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
        
        // Try SVG then PNG
        $files = [$searchName . '.svg', $searchName . '.png', $localName . '.svg', $localName . '.png'];

        foreach($files as $file) {
            if(file_exists(public_path('images/brands/' . $file))) {
                return asset('images/brands/' . $file);
            }
        }

        return 'https://cdn.simpleicons.org/' . $searchName . '/000000';
    }

    public function getImageUrlAttribute(): string
    {
        $storedImage = $this->attributes['image_url'] ?? null;

        if (is_string($storedImage) && trim($storedImage) !== '') {
            return $storedImage;
        }

        return self::$fallbackImages[$this->id % count(self::$fallbackImages)] ?? self::$fallbackImages[0];
    }
}
