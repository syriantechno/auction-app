<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'department_id',
        'position_id',
        'job_title',
        'job_description',
        'requirements',
        'status',
        'vacancies',
        'salary_range_from',
        'salary_range_to',
        'opening_date',
        'closing_date',
        'created_by'
    ];

    protected $casts = [
        'vacancies' => 'integer',
        'salary_range_from' => 'decimal:2',
        'salary_range_to' => 'decimal:2',
        'opening_date' => 'date',
        'closing_date' => 'date'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'open' => ['label' => 'Open', 'class' => 'bg-green-100 text-green-800'],
            'in_progress' => ['label' => 'In Progress', 'class' => 'bg-blue-100 text-blue-800'],
            'filled' => ['label' => 'Filled', 'class' => 'bg-purple-100 text-purple-800'],
            'closed' => ['label' => 'Closed', 'class' => 'bg-gray-100 text-gray-800'],
            default => ['label' => $this->status, 'class' => 'bg-gray-100 text-gray-800']
        };
    }

    public function getSalaryRangeAttribute(): string
    {
        if ($this->salary_range_from && $this->salary_range_to) {
            return number_format($this->salary_range_from, 2) . ' - ' . number_format($this->salary_range_to, 2);
        }
        return 'Not specified';
    }
}
