<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'month',
        'year',
        'basic_salary',
        'working_days',
        'actual_working_days',
        'working_hours_per_day',
        'hourly_rate',
        'earned_salary',
        'overtime_hours',
        'overtime_multiplier',
        'overtime_amount',
        'weekend_overtime_hours',
        'weekend_overtime_multiplier',
        'weekend_overtime_amount',
        'total_overtime_amount',
        'deductions',
        'deduction_details',
        'bonuses',
        'bonus_details',
        'absent_days',
        'absent_deduction',
        'late_minutes',
        'late_deduction',
        'half_days',
        'half_day_deduction',
        'unpaid_leave_days',
        'unpaid_leave_deduction',
        'gross_salary',
        'net_salary',
        'status',
        'payment_date',
        'payment_method',
        'notes',
        'generated_by',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'earned_salary' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'overtime_multiplier' => 'decimal:2',
        'overtime_amount' => 'decimal:2',
        'weekend_overtime_hours' => 'decimal:2',
        'weekend_overtime_multiplier' => 'decimal:2',
        'weekend_overtime_amount' => 'decimal:2',
        'total_overtime_amount' => 'decimal:2',
        'deductions' => 'decimal:2',
        'deduction_details' => 'array',
        'bonuses' => 'decimal:2',
        'bonus_details' => 'array',
        'absent_deduction' => 'decimal:2',
        'late_deduction' => 'decimal:2',
        'half_day_deduction' => 'decimal:2',
        'unpaid_leave_deduction' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'payment_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeForMonth($query, $year, $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'draft' => ['label' => 'Draft', 'class' => 'bg-gray-100 text-gray-800'],
            'calculated' => ['label' => 'Calculated', 'class' => 'bg-blue-100 text-blue-800'],
            'approved' => ['label' => 'Approved', 'class' => 'bg-green-100 text-green-800'],
            'paid' => ['label' => 'Paid', 'class' => 'bg-purple-100 text-purple-800'],
            default => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-800']
        };
    }
}
