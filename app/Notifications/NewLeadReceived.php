<?php

namespace App\Notifications;

use App\Models\Lead;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NewLeadReceived extends Notification implements ShouldBroadcast
{
    // No Queueable — sync is safer with QUEUE_CONNECTION=sync
    // Store plain data instead of model to avoid serialization issues
    private array $leadData;
    private int   $leadId;

    public function __construct(Lead $lead)
    {
        $this->leadId = $lead->id ?? 0;
        $raw = $lead->car_details;
        $this->leadData = match(true) {
            is_array($raw)  => $raw,
            is_string($raw) => json_decode($raw, true) ?? [],
            default         => [],
        };
    }

    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase(object $notifiable): array
    {
        $name = data_get($this->leadData, 'name', 'Someone');
        $year = data_get($this->leadData, 'year', '');
        $make = data_get($this->leadData, 'make', '');
        $model = data_get($this->leadData, 'model', '');

        // Build URL — try admin.leads.show, fallback to index
        try {
            $url = $this->leadId > 0
                ? route('admin.leads.show', $this->leadId)
                : route('admin.leads.index');
        } catch (\Throwable) {
            $url = url('/admin/leads');
        }

        return [
            'type'    => 'new_lead',
            'title'   => 'New Lead Received',
            'message' => trim("{$name} wants to sell a {$year} {$make} {$model}."),
            'url'     => $url,
            'icon'    => 'user-round-plus',
            'color'   => 'orange',
            'lead_id' => $this->leadId,
        ];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toDatabase($notifiable));
    }

    public function broadcastOn(): array
    {
        return [
            new \Illuminate\Broadcasting\PrivateChannel('App.Models.User.' . $this->leadId), // WRONG, I will use custom channel soon
        ];
    }
}
