<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $auction_id
 * @property int|null $lead_id
 * @property int|null $winning_bidder_id
 * @property float $highest_bid
 * @property float|null $offer_to_lead
 * @property float|null $profit_margin
 * @property string $status
 * @property float|null $counter_offer
 * @property string|null $notes
 * @property-read \App\Models\Auction $auction
 * @property-read \App\Models\Lead|null $lead
 * @property-read \App\Models\User|null $winningBidder
 */
class Negotiation extends Model
{
    protected $fillable = [
        'auction_id',
        'lead_id',
        'winning_bidder_id',
        'highest_bid',
        'offer_to_lead',
        'profit_margin',
        'status',
        'counter_offer',
        'notes',
        'offer_sent_at',
        'responded_at',
    ];

    protected $casts = [
        'highest_bid'    => 'decimal:2',
        'offer_to_lead'  => 'decimal:2',
        'profit_margin'  => 'decimal:2',
        'counter_offer'  => 'decimal:2',
        'offer_sent_at'  => 'datetime',
        'responded_at'   => 'datetime',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function winningBidder()
    {
        return $this->belongsTo(User::class, 'winning_bidder_id');
    }

    // Calculated profit margin
    public function getProfitAttribute(): float
    {
        return (float)$this->highest_bid - (float)$this->offer_to_lead;
    }

    // Status badge color
    public function getStatusColorAttribute(): string
    {
        return match((string) $this->status) {
            'pending'         => 'amber',
            'offer_sent'      => 'blue',
            'accepted'        => 'emerald',
            'rejected'        => 'red',
            'counter_offered' => 'purple',
            default           => 'slate',
        };
    }
}
