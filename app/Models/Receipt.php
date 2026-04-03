<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $receipt_number
 * @property int|null $auction_id
 * @property int|null $invoice_id
 * @property int $financial_account_id
 * @property int|null $received_from_user_id
 * @property string|null $received_from_name
 * @property float $amount
 * @property string $payment_method
 * @property string|null $reference
 * @property \Carbon\Carbon $receipt_date
 * @property string $purpose
 * @property string|null $notes
 * @property int $created_by
 */
class Receipt extends Model
{
    protected $fillable = [
        'receipt_number', 'auction_id', 'invoice_id', 'financial_account_id',
        'received_from_user_id', 'received_from_name', 'amount',
        'payment_method', 'reference', 'receipt_date', 'purpose', 'notes', 'created_by',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'receipt_date' => 'date',
    ];

    /** Auto-update account balance & invoice received amount when created */
    protected static function booted(): void
    {
        static::created(function (Receipt $receipt) {
            // Update account balance
            $receipt->financialAccount()->increment('current_balance', (float) $receipt->amount);

            // Update invoice amount_received & amount_remaining
            if ($receipt->invoice_id) {
                $invoice = Invoice::find($receipt->invoice_id);
                if ($invoice) {
                    $received = Receipt::where('invoice_id', $invoice->id)->sum('amount');
                    $invoice->update([
                        'amount_received'  => $received,
                        'amount_remaining' => max(0, (float) $invoice->total_amount - $received),
                        'status'           => $received >= (float) $invoice->total_amount ? 'paid' : 'partial',
                    ]);
                }
            }
        });
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function financialAccount()
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    public function receivedFromUser()
    {
        return $this->belongsTo(User::class, 'received_from_user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Generate next receipt number: QBZ-2026-0001 */
    public static function generateNumber(): string
    {
        $year  = now()->year;
        $last  = static::whereYear('created_at', $year)->count() + 1;
        return 'QBZ-' . $year . '-' . str_pad($last, 4, '0', STR_PAD_LEFT);
    }
}
