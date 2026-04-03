<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    private $measurementId;
    private $apiSecret;
    private $baseUrl;

    public function __construct()
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $this->measurementId = $settings->google_analytics_id;
        $this->apiSecret = config('services.google_analytics.api_secret');
        $this->baseUrl = 'https://www.google-analytics.com/mp/collect';
    }

    /**
     * Track page view
     */
    public function trackPageView(string $pagePath, string $pageTitle = null, array $additionalParams = []): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $payload = [
            'client_id' => $this->generateClientId(),
            'events' => [
                [
                    'name' => 'page_view',
                    'params' => array_merge([
                        'page_location' => url($pagePath),
                        'page_title' => $pageTitle ?? $pagePath,
                        'engagement_time_msec' => 1,
                    ], $additionalParams)
                ]
            ]
        ];

        return $this->sendEvent($payload);
    }

    /**
     * Track SEO-related events
     */
    public function trackSEOEvent(string $eventName, array $params = []): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $payload = [
            'client_id' => $this->generateClientId(),
            'events' => [
                [
                    'name' => $eventName,
                    'params' => array_merge([
                        'event_category' => 'SEO',
                    ], $params)
                ]
            ]
        ];

        return $this->sendEvent($payload);
    }

    /**
     * Track auction views
     */
    public function trackAuctionView(int $auctionId, string $auctionTitle, array $additionalParams = []): bool
    {
        return $this->trackPageView(
            "/auctions/{$auctionId}",
            $auctionTitle,
            array_merge([
                'content_type' => 'auction',
                'content_id' => $auctionId,
                'item_name' => $auctionTitle,
            ], $additionalParams)
        );
    }

    /**
     * Track SEO score changes
     */
    public function trackSEOScore(string $pageType, int $score, int $previousScore = null): bool
    {
        $params = [
            'page_type' => $pageType,
            'seo_score' => $score,
        ];

        if ($previousScore !== null) {
            $params['previous_score'] = $previousScore;
            $params['score_change'] = $score - $previousScore;
        }

        return $this->trackSEOEvent('seo_score_update', $params);
    }

    /**
     * Track indexing events
     */
    public function trackIndexingEvent(string $url, string $searchEngine, string $status): bool
    {
        return $this->trackSEOEvent('indexing_event', [
            'page_url' => $url,
            'search_engine' => $searchEngine,
            'indexing_status' => $status,
        ]);
    }

    /**
     * Track keyword ranking
     */
    public function trackKeywordRanking(string $keyword, int $position, string $url): bool
    {
        return $this->trackSEOEvent('keyword_ranking', [
            'keyword' => $keyword,
            'position' => $position,
            'page_url' => $url,
        ]);
    }

    /**
     * Get real-time data (simplified version)
     */
    public function getRealTimeData(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        return Cache::remember('ga_realtime_data', 60, function () {
            // This would use GA Data API in production
            // For now, return mock data
            return [
                'active_users' => rand(10, 50),
                'page_views' => rand(100, 500),
                'sessions' => rand(50, 200),
                'avg_session_duration' => rand(60, 300),
            ];
        });
    }

    /**
     * Get top pages
     */
    public function getTopPages(int $limit = 10): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        return Cache::remember('ga_top_pages', 300, function () use ($limit) {
            // Mock data - replace with actual GA API call
            return [
                ['path' => '/', 'views' => 1500, 'title' => 'Home'],
                ['path' => '/auctions', 'views' => 800, 'title' => 'Auctions'],
                ['path' => '/about', 'views' => 400, 'title' => 'About Us'],
            ];
        });
    }

    /**
     * Get traffic sources
     */
    public function getTrafficSources(): array
    {
        if (!$this->isConfigured()) {
            return [];
        }

        return Cache::remember('ga_traffic_sources', 300, function () {
            // Mock data - replace with actual GA API call
            return [
                ['source' => 'google', 'sessions' => 500, 'percentage' => 45],
                ['source' => 'direct', 'sessions' => 300, 'percentage' => 27],
                ['source' => 'social', 'sessions' => 200, 'percentage' => 18],
                ['source' => 'referral', 'sessions' => 100, 'percentage' => 10],
            ];
        });
    }

    /**
     * Send event to Google Analytics
     */
    private function sendEvent(array $payload): bool
    {
        try {
            $response = Http::post($this->buildUrl(), $payload);

            if (!$response->successful()) {
                Log::error('Google Analytics API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'payload' => $payload
                ]);
                return false;
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Google Analytics Service Error', [
                'error' => $e->getMessage(),
                'payload' => $payload
            ]);
            return false;
        }
    }

    /**
     * Build GA4 API URL
     */
    private function buildUrl(): string
    {
        return "{$this->baseUrl}?measurement_id={$this->measurementId}&api_secret={$this->apiSecret}";
    }

    /**
     * Generate client ID
     */
    private function generateClientId(): string
    {
        // In production, use proper client ID generation/persistence
        return uniqid() . '.' . time();
    }

    /**
     * Check if GA is configured
     */
    private function isConfigured(): bool
    {
        return !empty($this->measurementId) && !empty($this->apiSecret);
    }

    /**
     * Track custom event
     */
    public function trackCustomEvent(string $eventName, array $params = []): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $payload = [
            'client_id' => $this->generateClientId(),
            'events' => [
                [
                    'name' => $eventName,
                    'params' => $params
                ]
            ]
        ];

        return $this->sendEvent($payload);
    }

    /**
     * Track search queries
     */
    public function trackSearchQuery(string $query, int $resultsCount = 0): bool
    {
        return $this->trackCustomEvent('search', [
            'search_term' => $query,
            'results_count' => $resultsCount,
        ]);
    }

    /**
     * Track user engagement
     */
    public function trackEngagement(string $action, string $elementType, string $elementId = null): bool
    {
        return $this->trackCustomEvent('engagement', [
            'action' => $action,
            'element_type' => $elementType,
            'element_id' => $elementId,
        ]);
    }
}
