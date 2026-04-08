<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->integer('year');
            $table->integer('month');
            $table->decimal('basic_salary', 15, 2);
            $table->integer('working_days')->default(30);
            $table->integer('actual_working_days')->default(30);
            $table->decimal('working_hours_per_day', 4, 2)->default(8.00);
            $table->decimal('hourly_rate', 15, 2);
            $table->decimal('earned_salary', 15, 2);
            $table->decimal('overtime_hours', 4, 2)->default(0);
            $table->decimal('overtime_multiplier', 3, 2)->default(1.5);
            $table->decimal('overtime_amount', 15, 2)->default(0);
            $table->decimal('weekend_overtime_hours', 4, 2)->default(0);
            $table->decimal('weekend_overtime_multiplier', 3, 2)->default(2.0);
            $table->decimal('weekend_overtime_amount', 15, 2)->default(0);
            $table->decimal('total_overtime_amount', 15, 2)->default(0);
            $table->decimal('deductions', 15, 2)->default(0);
            $table->json('deduction_details')->nullable();
            $table->decimal('bonuses', 15, 2)->default(0);
            $table->json('bonus_details')->nullable();
            $table->integer('absent_days')->default(0);
            $table->decimal('absent_deduction', 15, 2)->default(0);
            $table->integer('late_minutes')->default(0);
            $table->decimal('late_deduction', 15, 2)->default(0);
            $table->integer('half_days')->default(0);
            $table->decimal('half_day_deduction', 15, 2)->default(0);
            $table->integer('unpaid_leave_days')->default(0);
            $table->decimal('unpaid_leave_deduction', 15, 2)->default(0);
            $table->decimal('gross_salary', 15, 2);
            $table->decimal('net_salary', 15, 2);
            $table->enum('status', ['draft', 'calculated', 'approved', 'paid'])->default('draft');
            $table->date('payment_date')->nullable();
            $table->enum('payment_method', ['cash', 'bank_transfer', 'check'])->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['employee_id', 'year', 'month']);
            $table->index(['year', 'month']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
