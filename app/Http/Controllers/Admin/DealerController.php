<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Auction;
use Illuminate\Http\Request;

class DealerController extends Controller
{
    /**
     * List all dealers (users with bids)
     */
    public function index()
    {
        $dealers = User::whereHas('bids')
            ->withCount(['bids'])
            ->with(['bids' => fn($q) => $q->latest()->limit(1)])
            ->orderByDesc('bids_count')
            ->paginate(20);

        return view('admin.dealers.index', compact('dealers'));
    }

    /**
     * Dealer profile: auctions participating + auctions won
     */
    public function show(User $user)
    {
        // Auctions the dealer is PARTICIPATING IN (active, has bids)
        $participating = Auction::whereHas('bids', fn($q) => $q->where('user_id', $user->id))
            ->whereIn('status', ['active', 'scheduled', 'pending'])
            ->with(['car', 'bids' => fn($q) => $q->where('user_id', $user->id)->latest()])
            ->latest()
            ->get()
            ->map(function ($auction) use ($user) {
                $userBid = $auction->bids->first();
                $topBid  = $auction->bids()->max('amount');
                $auction->user_bid_amount  = $userBid?->amount ?? 0;
                $auction->top_bid_amount   = $topBid ?? 0;
                $auction->is_leading       = $userBid && $userBid->amount >= $topBid;
                return $auction;
            });

        // Auctions the dealer WON (negotiation closed with this dealer)
        $won = Auction::whereHas('negotiation', fn($q) =>
                $q->where('status', 'closed')->where('winning_user_id', $user->id)
            )
            ->with(['car', 'negotiation'])
            ->latest()
            ->get();

        // Stats
        $stats = [
            'total_bids'        => $user->bids()->count(),
            'auctions_won'      => $won->count(),
            'active_auctions'   => $participating->count(),
            'total_spent'       => $won->sum(fn($a) => $a->negotiation?->final_price ?? 0),
            'highest_bid'       => $user->bids()->max('amount') ?? 0,
            'member_since'      => $user->created_at,
        ];

        return view('admin.dealers.show', compact('user', 'participating', 'won', 'stats'));
    }
}
