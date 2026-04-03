<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    protected $fillable = [
        'car_id',
        'start_at',
        'end_at',
        'initial_price',
        'current_price',
        'deposit_type',
        'deposit_amount',
        'status',
        'duration_minutes',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
