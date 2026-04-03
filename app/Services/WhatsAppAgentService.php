<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\SEOSettings;

class WhatsAppAgentService
{
    private $apiKey;
    private $phoneNumber;
    private $baseUrl;

    public function __construct()
    {
        $settings = SEOSettings::getCurrent();
        $this->apiKey = $settings->whatsapp_agent_api_key;
        $this->phoneNumber = $settings->whatsapp_agent_phone;
        $this->baseUrl = 'https://api.whatsapp.com/v1'; // Replace with actual WhatsApp API URL
    }

    /**
     * Send SEO notification via WhatsApp
     */
    public function sendSEONotification(string $type, array $data): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $message = $this->buildSEOMessage($type, $data);
        return $this->sendMessage($message);
    }

    /**
     * Send new auction notification
     */
    public function sendNewAuctionNotification(array $auctionData): bool
    {
        $message = "🚗 *New Auction Created!*\n\n";
        $message .= "📋 Title: {$auctionData['title']}\n";
        $message .= "💰 Starting Price: {$auctionData['starting_price']}\n";
        $message .= "⏰ Ends: {$auctionData['end_time']}\n";
        $message .= "🔗 " . url("/auctions/{$auctionData['id']}") . "\n\n";
        $message .= "📊 SEO Score: {$auctionData['seo_score']}/100";

        return $this->sendMessage($message);
    }

    /**
     * Send SEO score alert
     */
    public function sendSEOScoreAlert(string $url, int $score, array $issues): bool
    {
        $message = "⚠️ *SEO Score Alert*\n\n";
        $message .= "📄 Page: {$url}\n";
        $message .= "📊 Score: {$score}/100\n";
        $message .= "🔧 Issues:\n";

        foreach (array_slice($issues, 0, 3) as $issue) {
            $message .= "• {$issue}\n";
        }

        if (count($issues) > 3) {
            $message .= "• And " . (count($issues) - 3) . " more issues...\n";
        }

        $message .= "\n🔗 " . url("/admin/seo/analyze?url=" . urlencode($url));

        return $this->sendMessage($message);
    }

    /**
     * Send indexing failure notification
     */
    public function sendIndexingFailureNotification(string $url, string $searchEngine, string $error): bool
    {
        $message = "❌ *Indexing Failed*\n\n";
        $message .= "🔍 Search Engine: {$searchEngine}\n";
        $message .= "📄 URL: {$url}\n";
        $message .= "⚠️ Error: {$error}\n\n";
        $message .= "🔧 Check admin panel for details";

        return $this->sendMessage($message);
    }

    /**
     * Send keyword ranking change notification
     */
    public function sendRankingChangeNotification(string $keyword, int $oldPosition, int $newPosition, string $url): bool
    {
        $trend = $newPosition < $oldPosition ? "📈" : "📉";
        $change = abs($newPosition - $oldPosition);

        $message = "{$trend} *Keyword Ranking Changed*\n\n";
        $message .= "🔍 Keyword: {$keyword}\n";
        $message .= "📊 From: #{$oldPosition} → #{$newPosition}\n";
        $message .= "📄 Page: {$url}\n";
        $message .= "📈 Change: {$change} positions\n\n";
        $message .= "🔗 " . url($url);

        return $this->sendMessage($message);
    }

    /**
     * Send daily SEO report
     */
    public function sendDailySEOReport(array $report): bool
    {
        $message = "📊 *Daily SEO Report*\n\n";
        $message .= "📅 Date: " . now()->format('Y-m-d') . "\n";
        $message .= "📄 Total Pages: {$report['total_pages']}\n";
        $message .= "✅ Optimized: {$report['optimized_pages']}\n";
        $message .= "🔍 Indexed: {$report['indexed_pages']}\n";
        $message .= "📊 Average Score: {$report['average_score']}/100\n";
        $message .= "⏳ Pending: {$report['pending_submissions']}\n\n";

        if (!empty($report['top_performers'])) {
            $message .= "🏆 Top Performers:\n";
            foreach (array_slice($report['top_performers'], 0, 3) as $page) {
                $message .= "• {$page['url']} ({$page['score']}/100)\n";
            }
        }

        if (!empty($report['issues_found'])) {
            $message .= "\n⚠️ Issues Found: " . count($report['issues_found']) . "\n";
        }

        $message .= "\n🔗 " . url("/admin/seo");

        return $this->sendMessage($message);
    }

    /**
     * Send bulk operation completion notification
     */
    public function sendBulkOperationNotification(string $operation, int $processed, int $successful, array $errors = []): bool
    {
        $message = "✅ *Bulk Operation Completed*\n\n";
        $message .= "🔧 Operation: {$operation}\n";
        $message .= "📊 Processed: {$processed}\n";
        $message .= "✅ Successful: {$successful}\n";
        $message .= "❌ Failed: " . ($processed - $successful) . "\n";

        if (!empty($errors)) {
            $message .= "\n⚠️ Sample Errors:\n";
            foreach (array_slice($errors, 0, 3) as $error) {
                $message .= "• {$error}\n";
            }
        }

        $message .= "\n🔗 " . url("/admin/seo");

        return $this->sendMessage($message);
    }

    /**
     * Send custom message
     */
    public function sendCustomMessage(string $message): bool
    {
        return $this->sendMessage($message);
    }

    /**
     * Send message via WhatsApp API
     */
    private function sendMessage(string $message): bool
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->phoneNumber,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if (!$response->successful()) {
                Log::error('WhatsApp API Error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                    'message' => $message
                ]);
                return false;
            }

            Log::info('WhatsApp message sent successfully', [
                'message' => substr($message, 0, 100) . '...'
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('WhatsApp Service Error', [
                'error' => $e->getMessage(),
                'message' => substr($message, 0, 100) . '...'
            ]);
            return false;
        }
    }

    /**
     * Build SEO message based on type
     */
    private function buildSEOMessage(string $type, array $data): string
    {
        return match ($type) {
            'new_auction'      => "🚗 *New Auction!*\n📋 {$data['title']}\n💰 Starting: {$data['starting_price']}\n⏰ Ends: {$data['end_time']}\n🔗 " . url("/auctions/{$data['id']}"),
            'seo_score_low'    => "⚠️ *SEO Low Score*\n📄 {$data['url']}\n📊 Score: {$data['score']}/100\n🔧 Issues: " . implode(', ', array_slice($data['issues'] ?? [], 0, 2)),
            'indexing_failed'  => "❌ *Indexing Failed*\n🔍 {$data['search_engine']}\n📄 {$data['url']}\n⚠️ {$data['error']}",
            'ranking_dropped'  => "📉 *Ranking Changed*\n🔍 {$data['keyword']}\n#{$data['old_position']} → #{$data['new_position']}\n📄 {$data['url']}",
            'daily_report'     => "📊 *Daily SEO Report*\n📅 " . now()->format('Y-m-d') . "\n📄 Pages: {$data['total_pages']}\n✅ Optimized: {$data['optimized_pages']}",
            'bulk_completed'   => "✅ *Bulk Op Done*\n🔧 {$data['operation']}\n📊 Processed: {$data['processed']}\n✅ OK: {$data['successful']}",
            default            => "🤖 SEO Notification: {$type}",
        };
    }

    /**
     * Test WhatsApp connection
     */
    public function testConnection(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        $testMessage = "🤖 *SEO Bot Test*\n\n✅ Connection successful!\n📅 " . now()->format('Y-m-d H:i:s');
        
        return $this->sendMessage($testMessage);
    }

    /**
     * Check if WhatsApp is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->phoneNumber);
    }

    /**
     * Get configuration status
     */
    public function getConfigurationStatus(): array
    {
        return [
            'configured' => $this->isConfigured(),
            'api_key_set' => !empty($this->apiKey),
            'phone_number_set' => !empty($this->phoneNumber),
            'phone_number' => $this->phoneNumber ? $this->maskPhoneNumber($this->phoneNumber) : null,
        ];
    }

    /**
     * Mask phone number for security
     */
    private function maskPhoneNumber(string $phone): string
    {
        if (strlen($phone) <= 4) {
            return $phone;
        }
        
        return substr($phone, 0, 2) . '*****' . substr($phone, -2);
    }

    /**
     * Send image message
     */
    public function sendImageMessage(string $imageUrl, string $caption = ''): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->phoneNumber,
                    'type' => 'image',
                    'image' => [
                        'link' => $imageUrl,
                        'caption' => $caption
                    ]
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp Image Send Error', [
                'error' => $e->getMessage(),
                'image_url' => $imageUrl
            ]);
            return false;
        }
    }

    /**
     * Send document message
     */
    public function sendDocumentMessage(string $documentUrl, string $filename, string $caption = ''): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Content-Type' => 'application/json',
                ])
                ->post("{$this->baseUrl}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $this->phoneNumber,
                    'type' => 'document',
                    'document' => [
                        'link' => $documentUrl,
                        'filename' => $filename,
                        'caption' => $caption
                    ]
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('WhatsApp Document Send Error', [
                'error' => $e->getMessage(),
                'document_url' => $documentUrl
            ]);
            return false;
        }
    }
}
