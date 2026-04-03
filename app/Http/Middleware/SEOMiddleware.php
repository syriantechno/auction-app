<?php

namespace App\Http\Middleware;

use App\Models\SEOData;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class SEOMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Only apply to HTML responses
        if ($request->isMethod('GET') && 
            $response->isSuccessful() && 
            str_contains($response->headers->get('Content-Type'), 'text/html')) {
            
            $this->injectSEOData($request, $response);
        }
        
        return $response;
    }

    private function injectSEOData(Request $request, $response)
    {
        $url = $request->fullUrl();
        $path = $request->path();
        
        // Try to find SEO data for this URL
        $seoData = $this->findSEOData($url, $path);
        
        if ($seoData) {
            $content = $response->getContent();
            
            // Inject meta tags
            if ($seoData->meta_title || $seoData->meta_description) {
                $content = $this->injectMetaTags($content, $seoData);
            }
            
            // Inject structured data
            if ($seoData->structured_data) {
                $content = $this->injectStructuredData($content, $seoData);
            }
            
            $response->setContent($content);
        }
    }

    private function findSEOData(string $url, string $path): ?SEOData
    {
        // Try to find by URL pattern
        $seoData = $this->findSEOBypattern($url, $path);
        
        if ($seoData) {
            return $seoData;
        }
        
        // Try to find by model
        return $this->findSEOByModel($path);
    }

    private function findSEOBypattern(string $url, string $path): ?SEOData
    {
        // Check for exact URL match
        $seoData = SEOData::where('meta_title', 'like', "%{$path}%")
            ->orWhere('meta_description', 'like', "%{$path}%")
            ->first();
            
        return $seoData;
    }

    private function findSEOByModel(string $path): ?SEOData
    {
        // Check for auction pages
        if (str_contains($path, 'auctions/')) {
            $id = $this->extractIdFromPath($path);
            if ($id) {
                return SEOData::where('model_type', 'App\\Models\\Auction')
                    ->where('model_id', $id)
                    ->first();
            }
        }
        
        // Check for pages
        if ($path === '/' || $path === 'home') {
            return SEOData::where('model_type', 'App\\Models\\Page')
                ->where('model_id', function($query) {
                    $query->select('id')
                        ->from('pages')
                        ->where('slug', 'home')
                        ->limit(1);
                })
                ->first();
        }
        
        return null;
    }

    private function extractIdFromPath(string $path): ?int
    {
        $parts = explode('/', $path);
        $id = end($parts);
        
        return is_numeric($id) ? (int) $id : null;
    }

    private function injectMetaTags(string $content, SEOData $seoData): string
    {
        $metaTags = '';
        
        if ($seoData->meta_title) {
            $metaTags .= "<title>{$seoData->meta_title}</title>\n";
            $metaTags .= '<meta property="og:title" content="' . htmlspecialchars($seoData->meta_title) . '">' . "\n";
        }
        
        if ($seoData->meta_description) {
            $metaTags .= '<meta name="description" content="' . htmlspecialchars($seoData->meta_description) . '">' . "\n";
            $metaTags .= '<meta property="og:description" content="' . htmlspecialchars($seoData->meta_description) . '">' . "\n";
        }
        
        if ($seoData->meta_keywords && !empty($seoData->meta_keywords)) {
            $metaTags .= '<meta name="keywords" content="' . implode(', ', $seoData->meta_keywords) . '">' . "\n";
        }
        
        // Inject after <head> or before </head>
        if (str_contains($content, '<head>')) {
            return str_replace('<head>', '<head>' . "\n" . $metaTags, $content);
        }
        
        return $content;
    }

    private function injectStructuredData(string $content, SEOData $seoData): string
    {
        if (empty($seoData->structured_data)) {
            return $content;
        }
        
        $json = json_encode($seoData->structured_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $structuredData = "\n<script type=\"application/ld+json\">\n{$json}\n</script>\n";
        
        // Inject before </head>
        if (str_contains($content, '</head>')) {
            return str_replace('</head>', $structuredData . '</head>', $content);
        }
        
        return $content;
    }
}
