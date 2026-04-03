<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionReport extends Model
{
    protected $fillable = [
        'car_id',
        'expert_id',
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
        'paint_score' => 'integer',
        'engine_score' => 'integer',
        'transmission_score' => 'integer',
        'interior_score' => 'integer',
        'tires_score' => 'integer',
        'overall_score' => 'integer',
    ];

    /**
     * الحصول على السيارة المرتبطة بالفحص.
     */
    public function car(): BelongsTo
    {
        return $this->belongsTo(Car::class);
    }

    /**
     * الحصول على الخبير الذي قام بالفحص.
     */
    public function expert(): BelongsTo
    {
        return $this->belongsTo(User::class, 'expert_id');
    }
}
