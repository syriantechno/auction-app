<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auctions', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->decimal('initial_price', 15, 2);
            $table->decimal('current_price', 15, 2)->nullable();
            $table->string('deposit_type')->default('fixed'); // fixed, percentage, none
            $table->decimal('deposit_amount', 15, 2)->default(0);
            $table->string('status')->default('coming_soon'); // coming_soon, active, pending_approval, sold, failed
            $table->integer('duration_minutes')->default(20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auctions');
    }
};
