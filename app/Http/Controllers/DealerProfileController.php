<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bid;
use Illuminate\Http\Request;

class DealerProfileController extends Controller
{
    /**
     * Show the dealer's public profile with all auctions they've bid on.
     */
    public function show(Request $request, User $dealer)
    {
        // All auctions the dealer has bid on (unique auctions, latest bid per auction)
        $auctionIds = Bid::where('user_id', $dealer->id)
            ->distinct()
            ->pluck('auction_id');

        $auctions = \App\Models\Auction::whereIn('id', $auctionIds)
            ->with([
                'car',
                'bids' => fn ($q) => $q->where('user_id', $dealer->id)->orderByDesc('amount'),
            ])
            ->withCount('bids')
            ->withMax(['bids as dealer_highest_bid' => fn ($q) => $q->where('user_id', $dealer->id)], 'amount')
            ->orderByDesc('created_at')
            ->paginate(12);

        // Stats
        $totalBids      = Bid::where('user_id', $dealer->id)->count();
        $avgBid         = Bid::where('user_id', $dealer->id)->avg('amount') ?? 0;
        $auctionsWon    = \App\Models\Negotiation::where('winning_bidder_id', $dealer->id)
                            ->whereIn('status', ['accepted'])->count();
        $totalSpent     = \App\Models\Negotiation::where('winning_bidder_id', $dealer->id)
                            ->whereIn('status', ['accepted'])
                            ->sum('highest_bid');
        $activeBids     = Bid::where('user_id', $dealer->id)
                            ->whereHas('auction', fn ($q) => $q->where('status', 'active'))
                            ->distinct('auction_id')->count('auction_id');

        return view('dealer.profile', compact(
            'dealer',
            'auctions',
            'totalBids',
            'avgBid',
            'auctionsWon',
            'totalSpent',
            'activeBids'
        ));
    }
}
