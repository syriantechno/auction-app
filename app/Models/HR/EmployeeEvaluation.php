<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'evaluation_date',
        'evaluation_period',
        'total_score',
        'result',
        'notes',
        'evaluated_by'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'total_score' => 'integer'
    ];

    protected $appends = [
        'result_label',
        'score_percentage'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function evaluatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'evaluated_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(EmployeeEvaluationItem::class);
    }

    public function getResultLabelAttribute(): string
    {
        return match ($this->result) {
            'excellent' => 'Excellent',
            'good' => 'Good',
            'average' => 'Average',
            'needs_improvement' => 'Needs Improvement',
            default => $this->result
        };
    }

    public function getScorePercentageAttribute(): float
    {
        // Assuming max score is 100
        return min(100, max(0, $this->total_score));
    }

    public function getResultBadgeAttribute(): array
    {
        return match ($this->result) {
            'excellent' => ['label' => 'Excellent', 'class' => 'bg-green-100 text-green-800'],
            'good' => ['label' => 'Good', 'class' => 'bg-blue-100 text-blue-800'],
            'average' => ['label' => 'Average', 'class' => 'bg-yellow-100 text-yellow-800'],
            'needs_improvement' => ['label' => 'Needs Improvement', 'class' => 'bg-red-100 text-red-800'],
            default => ['label' => $this->result, 'class' => 'bg-gray-100 text-gray-800']
        };
    }
}
