<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Send a WhatsApp message to a given phone number.
     *
     * @param  string  $to    Phone in international format: +9665xxxxxxxx
     * @param  string  $body  Message text
     * @return bool
     */
    public function send(string $to, string $body): bool
    {
        // Read provider from DB settings (overrides .env if set by admin)
        $provider  = SystemSetting::get('whatsapp_provider',   env('WHATSAPP_PROVIDER',   'twilio'));
        $apiUrl    = SystemSetting::get('whatsapp_api_url',    env('WHATSAPP_API_URL',    ''));
        $apiKey    = SystemSetting::get('whatsapp_api_key',    env('WHATSAPP_API_KEY',    ''));
        $apiSecret = SystemSetting::get('whatsapp_api_secret', env('WHATSAPP_API_SECRET', ''));
        $from      = SystemSetting::get('whatsapp_from',       env('WHATSAPP_FROM',       ''));

        if (empty($apiUrl) || empty($apiKey)) {
            Log::warning('[WhatsApp] API not configured — skipping send.');
            return false;
        }

        // Clean phone — ensure starts with +
        $to = preg_replace('/[^+\d]/', '', $to);
        if (!str_starts_with($to, '+')) {
            $to = '+' . $to;
        }

        try {
            return match($provider) {
                'twilio'  => $this->sendTwilio($apiUrl, $apiKey, $apiSecret, $from, $to, $body),
                'meta'    => $this->sendMeta($apiUrl, $apiKey, $from, $to, $body),
                default   => $this->sendGeneric($apiUrl, $apiKey, $from, $to, $body),
            };
        } catch (\Throwable $e) {
            Log::error('[WhatsApp] Send failed: ' . $e->getMessage(), ['to' => $to]);
            return false;
        }
    }

    /** Twilio WhatsApp (WhatsApp sandbox or production) */
    private function sendTwilio(
        string $apiUrl, string $sid, string $token,
        string $from, string $to, string $body
    ): bool {
        $response = Http::withBasicAuth($sid, $token)
            ->asForm()
            ->post($apiUrl, [
                'From' => str_starts_with($from, 'whatsapp:') ? $from : "whatsapp:{$from}",
                'To'   => str_starts_with($to, 'whatsapp:')   ? $to   : "whatsapp:{$to}",
                'Body' => $body,
            ]);

        Log::info('[WhatsApp/Twilio] Response: ' . $response->status(), [
            'to'   => $to,
            'body' => substr($body, 0, 80),
        ]);

        return $response->successful();
    }

    /** Meta WhatsApp Cloud API */
    private function sendMeta(
        string $apiUrl, string $token,
        string $phoneNumberId, string $to, string $body
    ): bool {
        $response = Http::withToken($token)
            ->post("{$apiUrl}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to'                => ltrim($to, '+'),
                'type'              => 'text',
                'text'              => ['body' => $body],
            ]);

        Log::info('[WhatsApp/Meta] Response: ' . $response->status(), ['to' => $to]);

        return $response->successful();
    }

    /** Generic HTTP POST — custom API */
    private function sendGeneric(
        string $apiUrl, string $apiKey,
        string $from, string $to, string $body
    ): bool {
        $response = Http::withHeaders([
            'Authorization' => "Bearer {$apiKey}",
            'Content-Type'  => 'application/json',
        ])->post($apiUrl, [
            'from'    => $from,
            'to'      => $to,
            'message' => $body,
        ]);

        Log::info('[WhatsApp/Generic] Response: ' . $response->status(), ['to' => $to]);

        return $response->successful();
    }
}
