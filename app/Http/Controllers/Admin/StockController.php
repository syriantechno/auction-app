<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DealerHandoverMail;
use App\Models\InspectionReport;
use App\Models\Negotiation;
use App\Models\QualityControlReport;
use App\Models\StockEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class StockController extends Controller
{
    // ─────────────────────────────────────────────
    // STEP 6: Stock Index
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = StockEntry::with(['car', 'auction', 'lead', 'qcReport'])->latest();

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('reference_code', 'like', "%{$search}%")
                  ->orWhereHas('car', fn($q) => $q->where('make', 'like', "%{$search}%")
                                                   ->orWhere('model', 'like', "%{$search}%"));
        }

        $entries = $query->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return view('admin.stock._table', compact('entries'))->render();
        }

        return view('admin.stock.index', compact('entries'));
    }

    // ─────────────────────────────────────────────
    // STEP 6: Create stock entry (called from NegotiationController@accept)
    // ─────────────────────────────────────────────
    public static function createFromNegotiation(Negotiation $negotiation): StockEntry
    {
        $auction = $negotiation->auction;

        return StockEntry::create([
            'car_id'         => $auction->car_id,
            'auction_id'     => $auction->id,
            'negotiation_id' => $negotiation->id,
            'lead_id'        => $negotiation->lead_id,
            'reference_code' => $auction->reference_code,
            'purchase_price' => $negotiation->offer_to_lead ?? 0,
            'dealer_bid'     => $negotiation->highest_bid,
            'profit_margin'  => $negotiation->profit_margin ?? 0,
            'status'         => 'in_stock',
            'entry_date'     => now()->toDateString(),
        ]);
    }

    // ─────────────────────────────────────────────
    // STEP 7: Start Quality Control
    // ─────────────────────────────────────────────
    public function startQC(StockEntry $stockEntry)
    {
        if ($stockEntry->qcReport) {
            return response()->json(['success' => false, 'message' => 'QC already started.']);
        }

        // Find the original inspection report for this car
        $inspection = InspectionReport::where('car_id', $stockEntry->car_id)->latest()->first();

        $qc = QualityControlReport::create([
            'stock_entry_id'      => $stockEntry->id,
            'inspection_report_id'=> $inspection?->id,
            'qc_by'               => Auth::id(),
            'status'              => 'in_progress',
        ]);

        $stockEntry->update(['status' => 'qc_in_progress']);

        return response()->json([
            'success' => true,
            'message' => 'QC started.',
            'qc_id'   => $qc->id,
        ]);
    }

    // ─────────────────────────────────────────────
    // STEP 7: Save QC Report
    // ─────────────────────────────────────────────
    public function saveQC(Request $request, StockEntry $stockEntry)
    {
        $request->validate([
            'paint_verified'         => 'boolean',
            'engine_verified'        => 'boolean',
            'transmission_verified'  => 'boolean',
            'interior_verified'      => 'boolean',
            'tires_verified'         => 'boolean',
            'body_verified'          => 'boolean',
            'documents_verified'     => 'boolean',
            'keys_count_verified'    => 'boolean',
        ]);

        $qc = $stockEntry->qcReport;
        if (!$qc) {
            return response()->json(['success' => false, 'message' => 'QC not started.'], 422);
        }

        $qc->update($request->only([
            'paint_verified',        'paint_notes',
            'engine_verified',       'engine_notes',
            'transmission_verified', 'transmission_notes',
            'interior_verified',     'interior_notes',
            'tires_verified',        'tires_notes',
            'body_verified',         'body_notes',
            'documents_verified',    'documents_notes',
            'keys_count_verified',   'additional_notes',
        ]));

        return response()->json(['success' => true, 'is_fully_verified' => $qc->fresh()->isFullyVerified()]);
    }

    // ─────────────────────────────────────────────
    // STEP 7: Approve QC → STEP 8: Send Dealer Email
    // ─────────────────────────────────────────────
    public function approveQC(StockEntry $stockEntry)
    {
        $qc = $stockEntry->qcReport;
        if (!$qc) {
            return response()->json(['success' => false, 'message' => 'No QC report found.'], 422);
        }

        $qc->update(['status' => 'approved', 'approved_at' => now()]);
        $stockEntry->update([
            'status'            => 'qc_approved',
            'qc_completed_date' => now()->toDateString(),
        ]);

        // Update car status
        $stockEntry->car->update(['status' => 'qc_approved']);

        // STEP 8: Send dealer email if we have their email
        $dealerEmail = $stockEntry->auction->bids()
            ->orderByDesc('amount')
            ->with('user')
            ->first()
            ?->user?->email;

        if ($dealerEmail) {
            try {
                Mail::to($dealerEmail)->send(new DealerHandoverMail($stockEntry));
                $emailSent = true;
            } catch (\Exception $e) {
                $emailSent = false;
            }
        }

        return response()->json([
            'success'    => true,
            'message'    => 'QC approved.' . ($emailSent ?? false ? ' Dealer notified via email.' : ' Email not sent (no dealer email).'),
            'email_sent' => $emailSent ?? false,
        ]);
    }

    // ─────────────────────────────────────────────
    // STEP 9: Complete Deal (Stock Exit)
    // ─────────────────────────────────────────────
    public function completeDeal(Request $request, StockEntry $stockEntry)
    {
        $request->validate([
            'amount_received'         => 'required|numeric|min:0',
            'delivery_date'           => 'nullable|date',
            'ownership_transfer_date' => 'nullable|date',
            'notes'                   => 'nullable|string',
        ]);

        $stockEntry->update([
            'status'                  => 'sold',
            'amount_received'         => $request->amount_received,
            'delivery_date'           => $request->delivery_date ?? now()->toDateString(),
            'ownership_transfer_date' => $request->ownership_transfer_date,
            'notes'                   => $request->notes,
        ]);

        // Update car and auction status to sold/completed
        $stockEntry->car->update(['status' => 'sold']);
        $stockEntry->auction->update(['status' => 'completed']);

        return response()->json([
            'success' => true,
            'message' => 'Deal completed. Vehicle ' . $stockEntry->reference_code . ' exited stock.',
        ]);
    }

    // ─────────────────────────────────────────────
    // AJAX: Get stock entry details
    // ─────────────────────────────────────────────
    public function show(StockEntry $stockEntry)
    {
        $stockEntry->load(['car', 'auction', 'lead', 'qcReport.inspectionReport', 'negotiation']);

        return response()->json([
            'entry'      => $stockEntry,
            'car'        => $stockEntry->car,
            'qc'         => $stockEntry->qcReport,
            'inspection' => $stockEntry->qcReport?->inspectionReport,
            'ref_code'   => $stockEntry->reference_code,
        ]);
    }
}
