<?php

use App\Events\NotificationSent;
use App\Listeners\CreateNotificationDeliveries;
use App\Models\Notification;
use App\Models\Role;
use App\Models\User;
use App\Notifications\Events\NotificationCreated;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

it('creates one delivery when a recipient is selected directly and by role', function () {
    Event::fake([NotificationSent::class]);
    $role = Role::query()->create(['name' => 'Secretaria', 'sigla' => 'SEC']);
    $user = User::query()->create([
        'name' => 'Destinatária',
        'username' => 'destinataria',
        'email' => 'destinataria@example.com',
        'password' => 'Senha@12345',
        'password_verify' => true,
        'is_active' => true,
        'role_id' => $role->id,
    ]);
    $notification = Notification::query()->create([
        'title' => 'Aviso',
        'message' => 'Mensagem de teste',
        'created_by' => $user->id,
    ]);

    app(CreateNotificationDeliveries::class)->handle(new NotificationCreated(
        notificationId: $notification->id,
        senderId: $user->id,
        targets: ['users' => [$user->id], 'roles' => [$role->id]],
    ));

    $this->assertDatabaseCount('notification_deliveries', 1);
    $this->assertDatabaseHas('notification_deliveries', [
        'notification_id' => $notification->id,
        'user_id' => $user->id,
    ]);
    Event::assertDispatched(NotificationSent::class, fn (NotificationSent $event) => $event->userId === $user->id);
});
