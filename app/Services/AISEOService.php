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
        $this->baseUrl = $this->resolveSettingValue($settings->agent_router_base_url, config('ai_seo.agent_router.base_url'));
        $this->apiKey = $this->resolveSettingValue($settings->agent_router_api_key, config('ai_seo.agent_router.api_key'));
        $this->model = $this->resolveSupportedModel($settings->agent_router_model, config('ai_seo.agent_router.model'));
        $this->timeout = config('ai_seo.agent_router.timeout');
    }

    /**
     * Generate SEO meta tags using AI
     */
    public function generateMetaTags(string $content, string $type = 'page'): array
    {
        $prompt = $this->buildMetaTagsPrompt($content, $type);
        
        \Log::info('AISEOService - Sending request', ['prompt' => $prompt, 'model' => $this->model]);
        
        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert SEO specialist. Generate optimized meta tags for car auction websites. Always return valid JSON.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.3,
            'max_tokens' => 500
        ]);
        
        \Log::info('AISEOService - Raw response', ['response' => $response]);

        // If API returns empty, throw error - no demo mode
        if (empty($response)) {
            $error = $this->lastError;
            $message = 'AgentRouter API returned empty response.';

            if (!empty($error)) {
                $message .= ' HTTP ' . ($error['status'] ?? 'unknown') . '. ' . ($error['body_preview'] ?? $error['error'] ?? 'No response body received.');
            }

            \Log::error('AISEOService - API returned empty response', ['error' => $message]);
            throw new \Exception($message);
        }

        $result = $this->parseMetaTagsResponse($response);
        
        \Log::info('AISEOService - Parsed result', ['result' => $result]);

        return $result;
    }
    
    /**
     * Generate demo meta tags when API is unavailable
     */
    private function generateDemoMetaTags(string $content, string $type): array
    {
        // Extract keywords from content
        $keywords = $this->extractKeywords($content);
        
        $title = $this->generateTitle($content, $keywords);
        $description = $this->generateDescription($content, $keywords);
        
        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $title,
            'og_description' => $description,
            'demo_mode' => true
        ];
    }
    
    /**
     * Extract keywords from content
     */
    private function extractKeywords(string $content): array
    {
        $commonWords = ['the', 'a', 'an', 'is', 'are', 'was', 'were', 'be', 'been', 'being', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should'];
        
        // Extract potential keywords (capitalized words, numbers, model names)
        preg_match_all('/\b[A-Z][a-zA-Z]+\s+[A-Z][a-zA-Z]+\b|\b\d{4}\b|\b[A-Z]{1,3}\d+\b/', $content, $matches);
        
        $keywords = array_unique($matches[0] ?? []);
        
        // Add car-related keywords
        if (stripos($content, 'car') !== false || stripos($content, 'auto') !== false) {
            $keywords[] = 'car auction';
            $keywords[] = 'vehicle';
        }
        
        if (stripos($content, 'BMW') !== false) $keywords[] = 'BMW';
        if (stripos($content, 'Mercedes') !== false) $keywords[] = 'Mercedes-Benz';
        if (stripos($content, 'Audi') !== false) $keywords[] = 'Audi';
        if (stripos($content, 'Porsche') !== false) $keywords[] = 'Porsche';
        
        return array_slice($keywords, 0, 5);
    }
    
    /**
     * Generate title from content
     */
    private function generateTitle(string $content, array $keywords): string
    {
        // Extract year and model
        preg_match('/\b(20\d{2})\b/', $content, $yearMatch);
        $year = $yearMatch[1] ?? date('Y');
        
        preg_match('/\b(BMW|Mercedes|Audi|Porsche|Toyota|Honda)\s+([A-Z\d\s]+)/i', $content, $modelMatch);
        $model = $modelMatch[0] ?? 'Premium Vehicle';
        
        return "$year $model - Exclusive Car Auction | Bid Now";
    }
    
    /**
     * Generate description from content
     */
    private function generateDescription(string $content, array $keywords): string
    {
        $keywordsStr = implode(', ', array_slice($keywords, 0, 3));
        
        return "Bid on this exceptional vehicle. Features: $keywordsStr. Low mileage, premium condition. Live auction ending soon. Register now and place your bid!";
    }

    /**
     * Generate structured data (JSON-LD)
     */
    public function generateStructuredData(array $data, string $type = 'Product'): array
    {
        $prompt = $this->buildStructuredDataPrompt($data, $type);
        
        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Generate valid JSON-LD structured data for SEO. Return only valid JSON.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.1,
            'max_tokens' => 800
        ]);

        return json_decode($response, true) ?? [];
    }

    /**
     * Analyze content and extract keywords
     */
    public function analyzeKeywords(string $content): array
    {
        $prompt = "Extract the most important SEO keywords from this content. 
        Return as JSON array: [\"keyword1\", \"keyword2\", \"keyword3\"]
        Content: {$content}";

        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Extract SEO keywords. Return only JSON array.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.2,
            'max_tokens' => 200
        ]);

        return json_decode($response, true) ?? [];
    }

    /**
     * Generate SEO-optimized content
     */
    public function optimizeContent(string $originalContent, array $keywords = []): string
    {
        $keywordsText = implode(', ', $keywords);
        $prompt = "Optimize this content for SEO using these keywords: {$keywordsText}
        Keep the meaning but improve SEO. Add LSI keywords naturally.
        Content: {$originalContent}";

        $response = $this->makeRequest([
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an SEO content optimizer. Improve content for search engines.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.4,
            'max_tokens' => 1000
        ]);

        return $response;
    }

    /**
     * Generate SEO report
     */
    public function generateSEOReport(string $url): array
    {
        // Cache reports for 1 hour
        return Cache::remember("seo_report_{$url}", 3600, function () use ($url) {
            $prompt = "Analyze this URL for SEO issues and provide recommendations:
            URL: {$url}
            
            Return JSON with:
            {
                \"score\": 85,
                \"issues\": [\"Issue 1\", \"Issue 2\"],
                \"recommendations\": [\"Fix 1\", \"Fix 2\"],
                \"missing_tags\": [\"meta1\", \"meta2\"]
            }";

            $response = $this->makeRequest([
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an SEO auditor. Analyze websites and provide actionable recommendations.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.1,
                'max_tokens' => 600
            ]);

            return json_decode($response, true) ?? [];
        });
    }

    private function makeRequest(array $data): string
    {
        // Prefer the OpenAI Responses-compatible endpoint first, then fall back to chat completions.
        $this->lastError = [];

        $responsesResult = $this->tryAgentRouterResponsesAPI($data);
        if (!empty($responsesResult)) {
            return $responsesResult;
        }

        $agentRouterResult = $this->tryAgentRouterAPI($data);
        if (!empty($agentRouterResult)) {
            return $agentRouterResult;
        }

        $error = $this->lastError;
        $message = 'AgentRouter OpenAI-compatible API returned empty response.';

        if (!empty($error)) {
            $message .= ' HTTP ' . ($error['status'] ?? 'unknown') . '. ' . ($error['body_preview'] ?? $error['error'] ?? 'No response body received.');
        }

        throw new \Exception($message);
    }

    public function testMinimalAgentRouterConnection(): array
    {
        $url = rtrim($this->baseUrl, '/') . '/responses';

        $response = Http::timeout(30)
            ->withHeaders([
                'Authorization' => "Bearer {$this->apiKey}",
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($url, [
                'model' => $this->model,
                'input' => [
                    [
                        'role' => 'system',
                        'content' => 'You are a helpful assistant.',
                    ],
                    [
                        'role' => 'user',
                        'content' => 'Reply with: pong',
                    ],
                ],
                'temperature' => 0,
                'max_output_tokens' => 32,
            ]);

        $json = $response->json();
        $text = data_get($json, 'output.0.content.0.text')
            ?? data_get($json, 'output.0.content.0.output_text')
            ?? data_get($json, 'output.0.content.0.value')
            ?? data_get($json, 'output.0.text')
            ?? data_get($json, 'output_text')
            ?? data_get($json, 'text')
            ?? data_get($json, 'choices.0.message.content')
            ?? data_get($json, 'choices.0.message.text')
            ?? '';

        return [
            'success' => $response->successful() && filled($text),
            'status' => $response->status(),
            'url' => $url,
            'model' => $this->model,
            'response_text' => $text,
            'body_preview' => substr($response->body(), 0, 500),
            'raw_body' => $response->body(),
            'json' => $json,
        ];
    }

    private function tryAgentRouterResponsesAPI(array $data): string
    {
        try {
            \Log::info('Trying AgentRouter Responses API');

            $url = rtrim($this->baseUrl, '/') . '/responses';

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'model' => $data['model'] ?? 'deepseek-v3.2',
                    'input' => $data['messages'],
                    'temperature' => $data['temperature'] ?? 0.3,
                    'max_output_tokens' => $data['max_tokens'] ?? 500,
                ]);

            \Log::info('AgentRouter Responses Response', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200),
            ]);

            if ($response->successful()) {
                $json = $response->json();
                $text = data_get($json, 'output.0.content.0.text')
                    ?? data_get($json, 'output.0.content.0.output_text')
                    ?? data_get($json, 'output.0.content.0.value')
                    ?? data_get($json, 'output.0.text')
                    ?? data_get($json, 'output_text')
                    ?? data_get($json, 'text')
                    ?? data_get($json, 'choices.0.message.content')
                    ?? '';

                if (!empty($text)) {
                    $this->clearLastError();
                    return $text;
                }

                $this->setLastError([
                    'provider' => 'OpenAI Compatible (Responses)',
                    'status' => $response->status(),
                    'body_preview' => substr($response->body(), 0, 300),
                    'error' => 'Responses API returned an empty text payload.',
                ]);

                return '';
            }

            $this->setLastError([
                'provider' => 'OpenAI Compatible (Responses)',
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 300),
                'error' => 'Responses API request failed.',
            ]);

            return '';
        } catch (\Exception $e) {
            \Log::error('OpenAI Compatible Responses Exception', ['error' => $e->getMessage()]);
            $this->setLastError([
                'provider' => 'OpenAI Compatible (Responses)',
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    private function tryAgentRouterAPI(array $data): string
    {
        try {
            \Log::info('Trying AgentRouter Chat Completions API');

            $url = rtrim($this->baseUrl, '/') . '/chat/completions';

            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'model' => $data['model'] ?? 'deepseek-v3.2',
                    'messages' => $data['messages'],
                    'temperature' => $data['temperature'] ?? 0.3,
                    'max_tokens' => $data['max_tokens'] ?? 500,
                ]);

            \Log::info('AgentRouter Chat Completions Response', [
                'status' => $response->status(),
                'body' => substr($response->body(), 0, 200),
            ]);

            if ($response->successful()) {
                $json = $response->json();
                $content = data_get($json, 'choices.0.message.content')
                    ?? data_get($json, 'choices.0.message.text')
                    ?? data_get($json, 'output.0.content.0.text')
                    ?? data_get($json, 'output_text')
                    ?? data_get($json, 'text')
                    ?? '';

                if (!empty($content)) {
                    $this->clearLastError();
                    return $content;
                }

                $this->setLastError([
                    'provider' => 'OpenAI Compatible (Chat Completions)',
                    'status' => $response->status(),
                    'body_preview' => substr($response->body(), 0, 300),
                    'error' => 'Chat Completions returned an empty message payload.',
                ]);

                return '';
            }

            $this->setLastError([
                'provider' => 'OpenAI Compatible (Chat Completions)',
                'status' => $response->status(),
                'body_preview' => substr($response->body(), 0, 300),
                'error' => 'OpenAI-compatible request failed.',
            ]);

            return '';
        } catch (\Exception $e) {
            \Log::error('OpenAI Compatible Exception', ['error' => $e->getMessage()]);
            $this->setLastError([
                'provider' => 'OpenAI Compatible (Chat Completions)',
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    private function resolveSettingValue(?string $value, $fallback): string
    {
        return filled($value) ? trim($value) : trim((string) $fallback);
    }

    private function resolveSupportedModel(?string $value, $fallback): string
    {
        $supportedModels = array_keys(config('ai_seo.agent_router.supported_models', []));
        $candidate = filled($value) ? $value : (string) $fallback;

        if (in_array($candidate, $supportedModels, true)) {
            return $candidate;
        }

        $fallbackModel = (string) $fallback;

        return in_array($fallbackModel, $supportedModels, true)
            ? $fallbackModel
            : (string) ($supportedModels[0] ?? $candidate);
    }

    public function getLastError(): array
    {
        return $this->lastError;
    }

    private function setLastError(array $error): void
    {
        $this->lastError = $error;
    }

    private function clearLastError(): void
    {
        $this->lastError = [];
    }

    /**
     * Build prompt for meta tags generation
     */
    private function buildMetaTagsPrompt(string $content, string $type): string
    {
        $titleLength = config('ai_seo.generation.meta_title_length');
        $descLength = config('ai_seo.generation.meta_description_length');

        return "Generate SEO meta tags for this {$type} content:

        Content: {$content}

        Return JSON with:
        {
            \"title\": \"Compelling title under {$titleLength} chars\",
            \"description\": \"Engaging description under {$descLength} chars\",
            \"keywords\": [\"keyword1\", \"keyword2\", \"keyword3\"],
            \"og_title\": \"Social media title\",
            \"og_description\": \"Social media description\"
        }";
    }

    /**
     * Build prompt for structured data
     */
    private function buildStructuredDataPrompt(array $data, string $type): string
    {
        $dataJson = json_encode($data, JSON_PRETTY_PRINT);

        return "Generate JSON-LD structured data for {$type}:

        Data: {$dataJson}

        Include all relevant properties. Make it valid JSON-LD.";
    }

    /**
     * Parse meta tags response
     */
    private function parseMetaTagsResponse(string $response): array
    {
        if (preg_match('/```(?:json)?\s*(\{.*?\})\s*```/s', $response, $matches)) {
            $response = $matches[1];
        }

        $data = json_decode($response, true) ?? [];

        return [
            'title' => $data['title'] ?? '',
            'description' => $data['description'] ?? '',
            'keywords' => $data['keywords'] ?? [],
            'og_title' => $data['og_title'] ?? $data['title'] ?? '',
            'og_description' => $data['og_description'] ?? $data['description'] ?? '',
        ];
    }
}
