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
        $tables = ['auctions', 'pages', 'posts'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('seo_title')->nullable()->after('id');
                $table->text('seo_description')->nullable()->after('seo_title');
                $table->text('seo_keywords')->nullable()->after('seo_description');
                $table->json('seo_schema')->nullable()->after('seo_keywords');
                $table->integer('seo_score')->default(0)->after('seo_schema');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['auctions', 'pages', 'posts'];
        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['seo_title', 'seo_description', 'seo_keywords', 'seo_schema', 'seo_score']);
            });
        }
    }
};
