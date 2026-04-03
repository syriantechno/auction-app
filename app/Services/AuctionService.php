<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AuctionService
{
    /**
     * تنفيذ عملية مزايدة جديدة مع حماية من القناصين وزيادات تكيفية.
     */
    public function placeBid(Auction $auction, int $userId, float $amount): Bid
    {
        return DB::transaction(function () use ($auction, $userId, $amount) {
            // 1. التحقق من صحة المزايدة
            if ($amount <= $auction->current_price) {
                throw new \Exception('المبلغ يجب أن يكون أكبر من السعر الحالي.');
            }

            // 2. تطبيق حماية القناصين (Sniper Protection)
            // إذا تمت المزايدة في آخر دقيقتين، نمدد المزاد دقيقتين إضافيتين
            $now = Carbon::now();
            $remainingSeconds = $now->diffInSeconds($auction->end_at, false);

            if ($remainingSeconds > 0 && $remainingSeconds <= 120) {
                $auction->end_at = $auction->end_at->addMinutes(2);
            }

            // 3. تحديث سعر المزاد الحالي
            $auction->current_price = $amount;
            $auction->save();

            // 4. تسجيل المزايدة
            $bid = Bid::create([
                'auction_id' => $auction->id,
                'user_id' => $userId,
                'amount' => $amount,
            ]);

            // 5. إرسال حدث المزايدة (Real-time)
            event(new \App\Events\BidPlaced($bid));

            return $bid;
        });
    }

    /**
     * حساب مبلغ الزيادة التكيفي التالي (Adaptive Increment).
     */
    public function getNextMinimumBid(Auction $auction): float
    {
        $currentPrice = $auction->current_price ?: $auction->initial_price;

        if ($currentPrice < 10000) {
            return $currentPrice + 100;
        } elseif ($currentPrice < 50000) {
            return $currentPrice + 500;
        } else {
            return $currentPrice + 1000;
        }
    }
}
