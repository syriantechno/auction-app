<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'car_id', 'auction_id', 'negotiation_id', 'lead_id',
        'reference_code', 'purchase_price', 'dealer_bid', 'profit_margin',
        'status', 'entry_date', 'qc_completed_date', 'delivery_date',
        'ownership_transfer_date', 'amount_received', 'notes',
    ];

    protected $casts = [
        'purchase_price'          => 'decimal:2',
        'dealer_bid'              => 'decimal:2',
        'profit_margin'           => 'decimal:2',
        'amount_received'         => 'decimal:2',
        'entry_date'              => 'date',
        'qc_completed_date'       => 'date',
        'delivery_date'           => 'date',
        'ownership_transfer_date' => 'date',
    ];

    public function car()         { return $this->belongsTo(Car::class); }
    public function auction()     { return $this->belongsTo(Auction::class); }
    public function negotiation() { return $this->belongsTo(Negotiation::class); }
    public function lead()        { return $this->belongsTo(Lead::class); }
    public function qcReport()    { return $this->hasOne(QualityControlReport::class); }

    public function isActive(): bool
    {
        return !in_array($this->status, ['sold', 'delivered']);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'in_stock'        => 'blue',
            'qc_in_progress'  => 'amber',
            'qc_approved'     => 'emerald',
            'payment_pending' => 'purple',
            'delivered'       => 'indigo',
            'sold'            => 'slate',
            default           => 'slate',
        };
    }
}
