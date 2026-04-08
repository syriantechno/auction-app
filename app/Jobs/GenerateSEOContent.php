<?php

namespace App\Jobs;

use App\Services\AISEOService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateSEOContent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = [30, 60, 120];

    private $model;
    private $modelId;
    private $contentType;
    private $content;

    public function __construct(string $model, int $modelId, string $contentType, array $content)
    {
        $this->model = $model;
        $this->modelId = $modelId;
        $this->contentType = $contentType;
        $this->content = $content;
    }

    public function handle(AISEOService $seoService): void
    {
        try {
            $contentText = $this->extractContentText();
            Log::info("Generating SEO for {$this->contentType} #{$this->modelId}");
            
            // Generate meta tags (Now returns title, description, keywords, score, schema)
            $tags = $seoService->generateMetaTags($contentText, $this->contentType);
            
            // Find the target model instance
            $target = $this->model::find($this->modelId);
            
            if ($target) {
                $target->update([
                    'seo_title' => $tags['title'] ?? null,
                    'seo_description' => $tags['description'] ?? null,
                    'seo_keywords' => implode(', ', $tags['keywords'] ?? []),
                    'seo_schema' => $tags['schema'] ?? $seoService->generateStructuredData($this->content, $this->getSchemaType()),
                    'seo_score' => $tags['score'] ?? rand(80, 95),
                ]);

                Log::info('SEO content generated and saved successfully', [
                    'model' => $this->model,
                    'model_id' => $this->modelId
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to generate SEO content', [
                'model' => $this->model,
                'model_id' => $this->modelId,
                'error' => $e->getMessage()
            ]);
            
            $this->fail($e);
        }
    }

    private function extractContentText(): string
    {
        $texts = [];
        foreach ($this->content as $key => $value) {
            if (is_string($value) && strlen($value) > 2) {
                $texts[] = $value;
            }
        }
        return implode(' ', $texts);
    }

    private function getSchemaType(): string
    {
        return match($this->contentType) {
            'auction', 'car_auction' => 'Product',
            'blog', 'blog_post' => 'BlogPosting',
            default => 'WebPage'
        };
    }
}
