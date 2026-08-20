<?php

use App\Events\NotificationSent;
use Illuminate\Broadcasting\PrivateChannel;

it('broadcasts notifications on the user private channel', function () {
    $event = new NotificationSent(
        userId: 7,
        notificationId: 15,
        payload: [
            'title' => 'Aviso',
            'message' => 'Mensagem',
        ],
    );

    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(1)
        ->and($channels[0])->toBeInstanceOf(PrivateChannel::class)
        ->and((string) $channels[0])->toBe('private-user.7')
        ->and($event->broadcastAs())->toBe('notification.sent')
        ->and($event->broadcastWith())->toBe([
            'notification_id' => 15,
            'title' => 'Aviso',
            'message' => 'Mensagem',
        ]);
});
