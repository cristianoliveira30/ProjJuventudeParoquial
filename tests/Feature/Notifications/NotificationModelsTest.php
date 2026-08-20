<?php

use App\Models\Notification;
use App\Models\NotificationDeliveries;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

it('defines the notification creator relationship', function () {
    $relation = (new Notification())->creator();

    expect($relation)->toBeInstanceOf(BelongsTo::class)
        ->and($relation->getRelated())->toBeInstanceOf(User::class)
        ->and($relation->getForeignKeyName())->toBe('created_by')
        ->and($relation->getOwnerKeyName())->toBe('id');
});

it('defines delivery relationships to notification and user', function () {
    $delivery = new NotificationDeliveries();

    $notificationRelation = $delivery->notification();
    $userRelation = $delivery->user();

    expect($notificationRelation)->toBeInstanceOf(BelongsTo::class)
        ->and($notificationRelation->getRelated())->toBeInstanceOf(Notification::class)
        ->and($notificationRelation->getForeignKeyName())->toBe('notification_id')
        ->and($notificationRelation->getOwnerKeyName())->toBe('id')
        ->and($userRelation)->toBeInstanceOf(BelongsTo::class)
        ->and($userRelation->getRelated())->toBeInstanceOf(User::class)
        ->and($userRelation->getForeignKeyName())->toBe('user_id')
        ->and($userRelation->getOwnerKeyName())->toBe('id');
});

it('casts delivery timestamps as date values', function () {
    $delivery = new NotificationDeliveries();
    $delivery->forceFill([
        'read_at' => '2026-06-19 10:20:00',
        'delivered_at' => '2026-06-19 10:00:00',
    ]);

    expect($delivery->read_at->format('Y-m-d H:i:s'))->toBe('2026-06-19 10:20:00')
        ->and($delivery->delivered_at->format('Y-m-d H:i:s'))->toBe('2026-06-19 10:00:00');
});
