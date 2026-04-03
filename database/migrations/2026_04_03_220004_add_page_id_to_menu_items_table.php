<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedBigInteger('page_id')->nullable()->after('parent_id');
            $table->string('target', 20)->default('_self')->after('url'); // _self / _blank
            $table->string('icon', 100)->nullable()->after('target');    // lucide icon name
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['page_id', 'target', 'icon']);
        });
    }
};
