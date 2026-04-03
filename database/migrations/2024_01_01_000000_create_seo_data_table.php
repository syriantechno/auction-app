<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_data', function (Blueprint $table) {
            $table->id();
            
            // Polymorphic relationship
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            $table->index(['model_type', 'model_id']);
            
            // Meta tags
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            
            // Structured data
            $table->json('structured_data')->nullable();
            
            // SEO metrics
            $table->integer('seo_score')->default(0);
            $table->json('keywords')->nullable();
            
            // Content classification
            $table->string('content_type')->default('page');
            
            // Timestamps
            $table->timestamp('generated_at')->nullable();
            $table->timestamp('indexed_at')->nullable();
            $table->timestamp('last_submitted')->nullable();
            
            $table->timestamps();
            
            // Indexes for performance
            $table->index('content_type');
            $table->index('seo_score');
            $table->index('generated_at');
            $table->index('indexed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_data');
    }
};
