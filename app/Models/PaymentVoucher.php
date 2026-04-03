<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $voucher_number
 * @property int|null $auction_id
 * @property int $financial_account_id
 * @property string $paid_to_name
 * @property float $amount
 * @property string $payment_method
 * @property string|null $reference
 * @property \Carbon\Carbon $voucher_date
 * @property string $category
 * @property string|null $description
 * @property int $created_by
 */
class PaymentVoucher extends Model
{
    protected $fillable = [
        'voucher_number', 'auction_id', 'financial_account_id',
        'paid_to_name', 'amount', 'payment_method', 'reference',
        'voucher_date', 'category', 'description', 'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'voucher_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::created(function (PaymentVoucher $voucher) {
            // Debit the account
            $voucher->financialAccount()->decrement('current_balance', (float) $voucher->amount);

            // Update auction total_expenses on invoice if linked
            if ($voucher->auction_id) {
                static::recalcAuctionExpenses($voucher->auction_id);
            }
        });
    }

    protected static function recalcAuctionExpenses(int $auctionId): void
    {
        $totalExpenses = AuctionExpense::where('auction_id', $auctionId)->sum('amount')
            + static::where('auction_id', $auctionId)->sum('amount');

        $invoice = Invoice::where('auction_id', $auctionId)->latest()->first();
        if ($invoice) {
            $netProfit = (float)($invoice->gross_profit ?? 0) - $totalExpenses;
            $invoice->update([
                'total_expenses' => $totalExpenses,
                'net_profit'     => $netProfit,
            ]);
        }
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function financialAccount()
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Generate next voucher number: SNS-2026-0001 */
    public static function generateNumber(): string
    {
        $year = now()->year;
        $last = static::whereYear('created_at', $year)->count() + 1;
        return 'SNS-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
