<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'reward_type',
        'title',
        'reward_date',
        'amount',
        'description',
        'notes',
        'is_paid',
        'paid_date',
        'approved_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'reward_date' => 'date',
        'paid_date' => 'date',
        'is_paid' => 'boolean'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function getRewardTypeLabelAttribute(): string
    {
        return match ($this->reward_type) {
            'bonus' => 'Bonus',
            'allowance' => 'Allowance',
            'gift' => 'Gift',
            'certificate' => 'Certificate',
            'promotion' => 'Promotion',
            default => $this->reward_type
        };
    }

    public function getTypeBadgeAttribute(): array
    {
        return match ($this->reward_type) {
            'bonus' => ['label' => 'Bonus', 'class' => 'bg-green-100 text-green-800'],
            'allowance' => ['label' => 'Allowance', 'class' => 'bg-blue-100 text-blue-800'],
            'gift' => ['label' => 'Gift', 'class' => 'bg-purple-100 text-purple-800'],
            'certificate' => ['label' => 'Certificate', 'class' => 'bg-yellow-100 text-yellow-800'],
            'promotion' => ['label' => 'Promotion', 'class' => 'bg-indigo-100 text-indigo-800'],
            default => ['label' => $this->reward_type, 'class' => 'bg-gray-100 text-gray-800']
        };
    }
}
