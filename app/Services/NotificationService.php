<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    /**
     * Notify all admins with a specific permission
     */
    public static function notifyAdmins($notification, string $permission = 'telegram-chats.manage')
    {
        $admins = User::role('admin')->get();

        // If we want more granular control based on permissions
        if ($permission) {
            $admins = $admins->filter(fn($user) => $user->hasPermissionTo($permission));
        }

        Notification::send($admins, $notification);
    }
}
