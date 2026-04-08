<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Leave extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'employee_id',
        'department_id',
        'leave_type',
        'reason_category',
        'start_date',
        'end_date',
        'days_count',
        'is_paid',
        'status',
        'reason_details',
        'notes',
        'approved_by',
        'approved_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'is_paid' => 'boolean'
    ];

    protected $appends = [
        'leave_type_label',
        'status_label',
        'reason_category_label'
    ];

    public const STATUSES = ['pending', 'approved', 'rejected'];
    public const TYPES = ['annual', 'sick', 'unpaid', 'emergency', 'maternity'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    public function getLeaveTypeLabelAttribute(): string
    {
        $options = [
            'annual' => 'Annual Leave',
            'sick' => 'Sick Leave',
            'unpaid' => 'Unpaid Leave',
            'emergency' => 'Emergency Leave',
            'maternity' => 'Maternity Leave'
        ];
        return $options[$this->leave_type] ?? $this->leave_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst($this->status);
    }

    public function getReasonCategoryLabelAttribute(): string
    {
        $options = [
            'personal' => 'Personal',
            'family' => 'Family',
            'medical' => 'Medical',
            'work' => 'Work',
            'other' => 'Other'
        ];
        return $options[$this->reason_category] ?? $this->reason_category;
    }

    public function getDateRangeLabelAttribute(): string
    {
        return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
    }
}
