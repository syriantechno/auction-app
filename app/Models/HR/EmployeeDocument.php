<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'document_type',
        'document_number',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'file_path',
        'notes',
        'is_active'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function getIsExpiredAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isPast();
    }

    public function getIsExpiringSoonAttribute(): bool
    {
        return $this->expiry_date && $this->expiry_date->isFuture() && $this->expiry_date->diffInDays(now()) <= 30;
    }

    public function getStatusBadgeAttribute(): array
    {
        if ($this->is_expired) {
            return ['label' => 'Expired', 'class' => 'bg-red-100 text-red-800'];
        } elseif ($this->is_expiring_soon) {
            return ['label' => 'Expiring Soon', 'class' => 'bg-orange-100 text-orange-800'];
        } else {
            return ['label' => 'Active', 'class' => 'bg-green-100 text-green-800'];
        }
    }
}
