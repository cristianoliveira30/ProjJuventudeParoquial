<?php

use App\Notifications\Events\NotificationCreated;

it('carries notification creation data for listeners', function () {
    $event = new NotificationCreated(
        notificationId: 10,
        senderId: 3,
        targets: [
            'users' => [1, 2],
            'roles' => [5],
        ],
    );

    expect($event->notificationId)->toBe(10)
        ->and($event->senderId)->toBe(3)
        ->and($event->targets)->toBe([
            'users' => [1, 2],
            'roles' => [5],
        ]);
});
