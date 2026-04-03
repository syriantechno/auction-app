<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\SEOData;

class RankTrackingService
{
    private $googleApiKey;
    private $bingApiKey;
    private $cacheDuration = 3600; // 1 hour

    public function __construct()
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $this->googleApiKey = $settings->google_search_console_api_key;
        $this->bingApiKey = $settings->bing_webmaster_api_key;
    }

    /**
     * Track keyword rankings for multiple keywords
     */
    public function trackKeywordRankings(array $keywords, string $domain): array
    {
        $results = [];

        foreach ($keywords as $keyword) {
            $results[$keyword] = [
                'google' => $this->trackGoogleRanking($keyword, $domain),
                'bing' => $this->trackBingRanking($keyword, $domain),
                'timestamp' => now(),
            ];

            // Add delay to avoid rate limiting
            usleep(100000); // 0.1 second
        }

        return $results;
    }

    /**
     * Track single keyword ranking on Google
     */
    public function trackGoogleRanking(string $keyword, string $domain): array
    {
        $cacheKey = "google_ranking_{$keyword}_{$domain}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($keyword, $domain) {
            if (empty($this->googleApiKey)) {
                return $this->getMockRankingData($keyword, $domain, 'google');
            }

            try {
                // In production, use Google Search Console API or third-party service
                // For now, return mock data
                return $this->getMockRankingData($keyword, $domain, 'google');
            } catch (\Exception $e) {
                Log::error('Google ranking tracking failed', [
                    'keyword' => $keyword,
                    'domain' => $domain,
                    'error' => $e->getMessage()
                ]);

                return [
                    'position' => null,
                    'url' => null,
                    'title' => null,
                    'error' => 'Tracking failed'
                ];
            }
        });
    }

    /**
     * Track single keyword ranking on Bing
     */
    public function trackBingRanking(string $keyword, string $domain): array
    {
        $cacheKey = "bing_ranking_{$keyword}_{$domain}";
        
        return Cache::remember($cacheKey, $this->cacheDuration, function () use ($keyword, $domain) {
            if (empty($this->bingApiKey)) {
                return $this->getMockRankingData($keyword, $domain, 'bing');
            }

            try {
                // In production, use Bing Webmaster API or third-party service
                // For now, return mock data
                return $this->getMockRankingData($keyword, $domain, 'bing');
            } catch (\Exception $e) {
                Log::error('Bing ranking tracking failed', [
                    'keyword' => $keyword,
                    'domain' => $domain,
                    'error' => $e->getMessage()
                ]);

                return [
                    'position' => null,
                    'url' => null,
                    'title' => null,
                    'error' => 'Tracking failed'
                ];
            }
        });
    }

    /**
     * Get ranking history for a keyword
     */
    public function getRankingHistory(string $keyword, string $domain, int $days = 30): array
    {
        $history = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $history[$date] = [
                'google' => $this->getHistoricalRanking($keyword, $domain, 'google', $date),
                'bing' => $this->getHistoricalRanking($keyword, $domain, 'bing', $date),
            ];
        }

        return $history;
    }

    /**
     * Get ranking trends
     */
    public function getRankingTrends(string $domain): array
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $keywords = $settings->ranking_track_keywords ?? [];

        $trends = [];
        
        foreach ($keywords as $keyword) {
            $currentRankings = $this->trackKeywordRankings([$keyword], $domain)[$keyword];
            $previousRankings = $this->getPreviousRankings($keyword, $domain);

            $trends[$keyword] = [
                'current' => $currentRankings,
                'previous' => $previousRankings,
                'trend' => $this->calculateTrend($currentRankings, $previousRankings),
            ];
        }

        return $trends;
    }

    /**
     * Track competitor rankings
     */
    public function trackCompetitorRankings(array $keywords, array $competitors): array
    {
        $results = [];

        foreach ($competitors as $competitor) {
            $results[$competitor] = [];
            
            foreach ($keywords as $keyword) {
                $results[$competitor][$keyword] = [
                    'google' => $this->trackGoogleRanking($keyword, $competitor),
                    'bing' => $this->trackBingRanking($keyword, $competitor),
                ];
            }
        }

        return $results;
    }

    /**
     * Get keyword suggestions based on current rankings
     */
    public function getKeywordSuggestions(string $domain): array
    {
        // This would use AI to suggest keywords based on current content and rankings
        return [
            'car auction online' => ['difficulty' => 'medium', 'volume' => 'high'],
            'used cars bidding' => ['difficulty' => 'low', 'volume' => 'medium'],
            'online car marketplace' => ['difficulty' => 'high', 'volume' => 'high'],
            'vehicle auction platform' => ['difficulty' => 'medium', 'volume' => 'medium'],
        ];
    }

    /**
     * Generate ranking report
     */
    public function generateRankingReport(string $domain): array
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $keywords = $settings->ranking_track_keywords ?? [];

        $rankings = $this->trackKeywordRankings($keywords, $domain);
        $trends = $this->getRankingTrends($domain);

        return [
            'domain' => $domain,
            'generated_at' => now(),
            'keywords' => $keywords,
            'rankings' => $rankings,
            'trends' => $trends,
            'summary' => $this->generateRankingSummary($rankings),
        ];
    }

    /**
     * Get mock ranking data (for development)
     */
    private function getMockRankingData(string $keyword, string $domain, string $engine): array
    {
        $position = rand(1, 100);
        $url = "https://{$domain}/" . ($position <= 10 ? 'auctions' : 'other');
        $title = $position <= 10 ? "Best {$keyword} - {$domain}" : "Other result for {$keyword}";

        return [
            'position' => $position,
            'url' => $url,
            'title' => $title,
            'snippet' => "Find the best {$keyword} at our platform. Great deals and amazing offers.",
            'tracked_at' => now(),
        ];
    }

    /**
     * Get historical ranking data
     */
    private function getHistoricalRanking(string $keyword, string $domain, string $engine, string $date): ?array
    {
        // In production, this would query the database for historical data
        // For now, return mock historical data
        $position = rand(1, 100);
        
        return $position <= 50 ? [
            'position' => $position,
            'date' => $date,
        ] : null;
    }

    /**
     * Get previous rankings for comparison
     */
    private function getPreviousRankings(string $keyword, string $domain): array
    {
        return [
            'google' => ['position' => rand(1, 100), 'date' => now()->subDay()],
            'bing' => ['position' => rand(1, 100), 'date' => now()->subDay()],
        ];
    }

    /**
     * Calculate ranking trend
     */
    private function calculateTrend(array $current, array $previous): string
    {
        $currentPos = $current['google']['position'] ?? 100;
        $previousPos = $previous['google']['position'] ?? 100;

        if ($currentPos < $previousPos) {
            return 'up';
        } elseif ($currentPos > $previousPos) {
            return 'down';
        } else {
            return 'stable';
        }
    }

    /**
     * Generate ranking summary
     */
    private function generateRankingSummary(array $rankings): array
    {
        $totalKeywords = count($rankings);
        $top10Count = 0;
        $top3Count = 0;
        $averagePosition = 0;

        foreach ($rankings as $keyword => $data) {
            $googlePos = $data['google']['position'] ?? 100;
            $bingPos = $data['bing']['position'] ?? 100;
            
            $avgPos = ($googlePos + $bingPos) / 2;
            $averagePosition += $avgPos;

            if ($avgPos <= 10) $top10Count++;
            if ($avgPos <= 3) $top3Count++;
        }

        return [
            'total_keywords' => $totalKeywords,
            'top_10_count' => $top10Count,
            'top_3_count' => $top3Count,
            'top_10_percentage' => $totalKeywords > 0 ? round(($top10Count / $totalKeywords) * 100, 2) : 0,
            'average_position' => $totalKeywords > 0 ? round($averagePosition / $totalKeywords, 2) : 0,
        ];
    }

    /**
     * Check if ranking tracking is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->googleApiKey) || !empty($this->bingApiKey);
    }

    /**
     * Add keyword to tracking list
     */
    public function addKeywordToTracking(string $keyword): void
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $keywords = $settings->ranking_track_keywords ?? [];
        
        if (!in_array($keyword, $keywords)) {
            $keywords[] = $keyword;
            $settings->update(['ranking_track_keywords' => $keywords]);
        }
    }

    /**
     * Remove keyword from tracking list
     */
    public function removeKeywordFromTracking(string $keyword): void
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $keywords = $settings->ranking_track_keywords ?? [];
        
        $keywords = array_filter($keywords, function ($k) use ($keyword) {
            return $k !== $keyword;
        });

        $settings->update(['ranking_track_keywords' => array_values($keywords)]);
    }
}
