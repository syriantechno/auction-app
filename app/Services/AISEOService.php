<?php
 
namespace App\Services;
 
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
 
class AISEOService
{
    private $baseUrl;
    private $apiKey;
    private $model;
    private $timeout;
    private array $lastError = [];
 
    public function __construct()
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        $this->baseUrl = filled($settings->agent_router_base_url) ? trim($settings->agent_router_base_url) : config('ai_seo.agent_router.base_url');
        $this->apiKey = filled($settings->agent_router_api_key) ? trim($settings->agent_router_api_key) : config('ai_seo.agent_router.api_key');
        $this->model = filled($settings->agent_router_model) ? trim($settings->agent_router_model) : config('ai_seo.agent_router.model');
        $this->timeout = config('ai_seo.agent_router.timeout', 60);
    }
 
    private function getAuthHeaders(): array
    {
        $cleanKey = preg_replace('/[\x00-\x1F\x7F\s]/', '', $this->apiKey);
        return [
            'Authorization' => "Bearer {$cleanKey}",
            'Authentication' => "Bearer {$cleanKey}", 
            'X-API-KEY' => $cleanKey,
            'api-key' => $cleanKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'HTTP-Referer' => config('app.url', 'http://localhost'),
            'X-Title' => config('app.name', 'Auction SEO Agent'),
        ];
    }
 
    public function generateMetaTags(string $content, string $type = 'page'): array
    {
        $prompt = $this->buildMetaTagsPrompt($content, $type);
        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'You are an SEO expert. Return JSON.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.3
        ]);
        
        if (empty($response)) throw new \Exception('AI API returned empty response.');
        return $this->parseMetaTagsResponse($response);
    }

    public function generateStructuredData(array $content, string $schemaType = 'WebPage'): string
    {
        $prompt = "Generate a JSON-LD structured data script (schema.org) for a {$schemaType} based on this data: " . json_encode($content) . ". Return ONLY the JSON object, no markdown.";
        
        try {
            $response = $this->makeRequest([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a technical SEO expert. Return valid JSON-LD.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.1
            ]);
            
            if (preg_match('/\{.*\}/s', $response, $matches)) {
                return $matches[0];
            }
            return '{}';
        } catch (\Exception $e) {
            return '{}';
        }
    }

    public function analyzeKeywords(string $content): array
    {
        $prompt = "Analyze this content and return the top 10 most relevant SEO keywords: {$content}. Return ONLY a JSON array of strings: [\"keyword1\", \"keyword2\", ...]";
        
        try {
            $response = $this->makeRequest([
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an SEO analyst. Return JSON array.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'temperature' => 0.4
            ]);
            
            if (preg_match('/\[.*\]/s', $response, $matches)) {
                return json_decode($matches[0], true) ?? [];
            }
            return [];
        } catch (\Exception $e) {
            return [];
        }
    }
 
    public function optimizeContent(string $originalContent, array $keywords = []): string
    {
        $keywordsText = implode(', ', $keywords);
        return $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'Optimize content for SEO.'],
                ['role' => 'user', 'content' => "Keywords: {$keywordsText}\n\nContent: {$originalContent}"]
            ]
        ]);
    }
 
    public function generateSEOReport(string $url): array
    {
        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                ['role' => 'system', 'content' => 'Audit this URL for SEO. Return JSON.'],
                ['role' => 'user', 'content' => "URL: {$url}"]
            ]
        ]);
        return json_decode($response, true) ?? ['score' => 0, 'issues' => ['Analysis failed']];
    }
 
    private function makeRequest(array $data): string
    {
        $res = $this->tryAgentRouterAPI($data);
        if (!empty($res)) return $res;
        
        $res = $this->tryAgentRouterResponsesAPI($data);
        if (!empty($res)) return $res;
 
        throw new \Exception('AI Global Request failed. Check API Key and Provider status.');
    }
 
    public function testMinimalAgentRouterConnection(): array
    {
        $url = $this->resolveUrl('/chat/completions');
        try {
            $response = Http::timeout(20)->withHeaders($this->getAuthHeaders())->post($url, [
                'model' => $this->model,
                'messages' => [['role' => 'user', 'content' => 'ping']],
                'max_tokens' => 5
            ]);
 
            if ($response->successful()) {
                return [
                    'success' => true,
                    'status' => $response->status(),
                    'response_text' => data_get($response->json(), 'choices.0.message.content', 'Connected'),
                ];
            }
 
            return [
                'success' => false,
                'status' => $response->status(),
                'message' => $response->json('error.message') ?? 'Auth Error or Model not found.',
                'raw_body' => $response->body()
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
 
    private function tryAgentRouterAPI(array $data): string
    {
        $url = $this->resolveUrl('/chat/completions');
        try {
            $response = Http::timeout(60)->withHeaders($this->getAuthHeaders())->post($url, $data);
            return $response->successful() ? data_get($response->json(), 'choices.0.message.content', '') : '';
        } catch (\Exception $e) { return ''; }
    }
 
    private function tryAgentRouterResponsesAPI(array $data): string
    {
        $url = $this->resolveUrl('/responses');
        try {
            $response = Http::timeout(60)->withHeaders($this->getAuthHeaders())->post($url, [
                'model' => $data['model'],
                'input' => $data['messages'],
                'max_output_tokens' => $data['max_tokens'] ?? 500
            ]);
            return $response->successful() ? (data_get($response->json(), 'output.0.content.0.text') ?? '') : '';
        } catch (\Exception $e) { return ''; }
    }
 
    private function resolveUrl(string $suffix): string
    {
        $url = $this->baseUrl;
        if (str_contains($url, '/chat/completions') || str_contains($url, '/responses')) return $url;
        return rtrim($url, '/') . $suffix;
    }
 
    private function buildMetaTagsPrompt(string $content, string $type): string
    {
        return "SEO Meta tags for {$type}: {$content}. Return JSON: {\"title\":\"\",\"description\":\"\",\"keywords\":[], \"score\": 85, \"schema\": {}}";
    }
 
    private function parseMetaTagsResponse(string $response): array
    {
        if (preg_match('/\{.*\}/s', $response, $matches)) {
            $data = json_decode($matches[0], true);
            return [
                'title' => $data['title'] ?? '',
                'description' => $data['description'] ?? '',
                'keywords' => $data['keywords'] ?? [],
                'score' => $data['score'] ?? 0,
                'schema' => $data['schema'] ?? null,
                'og_title' => $data['og_title'] ?? ($data['title'] ?? ''),
                'og_description' => $data['og_description'] ?? ($data['description'] ?? ''),
            ];
        }
        return ['title' => 'Error', 'description' => 'Parsing failed', 'keywords' => [], 'score' => 0];
    }
}
