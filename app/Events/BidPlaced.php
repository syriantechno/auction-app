<?php

namespace App\Events;

use App\Models\Bid;
use App\Models\Auction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Bid $bid, public Auction $auction) {}

    public function broadcastOn(): array
    {
        return [new Channel("auction.{$this->auction->id}")];
    }

    public function broadcastAs(): string
    {
        return 'bid.placed';
    }

    public function broadcastWith(): array
    {
        return [
            'auction_id'        => $this->auction->id,
            'current_price'     => (float) $this->auction->current_price,
            'current_price_fmt' => '$' . number_format($this->auction->current_price),
            'end_at'            => $this->auction->end_at?->toISOString(),
            'end_at_timestamp'  => $this->auction->end_at?->timestamp,
            'bids_count'        => $this->auction->bids()->count(),
            'bidder_name'       => $this->bid->user->name ?? 'Bidder',
            'bidder_initial'    => strtoupper(substr($this->bid->user->name ?? 'B', 0, 1)),
            'bid_amount'        => '$' . number_format($this->bid->amount),
            'bid_time'          => $this->bid->created_at->diffForHumans(),
        ];
    }
}
