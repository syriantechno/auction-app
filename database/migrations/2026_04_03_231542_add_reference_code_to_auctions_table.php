<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            // Reference code: unique deal identifier e.g. MB-2026-0042
            $table->string('reference_code', 30)->nullable()->unique()->after('id');
            // Lead ID link — to trace auction back to its lead
            $table->unsignedBigInteger('lead_id')->nullable()->after('car_id');
        });
    }

    public function down(): void
    {
        Schema::table('auctions', function (Blueprint $table) {
            $table->dropColumn(['reference_code', 'lead_id']);
        });
    }
};
