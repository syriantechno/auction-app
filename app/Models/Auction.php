<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $car_id
 * @property int|null $lead_id
 * @property string|null $reference_code
 * @property string $status
 * @property float $initial_price
 * @property float $current_price
 * @property float|null $deposit_amount
 * @property int|null $duration_minutes
 * @property int $bids_count
 * @property float $bid_increment
 * @property int $time_extension_threshold
 * @property int $time_extension_seconds
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property-read \App\Models\Car $car
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bid> $bids
 * @property-read \App\Models\Lead|null $lead
 */
class Auction extends Model
{
    protected $fillable = [
        'car_id',
        'lead_id',
        'reference_code',
        'start_at',
        'end_at',
        'initial_price',
        'current_price',
        'deposit_type',
        'deposit_amount',
        'status',
        'duration_minutes',
        'bid_increment',
        'time_extension_threshold',
        'time_extension_seconds',
    ];

    protected $casts = [
        'start_at'                 => 'datetime',
        'end_at'                   => 'datetime',
        'bid_increment'            => 'decimal:2',
        'time_extension_threshold' => 'integer',
        'time_extension_seconds'   => 'integer',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function negotiation()
    {
        return $this->hasOne(Negotiation::class);
    }
}
