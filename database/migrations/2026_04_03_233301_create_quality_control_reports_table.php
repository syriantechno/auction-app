<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_control_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_entry_id');
            $table->unsignedBigInteger('inspection_report_id')->nullable(); // Original inspection ref
            $table->unsignedBigInteger('qc_by')->nullable();                // User who did the QC
            $table->enum('status', ['pending', 'in_progress', 'approved', 'failed'])->default('pending');
            // QC check results — mirror of inspection sections
            $table->boolean('paint_verified')->default(false);
            $table->text('paint_notes')->nullable();
            $table->boolean('engine_verified')->default(false);
            $table->text('engine_notes')->nullable();
            $table->boolean('transmission_verified')->default(false);
            $table->text('transmission_notes')->nullable();
            $table->boolean('interior_verified')->default(false);
            $table->text('interior_notes')->nullable();
            $table->boolean('tires_verified')->default(false);
            $table->text('tires_notes')->nullable();
            $table->boolean('body_verified')->default(false);
            $table->text('body_notes')->nullable();
            // Additional QC fields
            $table->boolean('documents_verified')->default(false);
            $table->text('documents_notes')->nullable();
            $table->boolean('keys_count_verified')->default(false);
            $table->text('additional_notes')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('stock_entry_id')->references('id')->on('stock_entries')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_control_reports');
    }
};
