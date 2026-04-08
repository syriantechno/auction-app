<?php

namespace App\Observers;

use App\Models\CMS\Page;
use App\Services\AISEOService;
use Illuminate\Support\Facades\Log;

class PageObserver
{
    protected $seoService;

    public function __construct(AISEOService $seoService)
    {
        $this->seoService = $seoService;
    }

    /**
     * Handle the Page "created" event.
     */
    public function created(Page $page): void
    {
        if ($page->is_published) {
            $this->generateAiMeta($page);
        }
    }

    /**
     * Handle the Page "updated" event.
     */
    public function updated(Page $page): void
    {
        // Only regenerate if title or content changed and we don't have SEO yet
        if ($page->isDirty(['title', 'content']) || empty($page->seo_description)) {
            if ($page->is_published) {
                $this->generateAiMeta($page);
            }
        }
    }

    /**
     * Generate SEO Meta using AI
     */
    public function generateAiMeta(Page $page): void
    {
        try {
            // Prepare content for AI analysis
            $contentBody = is_array($page->content) ? json_encode($page->content) : $page->content;
            $contentText = "Page Title: {$page->title}. Content Summary: " . mb_substr(strip_tags($contentBody), 0, 1000);

            $meta = $this->seoService->generateMetaTags($contentText, 'cms_page');

            // Save without triggering observer recursion
            $page->withoutEvents(function () use ($page, $meta) {
                $page->update([
                    'seo_title' => $meta['title'] ?? $page->title,
                    'seo_description' => $meta['description'] ?? '',
                    'seo_keywords' => implode(', ', $meta['keywords'] ?? []),
                ]);
            });

            Log::info("Autonomous SEO: Generated meta for Page #{$page->id} ({$page->slug})");
        } catch (\Exception $e) {
            Log::error("Autonomous SEO Error for Page #{$page->id}: " . $e->getMessage());
        }
    }
}
