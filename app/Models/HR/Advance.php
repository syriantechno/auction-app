<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Advance extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'amount',
        'request_date',
        'approved_date',
        'status',
        'reason',
        'repayment_method',
        'installments_count',
        'installment_amount',
        'remaining_amount',
        'last_deduction_date',
        'approved_by'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'installment_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'request_date' => 'date',
        'approved_date' => 'date',
        'last_deduction_date' => 'date'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'pending' => ['label' => 'Pending', 'class' => 'bg-yellow-100 text-yellow-800'],
            'approved' => ['label' => 'Approved', 'class' => 'bg-blue-100 text-blue-800'],
            'rejected' => ['label' => 'Rejected', 'class' => 'bg-red-100 text-red-800'],
            'paid' => ['label' => 'Paid', 'class' => 'bg-green-100 text-green-800'],
            default => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-800']
        };
    }
}
