<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SEOSettings extends Model
{
    use HasFactory;

    protected $table = 'seo_settings';
    
    protected $fillable = [
        'agent_router_provider',
        'agent_router_api_key',
        'agent_router_base_url',
        'agent_router_model',
        'google_analytics_id',
        'google_search_console_api_key',
        'bing_webmaster_api_key',
        'whatsapp_agent_api_key',
        'whatsapp_agent_phone',
        'auto_submit_google',
        'auto_submit_bing',
        'whatsapp_notifications',
        'notification_types',
        'ranking_track_keywords',
        'daily_reports',
        'alert_threshold',
    ];

    protected $casts = [
        'auto_submit_google' => 'boolean',
        'auto_submit_bing' => 'boolean',
        'whatsapp_notifications' => 'boolean',
        'notification_types' => 'array',
        'ranking_track_keywords' => 'array',
        'daily_reports' => 'boolean',
        'alert_threshold' => 'integer',
    ];

    /**
     * Get the current settings (singleton pattern)
     */
    public static function getCurrent()
    {
        return static::firstOrCreate(['id' => 1], [
            'agent_router_provider' => 'openai-compatible',
            'agent_router_api_key' => '',
            'agent_router_base_url' => 'https://agentrouter.org/v1',
            'agent_router_model' => 'deepseek-chat',
            'google_analytics_id' => '',
            'google_search_console_api_key' => '',
            'bing_webmaster_api_key' => '',
            'whatsapp_agent_api_key' => '',
            'whatsapp_agent_phone' => '',
            'auto_submit_google' => true,
            'auto_submit_bing' => true,
            'whatsapp_notifications' => false,
            'notification_types' => ['new_auction', 'seo_score_low', 'indexing_failed'],
            'ranking_track_keywords' => [],
            'daily_reports' => true,
            'alert_threshold' => 70,
        ]);
    }

    /**
     * Check if AgentRouter is configured
     */
    public function isAgentRouterConfigured(): bool
    {
        return filled($this->agent_router_api_key) || filled(config('ai_seo.agent_router.api_key'));
    }

    /**
     * Check if WhatsApp is configured
     */
    public function isWhatsAppConfigured(): bool
    {
        return !empty($this->whatsapp_agent_api_key) && !empty($this->whatsapp_agent_phone);
    }

    /**
     * Check if Google Analytics is configured
     */
    public function isGoogleAnalyticsConfigured(): bool
    {
        return !empty($this->google_analytics_id);
    }

    /**
     * Check if Google Search Console is configured
     */
    public function isGoogleSearchConsoleConfigured(): bool
    {
        return !empty($this->google_search_console_api_key);
    }

    /**
     * Get notification types as array
     */
    public function getNotificationTypesList(): array
    {
        return [
            'new_auction' => 'New Auction Created',
            'seo_score_low' => 'SEO Score Below Threshold',
            'indexing_failed' => 'Indexing Failed',
            'ranking_dropped' => 'Keyword Ranking Dropped',
            'daily_report' => 'Daily SEO Report',
            'bulk_completed' => 'Bulk SEO Generation Completed',
        ];
    }

    /**
     * Get active notification types
     */
    public function getActiveNotifications(): array
    {
        return array_intersect_key(
            $this->getNotificationTypesList(),
            array_flip($this->notification_types ?? [])
        );
    }
}
