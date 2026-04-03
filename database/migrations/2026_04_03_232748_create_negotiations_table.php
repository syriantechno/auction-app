<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('negotiations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('lead_id')->nullable();        // The seller (car owner)
            $table->unsignedBigInteger('winning_bidder_id')->nullable(); // Highest bidder / dealer
            $table->decimal('highest_bid', 12, 2)->default(0);         // What dealer paid
            $table->decimal('offer_to_lead', 12, 2)->nullable();       // What we offer to car owner
            $table->decimal('profit_margin', 12, 2)->nullable();       // Calculated difference
            $table->enum('status', [
                'pending',          // Just created, waiting for admin to set offer
                'offer_sent',       // Offer sent to lead owner
                'accepted',         // Lead owner accepted
                'rejected',         // Lead owner rejected
                'counter_offered',  // Lead owner came back with another price
            ])->default('pending');
            $table->decimal('counter_offer', 12, 2)->nullable();       // If lead counter-offers
            $table->text('notes')->nullable();
            $table->timestamp('offer_sent_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negotiations');
    }
};
