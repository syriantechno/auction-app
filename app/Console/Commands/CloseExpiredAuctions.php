<?php

namespace App\Console\Commands;

use App\Events\AuctionUpdated;
use App\Models\Auction;
use Illuminate\Console\Command;

class CloseExpiredAuctions extends Command
{
    protected $signature   = 'auctions:close-expired';
    protected $description = 'Close active auctions whose end_at has passed and broadcast via WebSocket';

    public function handle(): int
    {
        $expired = Auction::where('status', 'active')
            ->where('end_at', '<=', now())
            ->get();

        if ($expired->isEmpty()) {
            return self::SUCCESS;
        }

        foreach ($expired as $auction) {
            $auction->update(['status' => 'closed']);
            $auction->refresh();

            // Broadcast to all connected clients in real-time
            event(new AuctionUpdated($auction));

            $this->info("Closed auction #{$auction->id} — {$auction->car?->make} {$auction->car?->model}");
        }

        $this->info("Closed {$expired->count()} expired auction(s).");

        return self::SUCCESS;
    }
}
