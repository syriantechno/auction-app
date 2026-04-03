<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->index('make');
            $table->index('model');
            $table->index('year');
            $table->index('status');
            $table->index('ownership_type');
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->index('status');
            $table->index('car_id');
            $table->index(['start_at', 'end_at']);
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->index('auction_id');
            $table->index('user_id');
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->index('status');
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropIndex(['make']);
            $table->dropIndex(['model']);
            $table->dropIndex(['year']);
            $table->dropIndex(['status']);
            $table->dropIndex(['ownership_type']);
        });

        Schema::table('auctions', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['car_id']);
            $table->dropIndex(['start_at', 'end_at']);
        });

        Schema::table('bids', function (Blueprint $table) {
            $table->dropIndex(['auction_id']);
            $table->dropIndex(['user_id']);
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['user_id']);
        });
    }
};
