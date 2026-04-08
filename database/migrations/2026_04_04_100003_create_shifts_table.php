<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('working_hours', 4, 2)->default(8.00);
            $table->time('break_start')->nullable();
            $table->time('break_end')->nullable();
            $table->decimal('break_hours', 4, 2)->default(1.00);
            $table->integer('grace_period')->default(0);
            $table->decimal('overtime_start_after', 4, 2)->default(0.00);
            $table->enum('applicable_to', ['all', 'department', 'employee'])->default('all');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->json('work_days')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
