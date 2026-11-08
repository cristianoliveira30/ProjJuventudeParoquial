<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('rejects push endpoints that are not from a known push service', function (string $endpoint) {
    $this->actingAs(User::factory()->create())
        ->postJson(route('webpush.subscribe'), ['endpoint' => $endpoint, 'keys' => ['p256dh' => 'a', 'auth' => 'b']])
        ->assertUnprocessable();
})->with(['http://127.0.0.1/x', 'https://169.254.169.254/latest', 'https://evil.example/fcm.googleapis.com/']);

it('accepts a real push service endpoint', function () {
    $this->actingAs(User::factory()->create())
        ->postJson(route('webpush.subscribe'), ['endpoint' => 'https://fcm.googleapis.com/fcm/send/abc', 'keys' => ['p256dh' => 'a', 'auth' => 'b']])
        ->assertOk();
});
