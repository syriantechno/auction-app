<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeEvaluationItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_evaluation_id',
        'evaluation_criterion_id',
        'score',
        'notes'
    ];

    protected $casts = [
        'score' => 'integer'
    ];

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(EmployeeEvaluation::class, 'employee_evaluation_id');
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(EvaluationCriterion::class, 'evaluation_criterion_id');
    }
}
