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
        Schema::create('cars', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->integer('year');
            $table->string('vin')->nullable()->unique();
            $table->string('ownership_type')->default('owned'); // owned, brokerage
            $table->string('status')->default('available'); // available, inspection, sold, archived
            $table->decimal('base_price', 15, 2)->nullable();
            $table->json('inspection_data')->nullable();
            $table->string('inspection_report_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
