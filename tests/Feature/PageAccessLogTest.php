<?php

use App\Http\Middleware\EnsureFirstAccessPassword;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not store query parameters in page access logs', function () {
    $user = User::query()->create([
        'name' => 'Usuário de teste',
        'username' => 'usuario.teste',
        'email' => 'usuario.teste@example.com',
        'password' => 'Senha@12345',
        'password_verify' => true,
        'is_active' => true,
    ]);

    $this->withoutMiddleware(EnsureFirstAccessPassword::class)
        ->actingAs($user)
        ->get('/home?busca=dado-privado')
        ->assertOk();

    $this->assertDatabaseHas('page_access_logs', [
        'user_id' => $user->id,
        'full_url' => url('/home'),
    ]);
    $this->assertDatabaseMissing('page_access_logs', [
        'full_url' => url('/home?busca=dado-privado'),
    ]);
});
