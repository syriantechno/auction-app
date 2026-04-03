<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Negotiation extends Model
{
    protected $fillable = [
        'auction_id',
        'buyer_id',
        'seller_id',
        'last_bid_amount',
        'current_offer',
        'status', // pending_seller, counter_offered, accepted, rejected
        'notes',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }
}
