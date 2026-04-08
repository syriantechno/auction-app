<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Penalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'penalty_type',
        'penalty_date',
        'amount',
        'reason',
        'notes',
        'status',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'penalty_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function getPenaltyTypeLabelAttribute(): string
    {
        return match ($this->penalty_type) {
            'late' => 'Late Arrival',
            'absence' => 'Absence',
            'misconduct' => 'Misconduct',
            'other' => 'Other',
            default => $this->penalty_type
        };
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
            'approved' => ['label' => 'Approved', 'class' => 'bg-blue-100 text-blue-800'],
            'deducted' => ['label' => 'Deducted', 'class' => 'bg-green-100 text-green-800'],
            default => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-800']
        };
    }
}
