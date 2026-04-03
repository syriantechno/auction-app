<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop any JSON CHECK constraints MariaDB auto-creates on json columns
        // Then change the column to longtext (unrestricted HTML storage)
        DB::statement('ALTER TABLE `pages` MODIFY `content` LONGTEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `pages` MODIFY `content` JSON NULL');
    }
};
