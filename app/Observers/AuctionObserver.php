<?php

namespace App\Observers;

use App\Models\Auction;

class AuctionObserver
{
    /**
     * Handle the Auction "created" event.
     */
    public function created(Auction $auction): void
    {
        if (empty($auction->seo_title)) {
            app(\App\Services\SeoAutonomousAgent::class)->generateAiMeta($auction, 'car_auction');
        }
    }

    /**
     * Handle the Auction "updated" event.
     */
    public function updated(Auction $auction): void
    {
        // Regenerate if car detail or status changed to active and SEO is still empty
        if ($auction->isDirty(['status']) && $auction->status === 'active' && empty($auction->seo_title)) {
            app(\App\Services\SeoAutonomousAgent::class)->generateAiMeta($auction, 'car_auction');
        }
    }

    /**
     * Handle the Auction "deleted" event.
     */
    public function deleted(Auction $auction): void
    {
        //
    }

    /**
     * Handle the Auction "restored" event.
     */
    public function restored(Auction $auction): void
    {
        //
    }

    /**
     * Handle the Auction "force deleted" event.
     */
    public function forceDeleted(Auction $auction): void
    {
        //
    }
}
