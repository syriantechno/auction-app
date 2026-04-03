<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $auction_id
 * @property string $category
 * @property string $description
 * @property float $amount
 * @property \Carbon\Carbon $expense_date
 * @property string|null $receipt_ref
 * @property int|null $payment_voucher_id
 * @property int $created_by
 */
class AuctionExpense extends Model
{
    protected $fillable = [
        'auction_id', 'category', 'description', 'amount',
        'expense_date', 'receipt_ref', 'payment_voucher_id', 'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'expense_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::saved(function (AuctionExpense $expense) {
            static::recalcInvoice($expense->auction_id);
        });
        static::deleted(function (AuctionExpense $expense) {
            static::recalcInvoice($expense->auction_id);
        });
    }

    protected static function recalcInvoice(int $auctionId): void
    {
        $totalExpenses = static::where('auction_id', $auctionId)->sum('amount')
            + PaymentVoucher::where('auction_id', $auctionId)->sum('amount');

        $invoice = Invoice::where('auction_id', $auctionId)->latest()->first();
        if ($invoice) {
            $invoice->update([
                'total_expenses' => $totalExpenses,
                'net_profit'     => (float)($invoice->gross_profit ?? 0) - $totalExpenses,
            ]);
        }
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function paymentVoucher()
    {
        return $this->belongsTo(PaymentVoucher::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Category labels */
    public static function categories(): array
    {
        return [
            'inspection'   => '🔍 Inspection',
            'transport'    => '🚚 Transport',
            'repair'       => '🔧 Repair',
            'cleaning'     => '🧹 Cleaning',
            'photography'  => '📸 Photography',
            'marketing'    => '📢 Marketing',
            'commission'   => '💼 Commission',
            'other'        => '📌 Other',
        ];
    }
}
