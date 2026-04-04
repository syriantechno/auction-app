<?php

namespace App\Events;

use App\Models\Auction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AuctionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Auction $auction) {}

    public function broadcastOn(): array
    {
        return [new Channel("auction.{$this->auction->id}")];
    }

    public function broadcastAs(): string
    {
        return 'auction.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id'                    => $this->auction->id,
            'status'                => $this->auction->status,
            'current_price'         => (float) $this->auction->current_price,
            'current_price_fmt'     => '$' . number_format($this->auction->current_price),
            'end_at'                => $this->auction->end_at?->toISOString(),
            'end_at_timestamp'      => $this->auction->end_at?->timestamp,
            'bids_count'            => $this->auction->bids()->count(),
        ];
    }
}
