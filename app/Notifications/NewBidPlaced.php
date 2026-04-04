<?php

namespace App\Notifications;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewBidPlaced extends Notification implements ShouldBroadcast
{
    private array  $bidData;
    private int    $auctionId;
    private int    $bidId;
    private float  $amount;
    private string $bidderName;

    public function __construct(Bid $bid, Auction $auction)
    {
        $this->bidId      = $bid->id ?? 0;
        $this->amount     = (float) ($bid->amount ?? 0);
        $this->bidderName = $bid->user?->name ?? 'Anonymous';
        $this->auctionId  = $auction->id ?? 0;
        $this->bidData    = [
            'make'  => optional($auction->car)->make ?? '',
            'model' => optional($auction->car)->model ?? '',
        ];
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        try {
            $url = route('admin.auctions.index');
        } catch (\Throwable) {
            $url = url('/admin/auctions');
        }

        return [
            'type'       => 'new_bid',
            'title'      => 'New Bid Placed',
            'message'    => sprintf(
                '%s bid $%s on %s %s.',
                $this->bidderName,
                number_format($this->amount, 0),
                $this->bidData['make'],
                $this->bidData['model']
            ),
            'url'        => $url,
            'icon'       => 'gavel',
            'color'      => 'emerald',
            'auction_id' => $this->auctionId,
            'bid_id'     => $this->bidId,
            'amount'     => $this->amount,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(): array
    {
        return [];
    }
}
