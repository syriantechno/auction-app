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
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->onDelete('cascade');
            $table->foreignId('expert_id')->nullable()->constrained('users');
            
            // الهيكل الخارجي (Exterior)
            $table->integer('paint_score')->default(10);
            $table->text('body_notes')->nullable();
            
            // المحرك (Engine)
            $table->integer('engine_score')->default(10);
            $table->text('engine_notes')->nullable();
            
            // ناقل الحركة (Transmission)
            $table->integer('transmission_score')->default(10);
            $table->text('transmission_notes')->nullable();
            
            // المقصورة (Interior)
            $table->integer('interior_score')->default(10);
            $table->text('interior_notes')->nullable();
            
            // إطارات وفرامل (Tires & Brakes)
            $table->integer('tires_score')->default(10);
            $table->text('tires_notes')->nullable();
            
            // التقرير النهائي
            $table->integer('overall_score')->default(10);
            $table->json('detailed_checklists')->nullable(); // لضمان المرونة في إضافة نقاط فحص جديدة
            $table->text('expert_summary')->nullable();
            
            $table->timestamps();

            // فهارس للسرعة
            $table->index('car_id');
            $table->index('overall_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_reports');
    }
};
