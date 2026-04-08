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
        'ai_providers_config',
    ];

    protected $casts = [
        'auto_submit_google' => 'boolean',
        'auto_submit_bing' => 'boolean',
        'whatsapp_notifications' => 'boolean',
        'notification_types' => 'array',
        'ranking_track_keywords' => 'array',
        'daily_reports' => 'boolean',
        'alert_threshold' => 'integer',
        'ai_providers_config' => 'array',
    ];

    /**
     * Get config for a specific provider
     */
    public function getProviderConfig(string $provider): array
    {
        $config = $this->ai_providers_config ?? [];
        return $config[$provider] ?? [];
    }

    /**
     * Update active settings from a specific provider's saved config
     */
    public function syncActiveWithProvider(string $provider)
    {
        $config = $this->getProviderConfig($provider);
        if (!empty($config)) {
            $this->agent_router_api_key = $config['api_key'] ?? '';
            $this->agent_router_base_url = $config['base_url'] ?? '';
            $this->agent_router_model = $config['model'] ?? '';
        }
    }

    /**
     * Get list of supported AI providers
     */
    public static function getProviders(): array
    {
        return [
            'openrouter' => [
                'name' => 'OpenRouter (2026 Free Models)',
                'base_url' => 'https://openrouter.ai/api/v1',
                'models' => ['openrouter/free', 'meta-llama/llama-3.3-70b-instruct:free', 'mistralai/mistral-small-24b-instruct-2501:free', 'google/gemma-2-9b-it:free'],
            ],
            'groq' => [
                'name' => 'Groq Cloud (2026 Elite Models)',
                'base_url' => 'https://api.groq.com/openai/v1',
                'models' => ['llama-3.3-70b-versatile', 'llama-3.1-8b-instant', 'openai/gpt-oss-120b', 'openai/gpt-oss-20b', 'groq/compound', 'groq/compound-mini'],
            ],
            'agentrouter' => [
                'name' => 'AgentRouter.org (Standard 2026)',
                'base_url' => 'https://agentrouter.org/v1',
                'models' => ['deepseek-chat-v3', 'claude-3.5-haiku', 'gpt-4o-mini'],
            ],
            'together' => [
                'name' => 'Together AI (2026 Library)',
                'base_url' => 'https://api.together.xyz/v1',
                'models' => ['mistralai/Mistral-Small-24B-Instruct-2501', 'meta-llama/Llama-3.3-70B-Instruct-Turbo'],
            ],
            'openai' => [
                'name' => 'OpenAI Direct (2026)',
                'base_url' => 'https://api.openai.com/v1',
                'models' => ['gpt-4o', 'gpt-4o-mini', 'o1-mini'],
            ],
        ];
    }

    /**
     * Get the current settings (singleton pattern)
     */
    public static function getCurrent()
    {
        return static::firstOrCreate(['id' => 1], [
            'agent_router_provider' => 'openrouter',
            'agent_router_api_key' => '',
            'agent_router_base_url' => 'https://openrouter.ai/api/v1',
            'agent_router_model' => 'openrouter/free',
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
