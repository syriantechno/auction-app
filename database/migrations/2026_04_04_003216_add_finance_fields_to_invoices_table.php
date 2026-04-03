<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            // Auto-linked reference fields
            $table->string('invoice_number')->unique()->nullable()->after('id');
            $table->foreignId('negotiation_id')->nullable()->after('auction_id')->constrained()->nullOnDelete();
            $table->decimal('lead_asking_price', 15, 2)->nullable()->after('total_amount');   // كم طلب الليد
            $table->decimal('dealer_final_price', 15, 2)->nullable()->after('lead_asking_price'); // كم اشترى الديلر
            $table->decimal('gross_profit', 15, 2)->nullable()->after('dealer_final_price');      // الربح الإجمالي
            $table->decimal('total_expenses', 15, 2)->default(0)->after('gross_profit');          // مجموع المصاريف
            $table->decimal('net_profit', 15, 2)->nullable()->after('total_expenses');            // صافي الربح
            $table->decimal('amount_received', 15, 2)->default(0)->after('net_profit');           // المبلغ المحصّل
            $table->decimal('amount_remaining', 15, 2)->default(0)->after('amount_received');     // المبلغ المتبقي
            $table->string('due_date')->nullable()->after('amount_remaining');
            $table->text('internal_notes')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn([
                'invoice_number', 'negotiation_id', 'lead_asking_price',
                'dealer_final_price', 'gross_profit', 'total_expenses',
                'net_profit', 'amount_received', 'amount_remaining',
                'due_date', 'internal_notes',
            ]);
        });
    }
};
