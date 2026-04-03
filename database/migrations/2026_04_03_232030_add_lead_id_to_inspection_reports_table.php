<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inspection_reports', function (Blueprint $table) {
            // Link inspection back to the lead that originated it
            $table->unsignedBigInteger('lead_id')->nullable()->after('car_id');
            // Scheduled date/time (came from lead confirm step)
            $table->date('scheduled_date')->nullable()->after('lead_id');
            $table->time('scheduled_time')->nullable()->after('scheduled_date');
            // Location for the inspection
            $table->string('location')->nullable()->after('scheduled_time');
            // Inspector (user) assigned
            $table->unsignedBigInteger('inspector_id')->nullable()->after('expert_id');
            // Status tracking
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'approved'])->default('scheduled')->after('location');
        });
    }

    public function down(): void
    {
        Schema::table('inspection_reports', function (Blueprint $table) {
            $table->dropColumn(['lead_id', 'scheduled_date', 'scheduled_time', 'location', 'inspector_id', 'status']);
        });
    }
};
