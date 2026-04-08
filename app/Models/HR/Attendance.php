<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'shift_id',
        'latitude',
        'longitude',
        'attendance_date',
        'status',
        'notes',
        'check_in',
        'check_out',
        'working_hours',
        'overtime_hours',
        'location_id',
        'device_id',
        'break_duration',
        'late_minutes',
        'early_departure_minutes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
        'working_hours' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'break_duration' => 'decimal:2',
        'late_minutes' => 'integer',
        'early_departure_minutes' => 'integer'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class);
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'success',
            'absent' => 'danger',
            'vacation' => 'info',
            'travel' => 'warning',
            'half_day' => 'secondary',
            'holiday' => 'primary',
            'sick_leave' => 'warning',
            'unpaid_leave' => 'danger',
            default => 'secondary'
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match ($this->status) {
            'present' => 'check-circle',
            'absent' => 'x-circle',
            'vacation' => 'umbrella',
            'travel' => 'plane',
            'half_day' => 'clock',
            'holiday' => 'calendar',
            'sick_leave' => 'heart-pulse',
            'unpaid_leave' => 'alert-circle',
            default => 'help-circle'
        };
    }
}
