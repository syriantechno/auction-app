<?php

namespace App\Events;

use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BidPlaced implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid->load('user', 'auction');
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('auction.' . $this->bid->auction_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'amount' => $this->bid->amount,
            'user_name' => $this->bid->user->name,
            'current_price' => $this->bid->amount,
            'end_at' => $this->bid->auction->end_at->toIso8601String(),
        ];
    }
}
