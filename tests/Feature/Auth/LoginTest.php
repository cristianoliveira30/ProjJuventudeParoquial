<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;

function createLoginUser(array $attributes = []): User
{
    $user = new User(array_merge([
        'name' => 'Usuário Teste',
        'username' => 'usuario.teste',
        'email' => 'usuario.teste@example.com',
        'password_verify' => true,
    ], $attributes));

    $user->id = 1;

    return $user;
}

it('redirects a verified user to the parish panel after login', function () {
    $user = createLoginUser();

    Auth::shouldReceive('attempt')
        ->once()
        ->with(['username' => $user->username, 'password' => 'Senha@123'])
        ->andReturnTrue();

    Auth::shouldReceive('user')
        ->once()
        ->andReturn($user);

    $this->from('/login')
        ->post(route('login.post'), [
            'username' => $user->username,
            'password' => 'Senha@123',
        ])
        ->assertRedirect(route('home'));
});

it('redirects a first access user to the first access password screen', function () {
    $user = createLoginUser(['password_verify' => false]);

    Auth::shouldReceive('attempt')
        ->once()
        ->with(['username' => $user->username, 'password' => 'Senha@123'])
        ->andReturnTrue();

    Auth::shouldReceive('user')
        ->once()
        ->andReturn($user);

    $this->from('/login')
        ->post(route('login.post'), [
            'username' => $user->username,
            'password' => 'Senha@123',
        ])
        ->assertRedirect(route('first.access'));
});

it('rejects invalid credentials', function () {
    Auth::shouldReceive('attempt')
        ->once()
        ->with(['username' => 'usuario.teste', 'password' => 'senha-errada'])
        ->andReturnFalse();

    $this->from('/login')
        ->post(route('login.post'), [
            'username' => 'usuario.teste',
            'password' => 'senha-errada',
        ])
        ->assertRedirect('/login')
        ->assertSessionHasErrors('login');
});

it('requires username and password', function () {
    $this->from('/login')
        ->post(route('login.post'), [])
        ->assertRedirect('/login')
        ->assertSessionHasErrors(['username', 'password']);
});
