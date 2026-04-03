<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $type  cash|bank|other
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property float $opening_balance
 * @property float $current_balance
 * @property bool $is_active
 * @property string|null $notes
 */
class FinancialAccount extends Model
{
    protected $fillable = [
        'name', 'type', 'bank_name', 'account_number',
        'opening_balance', 'current_balance', 'is_active', 'notes',
    ];

    protected $casts = [
        'opening_balance'  => 'decimal:2',
        'current_balance'  => 'decimal:2',
        'is_active'        => 'boolean',
    ];

    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    public function paymentVouchers()
    {
        return $this->hasMany(PaymentVoucher::class);
    }

    /** Add money to account (receipt) */
    public function credit(float $amount): void
    {
        $this->increment('current_balance', $amount);
    }

    /** Remove money from account (voucher) */
    public function debit(float $amount): void
    {
        $this->decrement('current_balance', $amount);
    }
}
