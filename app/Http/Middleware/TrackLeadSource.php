<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackLeadSource
{
    /**
     * Detect lead source from referrer or UTM parameters
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only track on first visit (no existing session source)
        if (!session()->has('lead_source')) {
            $source = $this->detectSource($request);
            session(['lead_source' => $source]);
            session(['lead_source_detected_at' => now()]);
            
            // Debug logging
            \Log::info('[Lead Source] Detected: ' . $source . ' | Referrer: ' . ($request->headers->get('referer') ?? 'none') . ' | Session: ' . session()->getId());
        }

        // Also check for explicit UTM parameters on every request
        if ($utmSource = $request->query('utm_source')) {
            $mappedSource = $this->mapUtmSource($utmSource);
            session(['lead_source' => $mappedSource]);
            \Log::info('[Lead Source] UTM override: ' . $mappedSource);
        }

        return $next($request);
    }

    /**
     * Detect source from referrer URL
     */
    private function detectSource(Request $request): string
    {
        $referrer = $request->headers->get('referer') ?? '';
        $host = parse_url($referrer, PHP_URL_HOST) ?? '';
        $host = strtolower($host);

        // Check for social media
        if (str_contains($host, 'facebook.com') || str_contains($host, 'fb.com')) {
            return 'facebook';
        }
        if (str_contains($host, 'instagram.com')) {
            return 'instagram';
        }
        if (str_contains($host, 'linkedin.com')) {
            return 'linkedin';
        }
        if (str_contains($host, 'twitter.com') || str_contains($host, 'x.com')) {
            return 'twitter';
        }
        if (str_contains($host, 'tiktok.com')) {
            return 'tiktok';
        }
        if (str_contains($host, 'snapchat.com')) {
            return 'snapchat';
        }

        // Check for search engines
        if (str_contains($host, 'google.com') || str_contains($host, 'google.')) {
            return 'google';
        }
        if (str_contains($host, 'bing.com')) {
            return 'bing';
        }
        if (str_contains($host, 'yahoo.com')) {
            return 'yahoo';
        }
        if (str_contains($host, 'duckduckgo.com')) {
            return 'duckduckgo';
        }

        // Check for messaging apps
        if (str_contains($host, 'whatsapp.com') || str_contains($referrer, 'whatsapp')) {
            return 'whatsapp';
        }
        if (str_contains($host, 'telegram.org') || str_contains($host, 't.me')) {
            return 'telegram';
        }

        // Email marketing
        if (str_contains($referrer, 'mail') || str_contains($referrer, 'email')) {
            return 'email';
        }

        // If has referrer but not recognized, it's referral
        if (!empty($host) && !str_contains($host, $request->getHost())) {
            return 'referral';
        }

        // Direct traffic (no referrer)
        return 'direct';
    }

    /**
     * Map UTM source to internal source
     */
    private function mapUtmSource(string $utmSource): string
    {
        $utmSource = strtolower($utmSource);

        $mapping = [
            'facebook' => 'facebook',
            'fb' => 'facebook',
            'instagram' => 'instagram',
            'ig' => 'instagram',
            'google' => 'google',
            'g' => 'google',
            'bing' => 'bing',
            'linkedin' => 'linkedin',
            'twitter' => 'twitter',
            'x' => 'twitter',
            'tiktok' => 'tiktok',
            'whatsapp' => 'whatsapp',
            'wa' => 'whatsapp',
            'telegram' => 'telegram',
            'email' => 'email',
            'newsletter' => 'email',
            'referral' => 'referral',
            'affiliate' => 'referral',
            'direct' => 'direct',
            'none' => 'direct',
            'organic' => 'google',
            'ppc' => 'google',
            'cpc' => 'google',
        ];

        return $mapping[$utmSource] ?? 'other';
    }
}
