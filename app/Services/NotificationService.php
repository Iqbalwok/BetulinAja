<?php

namespace App\Services;

use App\Models\AppNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Send in-app notification to a user
     */
    public static function send(
        int $userId,
        string $title,
        string $message,
        string $type = 'info',
        ?array $data = null
    ): AppNotification {
        return AppNotification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'data' => $data,
        ]);
    }

    /**
     * Mark notification as read
     */
    public static function markAsRead(int $notificationId, int $userId): bool
    {
        $notification = AppNotification::where('id', $notificationId)
            ->where('user_id', $userId)
            ->first();

        if ($notification && is_null($notification->read_at)) {
            $notification->update(['read_at' => now()]);
            return true;
        }

        return false;
    }

    /**
     * Mark all notifications as read for a user
     */
    public static function markAllAsRead(int $userId): int
    {
        return AppNotification::where('user_id', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }
}
