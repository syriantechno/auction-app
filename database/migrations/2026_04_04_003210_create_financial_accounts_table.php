<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');                    // Cash Box / Bank Account / etc.
            $table->string('type');                    // cash | bank | other
            $table->string('bank_name')->nullable();   // Bank name if type=bank
            $table->string('account_number')->nullable();
            $table->decimal('opening_balance', 15, 2)->default(0);
            $table->decimal('current_balance', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Seed default accounts
        DB::table('financial_accounts')->insert([
            ['name' => 'Cash Box', 'type' => 'cash', 'opening_balance' => 0, 'current_balance' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Main Bank Account', 'type' => 'bank', 'opening_balance' => 0, 'current_balance' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_accounts');
    }
};
