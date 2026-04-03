<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'user_id',
        'car_details',
        'status',
        'notes',
    ];

    protected $casts = [
        'car_details' => 'array',
    ];

    public function getLogoUrlAttribute(): ?string
    {
        $make = $this->car_details['make'] ?? null;
        if (!$make) return null;

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
        
        // Try SVG then PNG locally
        $files = [$searchName . '.svg', $searchName . '.png', $localName . '.svg', $localName . '.png'];

        foreach($files as $file) {
            if(file_exists(public_path('images/brands/' . $file))) {
                return asset('images/brands/' . $file);
            }
        }

        return 'https://cdn.simpleicons.org/' . $searchName . '/000000';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
