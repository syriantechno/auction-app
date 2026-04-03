<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Bid increment: minimum amount each bid must raise the current price
            $table->decimal('bid_increment', 10, 2)->default(500)->after('current_price')
                  ->comment('Minimum amount to add per bid (e.g. 500, 1000)');

            // Time extension: if a bid is placed within this many seconds of end_at, extend the auction
            $table->unsignedSmallInteger('time_extension_threshold')->default(30)->after('bid_increment')
                  ->comment('Seconds remaining that trigger auto-extension when a bid is placed');

            // Time extension amount: how many seconds to add when triggered
            $table->unsignedSmallInteger('time_extension_seconds')->default(20)->after('time_extension_threshold')
                  ->comment('Seconds to add to end_at when bid placed in final window');
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn(['bid_increment', 'time_extension_threshold', 'time_extension_seconds']);
        });
    }
};
