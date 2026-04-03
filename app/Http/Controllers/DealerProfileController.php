<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Bid;
use App\Models\Auction;
use App\Models\Negotiation;
use Illuminate\Http\Request;

class DealerProfileController extends Controller
{
    public function show(Request $request, User $dealer)
    {
        // Tab 1: Active / Participating auctions (dealer has bids, auction not yet closed)
        $participating = Auction::whereHas('bids', fn($q) => $q->where('user_id', $dealer->id))
            ->whereIn('status', ['active', 'scheduled', 'pending', 'coming_soon'])
            ->with([
                'car',
                'bids' => fn($q) => $q->where('user_id', $dealer->id)->orderByDesc('amount'),
            ])
            ->withMax(['bids as top_bid' => fn($q) => $q], 'amount')
            ->latest()
            ->get()
            ->map(function ($auction) use ($dealer) {
                $userBid             = $auction->bids->first();
                $globalTop           = Bid::where('auction_id', $auction->id)->max('amount');
                $auction->user_bid   = $userBid?->amount ?? 0;
                $auction->top_bid    = $globalTop ?? 0;
                $auction->is_leading = $userBid && (float)$userBid->amount >= (float)$globalTop;
                return $auction;
            });

        // Tab 2: Won auctions (negotiation accepted with this dealer)
        $won = Auction::whereHas('negotiation', fn($q) =>
                $q->whereIn('status', ['accepted', 'closed'])
                  ->where('winning_bidder_id', $dealer->id)
            )
            ->with(['car', 'negotiation', 'invoices'])
            ->latest()
            ->get();

        // Stats
        $totalBids    = Bid::where('user_id', $dealer->id)->count();
        $avgBid       = Bid::where('user_id', $dealer->id)->avg('amount') ?? 0;
        $totalSpent   = Negotiation::where('winning_bidder_id', $dealer->id)
                            ->whereIn('status', ['accepted', 'closed'])
                            ->sum('highest_bid');
        $highestBid   = Bid::where('user_id', $dealer->id)->max('amount') ?? 0;
        $winRate      = $participating->count() + $won->count() > 0
                            ? round($won->count() / ($participating->count() + $won->count()) * 100)
                            : 0;

        return view('dealer.profile', compact(
            'dealer',
            'participating',
            'won',
            'totalBids',
            'avgBid',
            'totalSpent',
            'highestBid',
            'winRate'
        ));
    }
}
