<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Generate dynamic XML sitemap
     */
    public function generate()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

        // 1. Home Page
        $xml .= $this->createUrlElement(route('home'), now(), 'daily', '1.0');

        // 2. Auctions Page
        $xml .= $this->createUrlElement(route('auctions.index'), now(), 'hourly', '0.9');

        // 3. Dynamic Auctions
        $auctions = Auction::where('status', 'active')->orderBy('updated_at', 'desc')->get();
        foreach ($auctions as $auction) {
            $xml .= $this->createUrlElement(
                route('auctions.show', $auction),
                $auction->updated_at,
                'hourly',
                '0.8'
            );
        }

        // 4. Blog Posts
        $posts = Post::where('is_published', true)->orderBy('updated_at', 'desc')->get();
        foreach ($posts as $post) {
            $xml .= $this->createUrlElement(
                route('blog.show', $post->slug),
                $post->updated_at ?? now(),
                'weekly',
                '0.7'
            );
        }

        // 5. CMS Pages
        $pages = Page::where('is_published', true)->orderBy('updated_at', 'desc')->get();
        foreach ($pages as $page) {
            $xml .= $this->createUrlElement(
                route('page.show', $page->slug),
                $page->updated_at ?? now(),
                'monthly',
                '0.6'
            );
        }

        $xml .= '
</urlset>';

        return response($xml, 200)
            ->header('Content-Type', 'application/xml');
    }

    private function createUrlElement($url, $lastMod, $changeFreq, $priority)
    {
        $lastModFormatted = is_string($lastMod) ? $lastMod : $lastMod->toAtomString();
        return "
    <url>
        <loc>{$url}</loc>
        <lastmod>{$lastModFormatted}</lastmod>
        <changefreq>{$changeFreq}</changefreq>
        <priority>{$priority}</priority>
    </url>";
    }
}
