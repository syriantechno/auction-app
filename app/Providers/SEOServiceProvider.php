<?php

namespace App\Providers;

use App\Services\AISEOService;
use App\Services\GoogleAnalyticsService;
use App\Services\RankTrackingService;
use App\Services\WhatsAppAgentService;
use Illuminate\Support\ServiceProvider;

class SEOServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AISEOService::class, function ($app) {
            return new AISEOService();
        });

        $this->app->singleton(GoogleAnalyticsService::class, function ($app) {
            return new GoogleAnalyticsService();
        });

        $this->app->singleton(RankTrackingService::class, function ($app) {
            return new RankTrackingService();
        });

        $this->app->singleton(WhatsAppAgentService::class, function ($app) {
            return new WhatsAppAgentService();
        });
    }

    public function boot()
    {
        // Auto-generate SEO for models
        $this->bootAutoSEOGeneration();
        
        // Register middleware for SEO
        $this->app['router']->aliasMiddleware('seo', \App\Http\Middleware\SEOMiddleware::class);
    }

    private function bootAutoSEOGeneration()
    {
        // Auction SEO is handled exclusively by AuctionObserver — do not add it here
        $models = ['Page', 'Post']; // Blog uses Post model

        $seoFields = ['title', 'content', 'description', 'body', 'excerpt', 'summary', 'slug'];

        foreach ($models as $model) {
            $modelClass = "App\\Models\\{$model}";

            if (class_exists($modelClass)) {
                $modelClass::created(function ($instance) {
                    $this->generateSEOForModel($instance);
                });

                $modelClass::updated(function ($instance) use ($seoFields) {
                    if ($instance->isDirty($seoFields)) {
                        $this->generateSEOForModel($instance);
                    }
                });
            }
        }
    }

    private function generateSEOForModel($model)
    {
        try {
            $content = $this->extractModelContent($model);
            
            dispatch(new \App\Jobs\GenerateSEOContent(
                get_class($model),
                $model->id,
                $this->getContentType($model),
                $content
            ));
        } catch (\Exception $e) {
            \Log::error('Auto SEO generation failed', [
                'model' => get_class($model),
                'id' => $model->id,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function extractModelContent($model): array
    {
        $content = [];
        
        // Extract common fields
        $fields = ['title', 'description', 'content', 'body', 'excerpt', 'summary'];
        
        foreach ($fields as $field) {
            if (isset($model->{$field}) && is_string($model->{$field})) {
                $content[$field] = $model->{$field};
            }
        }
        
        // Add model-specific content
        if (method_exists($model, 'getSEOContent')) {
            $content = array_merge($content, $model->getSEOContent());
        }
        
        return $content;
    }

    private function getContentType($model): string
    {
        $className = class_basename($model);
        
        return match ($className) {
            'Auction' => 'auction',
            'Page' => 'page',
            'Blog' => 'blog',
            'Post' => 'blog',
            default => 'page'
        };
    }
}
