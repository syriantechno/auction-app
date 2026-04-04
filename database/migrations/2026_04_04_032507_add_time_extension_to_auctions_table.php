<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->boolean('anti_snipe_enabled')->default(true)->after('status');
            $table->unsignedSmallInteger('time_extension_threshold')->default(30)->after('anti_snipe_enabled'); // seconds remaining to trigger
            $table->unsignedSmallInteger('time_extension_seconds')->default(20)->after('time_extension_threshold'); // seconds to add
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn(['anti_snipe_enabled', 'time_extension_threshold', 'time_extension_seconds']);
        });
    }
};
