<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('evaluation_date');
            $table->enum('evaluation_period', ['monthly', 'quarterly', 'annual']);
            $table->integer('total_score');
            $table->enum('result', ['excellent', 'good', 'average', 'needs_improvement']);
            $table->text('notes')->nullable();
            $table->foreignId('evaluated_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'evaluation_date']);
        });

        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('weight')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('employee_evaluation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_evaluation_id')->constrained('employee_evaluations')->cascadeOnDelete();
            $table->foreignId('evaluation_criterion_id')->constrained('evaluation_criteria')->cascadeOnDelete();
            $table->integer('score');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_evaluation_items');
        Schema::dropIfExists('evaluation_criteria');
        Schema::dropIfExists('employee_evaluations');
    }
};
