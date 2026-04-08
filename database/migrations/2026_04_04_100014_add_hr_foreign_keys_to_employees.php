<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add default_shift_id to employees
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('default_shift_id')->nullable()->after('department_id')->constrained('shifts')->nullOnDelete();
        });

        // Add salary_structure_id to employees
        Schema::table('employees', function (Blueprint $table) {
            $table->foreignId('salary_structure_id')->nullable()->after('default_shift_id')->constrained('salary_structures')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['default_shift_id']);
            $table->dropForeign(['salary_structure_id']);
            $table->dropColumn(['default_shift_id', 'salary_structure_id']);
        });
    }
};
