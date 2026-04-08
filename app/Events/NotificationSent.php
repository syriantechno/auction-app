<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $notificationData;
    public User $user;
    public array $targetRoles;

    /**
     * Create a new event instance.
     *
     * @param User $user User who received the notification
     * @param array $notificationData The notification data
     * @param array $targetRoles Target roles for this notification (if role-based)
     */
    public function __construct(User $user, array $notificationData, array $targetRoles = [])
    {
        $this->user = $user;
        $this->notificationData = $notificationData;
        $this->targetRoles = $targetRoles;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [
            new PrivateChannel('notifications.' . $this->user->id),
        ];

        // Also broadcast to role channels if specified
        foreach ($this->targetRoles as $role) {
            $channels[] = new PrivateChannel('role.' . $role);
        }

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'notification.sent';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notificationData['id'] ?? uniqid(),
            'title' => $this->notificationData['title'] ?? 'Notification',
            'message' => $this->notificationData['message'] ?? '',
            'url' => $this->notificationData['url'] ?? null,
            'icon' => $this->notificationData['icon'] ?? 'bell',
            'color' => $this->notificationData['color'] ?? 'orange',
            'type' => $this->notificationData['type'] ?? 'general',
            'created_at' => now()->diffForHumans(),
            'target_roles' => $this->targetRoles,
        ];
    }
}
