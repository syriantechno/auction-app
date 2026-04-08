<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('position_id')->nullable()->constrained('positions')->nullOnDelete();
            $table->string('job_title');
            $table->text('job_description')->nullable();
            $table->text('requirements')->nullable();
            $table->enum('status', ['open', 'in_progress', 'filled', 'closed'])->default('open');
            $table->integer('vacancies')->default(1);
            $table->decimal('salary_range_from', 15, 2)->nullable();
            $table->decimal('salary_range_to', 15, 2)->nullable();
            $table->date('opening_date');
            $table->date('closing_date')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recruitments');
    }
};
