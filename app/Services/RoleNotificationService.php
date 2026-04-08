<?php

namespace App\Services;

use App\Models\User;
use App\Events\NotificationSent;
use Illuminate\Notifications\Notification;
use Spatie\Permission\Models\Role;

class RoleNotificationService
{
    /**
     * Send notification to all users with a specific role
     */
    public static function notifyRole(
        string $roleName,
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'bell',
        string $color = 'orange',
        string $type = 'general',
        bool $broadcast = true  // ← NEW: real-time broadcasting
    ): int {
        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return 0;
        }

        $users = User::role($roleName)->get();
        $count = 0;

        foreach ($users as $user) {
            $notificationData = [
                'title' => $title,
                'message' => $message,
                'url' => $url,
                'icon' => $icon,
                'color' => $color,
                'type' => $type,
            ];

            // Save to database
            $user->notify(new \App\Notifications\RoleBasedNotification($notificationData));

            // Broadcast in real-time
            if ($broadcast) {
                broadcast(new NotificationSent($user, $notificationData, [$roleName]))->toOthers();
            }

            $count++;
        }

        return $count;
    }

    /**
     * Send notification to multiple roles at once
     */
    public static function notifyRoles(
        array $roleNames,
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'bell',
        string $color = 'orange',
        string $type = 'general',
        bool $broadcast = true
    ): int {
        $totalCount = 0;

        foreach ($roleNames as $roleName) {
            $totalCount += self::notifyRole($roleName, $title, $message, $url, $icon, $color, $type, $broadcast);
        }

        return $totalCount;
    }

    /**
     * Send notification to Super Admins only
     */
    public static function notifyAdmins(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'shield',
        string $color = 'red'
    ): int {
        return self::notifyRoles(
            ['super-admin', 'admin'],
            $title,
            $message,
            $url,
            $icon,
            $color,
            'admin_alert'
        );
    }

    /**
     * Send notification to HR Managers
     */
    public static function notifyHR(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'users'
    ): int {
        return self::notifyRole(
            'hr-manager',
            $title,
            $message,
            $url,
            $icon,
            'blue',
            'hr_notification'
        );
    }

    /**
     * Send notification to Finance/Accounts Managers
     */
    public static function notifyFinance(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'banknote'
    ): int {
        return self::notifyRole(
            'accounts-manager',
            $title,
            $message,
            $url,
            $icon,
            'green',
            'finance_notification'
        );
    }

    /**
     * Send notification to Dealers
     */
    public static function notifyDealers(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'car'
    ): int {
        return self::notifyRole(
            'dealer',
            $title,
            $message,
            $url,
            $icon,
            'orange',
            'dealer_notification'
        );
    }

    /**
     * Send notification to Inspectors
     */
    public static function notifyInspectors(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'search'
    ): int {
        return self::notifyRole(
            'inspector',
            $title,
            $message,
            $url,
            $icon,
            'purple',
            'inspection_notification'
        );
    }

    /**
     * Send notification to Secretaries
     */
    public static function notifySecretaries(
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'calendar'
    ): int {
        return self::notifyRole(
            'secretary',
            $title,
            $message,
            $url,
            $icon,
            'pink',
            'secretary_notification'
        );
    }

    /**
     * Notify specific user + their role members (for escalation)
     */
    public static function notifyWithEscalation(
        User $primaryUser,
        array $escalationRoles,
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'alert-triangle',
        string $color = 'red'
    ): void {
        // Notify primary user
        $notificationData = [
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'icon' => $icon,
            'color' => $color,
            'type' => 'escalation',
        ];

        $primaryUser->notify(new \App\Notifications\RoleBasedNotification($notificationData));
        
        // Broadcast to primary user
        broadcast(new NotificationSent($primaryUser, $notificationData, $escalationRoles));

        // Notify escalation roles
        foreach ($escalationRoles as $role) {
            self::notifyRole($role, "[ escalation ] {$title}", $message, $url, $icon, $color, 'escalation');
        }
    }

    /**
     * Send notification to a specific user (private)
     */
    public static function notifyUser(
        User $user,
        string $title,
        string $message,
        ?string $url = null,
        string $icon = 'bell',
        string $color = 'orange',
        string $type = 'general'
    ): void {
        $notificationData = [
            'title' => $title,
            'message' => $message,
            'url' => $url,
            'icon' => $icon,
            'color' => $color,
            'type' => $type,
        ];

        // Save to database
        $user->notify(new \App\Notifications\RoleBasedNotification($notificationData));

        // Broadcast in real-time
        broadcast(new NotificationSent($user, $notificationData, []));
    }
}
