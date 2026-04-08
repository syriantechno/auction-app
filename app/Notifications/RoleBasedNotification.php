<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class RoleBasedNotification extends Notification
{
    use Queueable;

    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toDatabase($notifiable): array
    {
        return [
            'title' => $this->data['title'] ?? 'Notification',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? null,
            'icon' => $this->data['icon'] ?? 'bell',
            'color' => $this->data['color'] ?? 'orange',
            'type' => $this->data['type'] ?? 'general',
        ];
    }
}
