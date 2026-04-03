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
            
            // Generate meta tags
            $metaTags = $seoService->generateMetaTags($contentText, $this->contentType);
            
            // Generate structured data
            $structuredData = $seoService->generateStructuredData(
                $this->content, 
                $this->getSchemaType()
            );
            
            // Analyze keywords
            $keywords = $seoService->analyzeKeywords($contentText);
            
            // Save SEO data
            $this->saveSEOData([
                'meta_tags' => $metaTags,
                'structured_data' => $structuredData,
                'keywords' => $keywords,
                'content_type' => $this->contentType,
                'generated_at' => now(),
            ]);

            Log::info('SEO content generated successfully', [
                'model' => $this->model,
                'model_id' => $this->modelId,
                'type' => $this->contentType
            ]);

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
            if (is_string($value) && strlen($value) > 10) {
                $texts[] = $value;
            }
        }
        
        return implode(' ', $texts);
    }

    private function getSchemaType(): string
    {
        $types = config('ai_seo.content_types');
        return $types[$this->contentType]['schema'] ?? 'WebPage';
    }

    private function saveSEOData(array $seoData): void
    {
        $seoModel = $this->getSEOModel();
        
        if ($seoModel) {
            $seoModel->updateOrCreate(
                ['model_type' => $this->model, 'model_id' => $this->modelId],
                $seoData
            );
        }
    }

    private function getSEOModel()
    {
        // This would be your SEOData model
        // For now, we'll use a simple cache approach
        cache()->put(
            "seo_data_{$this->model}_{$this->modelId}",
            $seoData,
            now()->addDays(30)
        );
        
        return null;
    }
}
