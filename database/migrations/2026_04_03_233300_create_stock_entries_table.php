<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('car_id');
            $table->unsignedBigInteger('auction_id');
            $table->unsignedBigInteger('negotiation_id')->nullable();
            $table->unsignedBigInteger('lead_id')->nullable();           // Seller (car owner)
            $table->string('reference_code', 30)->nullable();            // Copied from auction
            $table->decimal('purchase_price', 12, 2)->default(0);        // What we agreed to pay the lead
            $table->decimal('dealer_bid', 12, 2)->default(0);            // What the dealer bid
            $table->decimal('profit_margin', 12, 2)->default(0);         // = dealer_bid - purchase_price
            $table->enum('status', [
                'in_stock',         // Just entered stock — waiting for QC
                'qc_in_progress',   // Quality control underway
                'qc_approved',      // QC passed — ready to handover
                'payment_pending',  // Awaiting dealer payment
                'delivered',        // Car delivered to dealer
                'sold',             // Ownership transferred — deal complete
            ])->default('in_stock');
            $table->date('entry_date')->nullable();
            $table->date('qc_completed_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->date('ownership_transfer_date')->nullable();
            $table->decimal('amount_received', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->onDelete('cascade');
            $table->foreign('auction_id')->references('id')->on('auctions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_entries');
    }
};
