<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'start_time',
        'end_time',
        'working_hours',
        'break_start',
        'break_end',
        'break_hours',
        'grace_period',
        'overtime_start_after',
        'applicable_to',
        'department_id',
        'employee_id',
        'work_days',
        'color',
        'is_active'
    ];

    protected $casts = [
        'work_days' => 'array',
        'is_active' => 'boolean',
        'working_hours' => 'decimal:2',
        'break_hours' => 'decimal:2',
        'grace_period' => 'integer',
        'overtime_start_after' => 'decimal:2'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SystemSetting::class, 'company_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'default_shift_id');
    }
}
