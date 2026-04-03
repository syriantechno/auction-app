<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('voucher_number')->unique();          // SNS-2026-0001
            $table->foreignId('auction_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('financial_account_id')->constrained()->restrictOnDelete();
            $table->string('paid_to_name');                     // Who received payment
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');                   // cash | transfer | cheque
            $table->string('reference')->nullable();
            $table->date('voucher_date');
            $table->string('category');                         // lead_payment | commission | maintenance | transport | other
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_vouchers');
    }
};
