<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\StockController;
use App\Models\Auction;
use App\Models\Negotiation;
use Illuminate\Http\Request;

class NegotiationController extends Controller
{
    /**
     * Start a negotiation for a closed auction.
     * Auto-detects highest bid and linked lead.
     */
    public function start(Auction $auction)
    {
        // Only closed auctions can enter negotiation
        if ($auction->status !== 'closed') {
            return response()->json(['success' => false, 'message' => 'Auction must be closed first.'], 422);
        }

        // Check if negotiation already exists
        $existing = Negotiation::where('auction_id', $auction->id)->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'Negotiation already started.', 'negotiation' => $existing], 200);
        }

        // Get highest bid
        $topBid = $auction->bids()->orderByDesc('amount')->with('user')->first();

        $negotiation = Negotiation::create([
            'auction_id'        => $auction->id,
            'lead_id'           => $auction->lead_id,
            'winning_bidder_id' => $topBid?->user_id,
            'highest_bid'       => $topBid?->amount ?? $auction->current_price,
            'status'            => 'pending',
        ]);

        return response()->json([
            'success'     => true,
            'message'     => 'Negotiation started.',
            'negotiation' => $negotiation,
            'top_bid'     => $topBid?->amount ?? $auction->current_price,
            'bidder_name' => $topBid?->user?->name ?? 'Unknown',
        ]);
    }

    /**
     * Send a price offer to the lead owner.
     */
    public function sendOffer(Request $request, Negotiation $negotiation)
    {
        $request->validate([
            'offer_to_lead' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $offer        = (float) $request->input('offer_to_lead');
        $profitMargin = (float) $negotiation->highest_bid - $offer;

        $negotiation->update([
            'offer_to_lead'  => $offer,
            'profit_margin'  => $profitMargin,
            'notes'          => $request->input('notes'),
            'status'         => 'offer_sent',
            'offer_sent_at'  => now(),
        ]);

        // TODO: Send email/WhatsApp notification to lead owner here in future sprint

        return response()->json([
            'success'       => true,
            'message'       => 'Offer sent to lead owner.',
            'offer'         => $offer,
            'profit_margin' => $profitMargin,
        ]);
    }

    /**
     * Mark offer as accepted — this triggers stock entry.
     */
    public function accept(Negotiation $negotiation)
    {
        $negotiation->update([
            'status'       => 'accepted',
            'responded_at' => now(),
        ]);

        // Update auction status
        $negotiation->auction->update(['status' => 'deal_approved']);

        // STEP 6: Auto-create stock entry
        StockController::createFromNegotiation($negotiation);

        return response()->json([
            'success' => true,
            'message' => 'Deal approved. Vehicle is now in stock.',
        ]);
    }

    /**
     * Mark offer as rejected.
     */
    public function reject(Negotiation $negotiation)
    {
        $negotiation->update([
            'status'       => 'rejected',
            'responded_at' => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Offer rejected by lead owner.']);
    }

    /**
     * Record a counter-offer from the lead owner.
     */
    public function counterOffer(Request $request, Negotiation $negotiation)
    {
        $request->validate(['counter_offer' => 'required|numeric|min:0']);

        $negotiation->update([
            'counter_offer' => $request->input('counter_offer'),
            'status'        => 'counter_offered',
            'responded_at'  => now(),
        ]);

        return response()->json([
            'success'      => true,
            'message'      => 'Counter-offer recorded.',
            'counter_offer'=> $request->input('counter_offer'),
        ]);
    }

    /**
     * Get full negotiation status for an auction (AJAX).
     */
    public function show(Auction $auction)
    {
        $negotiation = Negotiation::where('auction_id', $auction->id)
            ->with(['lead', 'winningBidder'])
            ->first();

        $topBid = $auction->bids()->orderByDesc('amount')->with('user')->first();

        return response()->json([
            'negotiation' => $negotiation,
            'top_bid'     => $topBid?->amount ?? $auction->current_price,
            'bidder_name' => $topBid?->user?->name ?? 'N/A',
            'ref_code'    => $auction->reference_code,
        ]);
    }
}
