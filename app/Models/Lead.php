<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int|null $user_id
 * @property array $car_details
 * @property string $status
 * @property string|null $notes
 * @property-read \App\Models\User|null $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InspectionReport> $inspections
 */
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

    public function inspections()
    {
        return $this->hasMany(InspectionReport::class);
    }
}
