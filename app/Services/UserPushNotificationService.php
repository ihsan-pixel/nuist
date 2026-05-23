<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;

class UserPushNotificationService
{
    public function __construct(
        private readonly FcmPushService $fcmPushService
    ) {
    }

    public function notifyUser(
        User $user,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): Notification {
        $notification = Notification::create([
            'user_id' => $user->id,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);

        $pushData = array_merge($data, [
            'notification_id' => $notification->id,
            'type' => $type,
        ]);

        $this->fcmPushService->sendToUser($user, $title, $message, $pushData);

        return $notification;
    }

    public function notifyUserId(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = []
    ): ?Notification {
        $user = User::query()->find($userId);

        if (!$user) {
            return null;
        }

        return $this->notifyUser($user, $type, $title, $message, $data);
    }
}
