<?php

use App\Models\User;

it('requires title and message when creating a notification', function () {
    $user = new User([
        'name' => 'Remetente',
        'username' => 'remetente',
        'email' => 'remetente@example.com',
        'password_verify' => true,
    ]);
    $user->id = 1;

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('notifications.dashboard'))
        ->post(route('notifications.create'), [])
        ->assertRedirect(route('notifications.dashboard'))
        ->assertSessionHasErrors(['title', 'message']);
});

it('requires at least one target when creating a notification', function () {
    $user = new User([
        'name' => 'Remetente',
        'username' => 'remetente',
        'email' => 'remetente@example.com',
        'password_verify' => true,
    ]);
    $user->id = 1;

    $this->withoutMiddleware()
        ->actingAs($user)
        ->from(route('notifications.dashboard'))
        ->post(route('notifications.create'), [
            'title' => 'Aviso',
            'message' => 'Mensagem de teste',
            'destino' => 'filtros',
        ])
        ->assertRedirect(route('notifications.dashboard'))
        ->assertSessionHasErrors('targets');
});

it('requires an endpoint when subscribing to web push', function () {
    $this->withoutMiddleware()
        ->postJson(route('webpush.subscribe'), [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors([
            'endpoint',
            'keys.p256dh',
            'keys.auth',
        ]);
});

it('returns unauthenticated when subscribing to web push without a user', function () {
    $this->withoutMiddleware()
        ->postJson(route('webpush.subscribe'), [
            'endpoint' => 'https://push.example/subscription',
            'keys' => [
                'p256dh' => 'public-key',
                'auth' => 'auth-secret',
            ],
        ])
        ->assertUnauthorized()
        ->assertExactJson(['message' => 'Unauthenticated']);
});
