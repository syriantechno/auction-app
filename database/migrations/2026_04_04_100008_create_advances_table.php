<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advances', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->date('request_date');
            $table->date('approved_date')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'paid'])->default('pending');
            $table->text('reason')->nullable();
            $table->enum('repayment_method', ['salary_deduction', 'cash'])->default('salary_deduction');
            $table->integer('installments_count')->default(1);
            $table->decimal('installment_amount', 15, 2)->nullable();
            $table->decimal('remaining_amount', 15, 2);
            $table->date('last_deduction_date')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index(['employee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advances');
    }
};
