<?php

namespace App\Listeners;

use App\Events\NotificationSent;
use App\Jobs\SendWebPushForUser;

class QueueWebPushOnNotificationSent
{
    public function handle(NotificationSent $event): void
    {
        SendWebPushForUser::dispatch(
            userId: $event->userId,
            notificationId: $event->notificationId,
            payload: $event->payload
        )->onQueue('push');
    }
}
