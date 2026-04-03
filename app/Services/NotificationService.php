<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Send a notification through multiple channels.
     */
    public function notify(User $user, string $message, string $type = 'general', array $data = []): void
    {
        $this->sendEmail($user, $message, $type, $data);
        $this->sendWhatsApp($user, $message, $type, $data);
    }

    /**
     * Send Email notification.
     */
    public function sendEmail(User $user, string $message, string $type, array $data): void
    {
        // For now, logging. In production, use Mail::to($user)->send(...)
        Log::info("Email sent to {$user->email}: {$message}");
        
        // Example:
        // Mail::to($user->email)->send(new \App\Mail\GenericNotification($message, $type, $data));
    }

    /**
     * Send WhatsApp notification.
     */
    public function sendWhatsApp(User $user, string $message, string $type, array $data): void
    {
        // Placeholder for WhatsApp API (e.g., Twilio, UltraMsg, etc.)
        Log::info("WhatsApp sent to {$user->phone}: {$message}");
        
        // Example logic for a provider:
        // $this->whatsAppProvider->sendMessage($user->phone, $message);
    }

    /**
     * Large scale notification for all bidders in an auction.
     */
    public function notifyBidders(\App\Models\Auction $auction, string $message): void
    {
        $bidders = $auction->bids()->with('user')->get()->pluck('user')->unique('id');

        foreach ($bidders as $user) {
            $this->notify($user, $message, 'auction_update');
        }
    }
}
