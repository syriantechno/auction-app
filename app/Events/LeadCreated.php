<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Lead $lead;
    public array $leadData;

    /**
     * Create a new event instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
        $this->leadData = [
            'id' => $lead->id,
            'status' => $lead->status,
            'notes' => $lead->notes,
            'car_details' => $lead->car_details,
            'created_at' => $lead->created_at->diffForHumans(),
            'created_at_full' => $lead->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Channel for all admins who can view leads
            new PrivateChannel('leads.admin'),
            // Channel for secretaries
            new PrivateChannel('leads.secretary'),
            // Channel for super admins
            new PrivateChannel('leads.super'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'lead.created';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'lead' => $this->leadData,
            'message' => 'New lead received from ' . data_get($this->lead->car_details, 'name', 'Anonymous'),
            'title' => '🚗 New Lead: ' . data_get($this->lead->car_details, 'make') . ' ' . data_get($this->lead->car_details, 'model'),
            'url' => route('admin.leads.show', $this->lead->id),
        ];
    }
}
