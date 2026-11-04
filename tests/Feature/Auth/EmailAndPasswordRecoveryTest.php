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
