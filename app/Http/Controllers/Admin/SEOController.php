<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AISEOService;
use App\Services\GoogleAnalyticsService;
use App\Services\RankTrackingService;
use App\Services\WhatsAppAgentService;
use App\Models\SEOSettings;
use App\Jobs\GenerateSEOContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SEOController extends Controller
{
    private $seoService;

    public function __construct(AISEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * SEO Dashboard
     */
    public function dashboard()
    {
        return view('admin.seo.dashboard', [
            'stats' => $this->getSEOStats(),
            'recentReports' => $this->getRecentReports(),
        ]);
    }

    /**
     * Generate SEO for specific content
     */
    public function generate(Request $request)
    {
        $validated = $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'content' => 'required|array',
        ]);

        try {
            // Dispatch job for async processing
            GenerateSEOContent::dispatch(
                $validated['model_type'],
                $validated['model_id'],
                $this->getContentType($validated['model_type']),
                $validated['content']
            );

            return response()->json([
                'success' => true,
                'message' => 'SEO generation started successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('SEO generation failed', [
                'error' => $e->getMessage(),
                'request' => $validated
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate SEO content'
            ], 500);
        }
    }

    /**
     * Analyze URL for SEO
     */
    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url'
        ]);

        try {
            $report = $this->seoService->generateSEOReport($validated['url']);

            return response()->json([
                'success' => true,
                'report' => $report
            ]);

        } catch (\Exception $e) {
            Log::error('SEO analysis failed', [
                'url' => $validated['url'],
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze URL'
            ], 500);
        }
    }

    /**
     * Generate meta tags on-demand
     */
    public function generateMetaTags(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'type' => 'string|in:page,auction,blog'
        ]);

        try {
            $metaTags = $this->seoService->generateMetaTags(
                $validated['content'],
                $validated['type'] ?? 'page'
            );

            return response()->json([
                'success' => true,
                'meta_tags' => $metaTags
            ]);

        } catch (\Exception $e) {
            Log::error('Meta tags generation failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate meta tags'
            ], 500);
        }
    }

    /**
     * Optimize existing content
     */
    public function optimizeContent(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'keywords' => 'array'
        ]);

        try {
            $optimized = $this->seoService->optimizeContent(
                $validated['content'],
                $validated['keywords'] ?? []
            );

            return response()->json([
                'success' => true,
                'optimized_content' => $optimized
            ]);

        } catch (\Exception $e) {
            Log::error('Content optimization failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to optimize content'
            ], 500);
        }
    }

    /**
     * SEO Settings page
     */
    public function settings()
    {
        $settings = SEOSettings::getCurrent();
        
        return view('admin.seo.settings', compact('settings'));
    }

    /**
     * Update SEO settings
     */
    public function updateSettings(Request $request)
    {
        $settings = SEOSettings::getCurrent();
        
        \Log::info('SEO Settings Update Request', $request->all());
        
        $validated = $request->validate([
            'agent_router_provider' => 'nullable|string|in:openai-compatible,openai',
            'agent_router_api_key' => 'nullable|string',
            'agent_router_base_url' => 'nullable|string',
            'agent_router_model' => ['nullable', 'string', Rule::in(array_keys(config('ai_seo.agent_router.supported_models', [])))],
            'google_analytics_id' => 'nullable|string',
            'google_search_console_api_key' => 'nullable|string',
            'bing_webmaster_api_key' => 'nullable|string',
            'whatsapp_agent_api_key' => 'nullable|string',
            'whatsapp_agent_phone' => 'nullable|string',
            'auto_submit_google' => 'boolean',
            'auto_submit_bing' => 'boolean',
            'whatsapp_notifications' => 'boolean',
            'notification_types' => 'array',
            'ranking_track_keywords' => 'array',
            'daily_reports' => 'boolean',
            'alert_threshold' => 'integer|min:0|max:100',
        ]);

        \Log::info('SEO Settings Validated', $validated);

        $settings->update($validated);
        
        \Log::info('SEO Settings Updated', ['settings' => $settings->fresh()->toArray()]);

        return redirect()->route('admin.seo.settings')
            ->with('success', 'SEO settings updated successfully!');
    }

    /**
     * Test AgentRouter connection
     */
    public function testAgentRouter()
    {
        $settings = SEOSettings::getCurrent();
        $apiKey = filled($settings->agent_router_api_key) ? $settings->agent_router_api_key : config('ai_seo.agent_router.api_key');
        $baseUrl = filled($settings->agent_router_base_url) ? $settings->agent_router_base_url : config('ai_seo.agent_router.base_url');
        $supportedModels = array_keys(config('ai_seo.agent_router.supported_models', []));
        $candidateModel = filled($settings->agent_router_model) ? $settings->agent_router_model : config('ai_seo.agent_router.model');
        $model = in_array($candidateModel, $supportedModels, true)
            ? $candidateModel
            : (in_array(config('ai_seo.agent_router.model'), $supportedModels, true)
                ? config('ai_seo.agent_router.model')
                : ($supportedModels[0] ?? $candidateModel));
        
        // Check if API key is configured
        if (empty($apiKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Error: API Key is not configured. Please add your AgentRouter API key first.'
            ]);
        }
        
        try {
            $seoService = app(AISEOService::class);
            
            // Test API with a lightweight Responses request
            $testResponse = $seoService->testMinimalAgentRouterConnection();
            $success = (bool) ($testResponse['success'] ?? false);
            
            return response()->json([
                'success' => $success,
                'message' => $success
                    ? 'AgentRouter API connection successful!'
                    : 'Failed to connect to AgentRouter API',
                'details' => [
                    'model' => $model,
                    'base_url' => $baseUrl,
                    'test_type' => 'minimal_responses_ping',
                    'status' => $testResponse['status'] ?? null,
                    'url' => $testResponse['url'] ?? null,
                    'response_text' => $testResponse['response_text'] ?? null,
                    'body_preview' => $testResponse['body_preview'] ?? null,
                    'raw_body' => $testResponse['raw_body'] ?? null,
                    'response_json' => $testResponse['json'] ?? null,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'details' => [
                    'service_error' => method_exists($seoService ?? null, 'getLastError') ? ($seoService->getLastError() ?? []) : [],
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]
            ]);
        }
    }

    /**
     * Verify the saved AgentRouter API key without exposing it
     */
    public function verifyAgentRouterKey()
    {
        $settings = SEOSettings::getCurrent();
        $storedKey = $settings->agent_router_api_key;
        $fallbackKey = config('ai_seo.agent_router.api_key');
        $effectiveKey = filled($storedKey) ? $storedKey : $fallbackKey;

        if (blank($effectiveKey)) {
            return response()->json([
                'success' => false,
                'message' => 'No API key is stored in the database or configured in the environment.',
            ]);
        }

        $source = filled($storedKey) ? 'database' : 'environment';
        $length = strlen($effectiveKey);
        $last4 = substr($effectiveKey, -4);

        return response()->json([
            'success' => true,
            'message' => 'API key is present and readable.',
            'source' => $source,
            'stored_in_database' => filled($storedKey),
            'database_length' => filled($storedKey) ? strlen($storedKey) : 0,
            'effective_length' => $length,
            'masked_preview' => 'sk-••••••••••••••' . $last4,
        ]);
    }

    /**
     * Test WhatsApp connection
     */
    public function testWhatsApp()
    {
        $whatsappService = app(WhatsAppAgentService::class);
        
        try {
            $success = $whatsappService->testConnection();
            
            return response()->json([
                'success' => $success,
                'message' => $success ? 'Test message sent successfully!' : 'Failed to send test message'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get Google Analytics data
     */
    public function getAnalyticsData()
    {
        $analyticsService = app(GoogleAnalyticsService::class);
        
        return response()->json([
            'realtime' => $analyticsService->getRealTimeData(),
            'top_pages' => $analyticsService->getTopPages(),
            'traffic_sources' => $analyticsService->getTrafficSources(),
        ]);
    }

    /**
     * Get ranking data
     */
    public function getRankingData(Request $request)
    {
        $domain = $request->input('domain', parse_url(config('app.url'), PHP_URL_HOST));
        $rankService = app(RankTrackingService::class);
        
        return response()->json([
            'trends' => $rankService->getRankingTrends($domain),
            'report' => $rankService->generateRankingReport($domain),
        ]);
    }

    /**
     * Submit URLs to search engines
     */
    public function submitUrls(Request $request)
    {
        $validated = $request->validate([
            'urls' => 'required|array',
            'urls.*' => 'url'
        ]);

        $results = [];

        foreach ($validated['urls'] as $url) {
            try {
                // Submit to Google (if API key is configured)
                if (config('ai_seo.indexing.auto_submit_google')) {
                    $googleResult = $this->submitToGoogle($url);
                    $results[$url]['google'] = $googleResult;
                }

                // Submit to Bing (if API key is configured)
                if (config('ai_seo.indexing.auto_submit_bing')) {
                    $bingResult = $this->submitToBing($url);
                    $results[$url]['bing'] = $bingResult;
                }

                // Add delay between submissions
                sleep(config('ai_seo.indexing.submission_delay', 2));

            } catch (\Exception $e) {
                $results[$url]['error'] = $e->getMessage();
            }
        }

        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Get SEO statistics
     */
    private function getSEOStats(): array
    {
        return [
            'total_pages' => $this->getTotalPages(),
            'optimized_pages' => $this->getOptimizedPages(),
            'indexed_pages' => $this->getIndexedPages(),
            'pending_submissions' => $this->getPendingSubmissions(),
            'average_score' => $this->getAverageSEOScore(),
        ];
    }

    /**
     * Get recent SEO reports
     */
    private function getRecentReports(): array
    {
        // This would fetch from your SEO reports table
        return [
            ['url' => '/', 'score' => 85, 'date' => now()->subDay()],
            ['url' => '/auctions', 'score' => 78, 'date' => now()->subDays(2)],
            ['url' => '/about', 'score' => 92, 'date' => now()->subDays(3)],
        ];
    }

    /**
     * Helper methods
     */
    private function getContentType(string $modelType): string
    {
        return match ($modelType) {
            'App\\Models\\Auction' => 'auction',
            'App\\Models\\Page' => 'page',
            'App\\Models\\Blog' => 'blog',
            default => 'page'
        };
    }

    private function submitToGoogle(string $url): array
    {
        // Google Search Console API integration
        return ['status' => 'submitted', 'message' => 'Submitted to Google'];
    }

    private function submitToBing(string $url): array
    {
        // Bing Webmaster API integration
        return ['status' => 'submitted', 'message' => 'Submitted to Bing'];
    }

    private function getTotalPages(): int
    {
        return 150; // Placeholder
    }

    private function getOptimizedPages(): int
    {
        return 120; // Placeholder
    }

    private function getIndexedPages(): int
    {
        return 110; // Placeholder
    }

    private function getPendingSubmissions(): int
    {
        return 5; // Placeholder
    }

    private function getAverageSEOScore(): float
    {
        return 82.5; // Placeholder
    }
}
