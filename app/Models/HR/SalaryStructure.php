<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalaryStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'basic_salary',
        'components',
        'is_active'
    ];

    protected $casts = [
        'basic_salary' => 'decimal:2',
        'components' => 'array',
        'is_active' => 'boolean'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function employees(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function getTotalSalaryAttribute(): float
    {
        $componentsTotal = 0;
        if ($this->components && is_array($this->components)) {
            foreach ($this->components as $component) {
                $componentsTotal += $component['amount'] ?? 0;
            }
        }
        return $this->basic_salary + $componentsTotal;
    }
}
