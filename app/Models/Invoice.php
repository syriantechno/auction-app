<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $invoice_number
 * @property int|null $auction_id
 * @property int|null $negotiation_id
 * @property int|null $user_id
 * @property float|null $amount
 * @property string|null $type
 * @property string $status
 * @property float|null $commission_amount
 * @property float|null $tax_amount
 * @property float|null $total_amount
 * @property float|null $lead_asking_price
 * @property float|null $dealer_final_price
 * @property float|null $gross_profit
 * @property float $total_expenses
 * @property float|null $net_profit
 * @property float $amount_received
 * @property float $amount_remaining
 * @property string|null $due_date
 * @property string|null $internal_notes
 * @property \Carbon\Carbon|null $paid_at
 * @property string|null $pdf_path
 */
class Invoice extends Model
{
    protected $fillable = [
        'invoice_number', 'auction_id', 'negotiation_id', 'user_id',
        'amount', 'type', 'status',
        'commission_amount', 'tax_amount', 'total_amount',
        'lead_asking_price', 'dealer_final_price', 'gross_profit',
        'total_expenses', 'net_profit',
        'amount_received', 'amount_remaining',
        'due_date', 'internal_notes', 'paid_at', 'pdf_path',
    ];

    protected $casts = [
        'paid_at'            => 'datetime',
        'lead_asking_price'  => 'decimal:2',
        'dealer_final_price' => 'decimal:2',
        'gross_profit'       => 'decimal:2',
        'total_expenses'     => 'decimal:2',
        'net_profit'         => 'decimal:2',
        'amount_received'    => 'decimal:2',
        'amount_remaining'   => 'decimal:2',
        'total_amount'       => 'decimal:2',
        'commission_amount'  => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function negotiation()
    {
        return $this->belongsTo(Negotiation::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /** Generate sequential invoice number: INV-2026-0001 */
    public static function generateNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->count() + 1;
        return 'INV-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }

    /** Create invoice from a completed negotiation */
    public static function createFromNegotiation(Negotiation $negotiation): self
    {
        $auction      = $negotiation->auction;
        $dealerPrice  = (float) $negotiation->highest_bid;
        $leadPrice    = (float) ($negotiation->offer_to_lead ?? 0);
        $grossProfit  = $dealerPrice - $leadPrice;
        $commission   = round($dealerPrice * 0.02, 2); // 2% commission
        $total        = $dealerPrice + $commission;

        return static::create([
            'invoice_number'      => static::generateNumber(),
            'auction_id'          => $negotiation->auction_id,
            'negotiation_id'      => $negotiation->id,
            'user_id'             => $negotiation->winning_bidder_id,
            'type'                => 'auction_sale',
            'status'              => 'pending',
            'lead_asking_price'   => $leadPrice,
            'dealer_final_price'  => $dealerPrice,
            'gross_profit'        => $grossProfit,
            'total_expenses'      => 0,
            'net_profit'          => $grossProfit,
            'amount'              => $dealerPrice,
            'commission_amount'   => $commission,
            'total_amount'        => $total,
            'amount_received'     => 0,
            'amount_remaining'    => $total,
        ]);
    }
}
