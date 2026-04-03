<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionReport extends Model
{
    protected $fillable = [
        'car_id',
        'lead_id',
        'expert_id',
        'inspector_id',
        'scheduled_date',
        'scheduled_time',
        'location',
        'status',
        'paint_score',
        'body_notes',
        'engine_score',
        'engine_notes',
        'transmission_score',
        'transmission_notes',
        'interior_score',
        'interior_notes',
        'tires_score',
        'tires_notes',
        'overall_score',
        'detailed_checklists',
        'expert_summary',
    ];

    protected $casts = [
        'detailed_checklists' => 'array',
        'paint_score'         => 'integer',
        'engine_score'        => 'integer',
        'transmission_score'  => 'integer',
        'interior_score'      => 'integer',
        'tires_score'         => 'integer',
        'overall_score'       => 'integer',
        'scheduled_date'      => 'date',
    ];

    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }

    public function inspector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }
}
