<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->string('receipt_number')->unique();         // QBZ-2026-0001
            $table->foreignId('auction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('financial_account_id')->constrained()->restrictOnDelete();
            $table->foreignId('received_from_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('received_from_name')->nullable();   // If not a registered user
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');                   // cash | transfer | cheque | pos
            $table->string('reference')->nullable();            // Cheque no / transfer ref
            $table->date('receipt_date');
            $table->string('purpose');                          // auction_payment | deposit | other
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
