<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'type',
        'status',
        'commission_amount',
        'tax_amount',
        'total_amount',
        'paid_at',
        'pdf_path',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
