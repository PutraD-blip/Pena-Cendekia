<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class NotificationService
{
    public static function send(
        int $userId,
        string $title,
        string $message,
        string $type = 'info',
        ?string $relatedType = null,
        ?int $relatedId = null
    ): Notification {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'related_type' => $relatedType,
            'related_id' => $relatedId,
            'is_read' => false,
        ]);
    }

    public static function notifyAdmins(string $title, string $message, string $type = 'info', ?string $relatedType = null, ?int $relatedId = null): void
    {
        $admins = User::where('role', 'admin')->where('is_active', true)->get();
        foreach ($admins as $admin) {
            self::send($admin->id, $title, $message, $type, $relatedType, $relatedId);
        }
    }
}
