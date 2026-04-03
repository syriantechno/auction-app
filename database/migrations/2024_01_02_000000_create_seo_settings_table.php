<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();
            
            // AgentRouter API
            $table->string('agent_router_api_key')->nullable();
            $table->string('agent_router_base_url')->nullable();
            $table->string('agent_router_model')->nullable();
            
            // Google Services
            $table->string('google_analytics_id')->nullable();
            $table->string('google_search_console_api_key')->nullable();
            
            // Bing Services
            $table->string('bing_webmaster_api_key')->nullable();
            
            // WhatsApp Agent
            $table->string('whatsapp_agent_api_key')->nullable();
            $table->string('whatsapp_agent_phone')->nullable();
            
            // SEO Settings
            $table->boolean('auto_submit_google')->default(true);
            $table->boolean('auto_submit_bing')->default(true);
            $table->boolean('whatsapp_notifications')->default(false);
            $table->json('notification_types')->nullable();
            $table->json('ranking_track_keywords')->nullable();
            $table->boolean('daily_reports')->default(true);
            $table->integer('alert_threshold')->default(70);
            
            $table->timestamps();
            
            // Indexes
            $table->index('agent_router_api_key');
            $table->index('google_analytics_id');
            $table->index('whatsapp_agent_phone');
        });
    }

    public function down()
    {
        Schema::dropIfExists('seo_settings');
    }
};
