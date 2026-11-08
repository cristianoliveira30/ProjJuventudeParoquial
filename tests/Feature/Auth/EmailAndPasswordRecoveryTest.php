<?php

use App\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires first access users to confirm their email before choosing a password', function () {
    Notification::fake();
    $user = User::factory()->create(['password_verify' => false, 'email_verified_at' => null]);

    $this->post(route('login.post'), ['username' => $user->username, 'password' => 'password'])
        ->assertRedirect(route('email.verify'));

    Notification::assertSentTo($user, \App\Notifications\EmailVerificationCode::class);
});

it('sends a password reset link', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHas('status');

    Notification::assertSentTo($user, \Illuminate\Auth\Notifications\ResetPassword::class);
});

it('answers the same way for unknown emails so registered ones are not revealed', function () {
    Notification::fake();

    $this->post(route('password.email'), ['email' => 'ninguem@example.com'])
        ->assertSessionHas('status')
        ->assertSessionHasNoErrors();
});

it('limits email verification code attempts', function () {
    $user = User::factory()->create(['password_verify' => false, 'email_verified_at' => null]);

    foreach (range(1, 5) as $i) {
        $this->actingAs($user)->post(route('email.verify.submit'), ['code' => '000000'])->assertSessionHasErrors('code');
    }

    $this->actingAs($user)->post(route('email.verify.submit'), ['code' => '000000'])->assertStatus(429);
});
