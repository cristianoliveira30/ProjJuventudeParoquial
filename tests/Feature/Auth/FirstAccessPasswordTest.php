<?php

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

function createFirstAccessUser(array $attributes = []): User
{
    return User::query()->forceCreate(array_merge([
        'name' => 'Primeiro Acesso',
        'username' => 'primeiro.acesso',
        'email' => 'primeiro.acesso@example.com',
        'password' => Hash::make('Senha@123'),
        'password_verify' => false,
    ], $attributes));
}

it('updates the first access password and marks it as verified', function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The local PHP runtime does not have pdo_sqlite; this test runs under Sail.');
    }

    Schema::dropIfExists('users');
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->boolean('password_verify')->default(false);
        $table->timestamps();
    });

    $user = User::query()->forceCreate([
        'name' => 'Primeiro Acesso',
        'username' => 'primeiro.acesso',
        'email' => 'primeiro.acesso@example.com',
        'password' => Hash::make('Senha@123'),
        'password_verify' => false,
    ]);

    $this->actingAs($user)
        ->from(route('first.access'))
        ->put(route('user.profile.password.update'), [
            'password' => 'NovaSenha@123',
            'password_confirmation' => 'NovaSenha@123',
        ])
        ->assertRedirect(route('home'))
        ->assertSessionHas('success');

    $user->refresh();

    expect($user->password_verify)->toBeTrue()
        ->and(Hash::check('NovaSenha@123', $user->password))->toBeTrue();
});

it('rejects weak first access passwords', function () {
    $this->withoutMiddleware()
        ->from(route('first.access'))
        ->put(route('user.profile.password.update'), [
            'password' => 'simples',
            'password_confirmation' => 'simples',
        ])
        ->assertRedirect(route('first.access'))
        ->assertSessionHasErrors('password');
});

it('redirects verified users away from the first access screen', function () {
    $user = new User([
        'name' => 'Usuário Verificado',
        'username' => 'usuario.verificado',
        'email' => 'verificado@paroquia.test',
        'password_verify' => true,
        'is_active' => true,
    ]);
    $user->id = 10;

    $this->actingAs($user)
        ->get(route('first.access'))
        ->assertRedirect(route('account.security'));
});

it('does not let a verified user reuse the first access password endpoint', function () {
    $user = new User([
        'name' => 'Usuário Verificado',
        'username' => 'usuario.verificado',
        'email' => 'verificado@paroquia.test',
        'password_verify' => true,
        'is_active' => true,
    ]);
    $user->id = 11;

    $this->actingAs($user)
        ->put(route('user.profile.password.update'), [
            'password' => 'OutraSenha@123',
            'password_confirmation' => 'OutraSenha@123',
        ])
        ->assertForbidden();
});
