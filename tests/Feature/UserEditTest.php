<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

function editor(string $name, string $sigla = 'ADMIN'): User
{
    $role = Role::firstOrCreate(['sigla' => $sigla], ['name' => $sigla]);

    return User::query()->create(['name' => $name, 'username' => $name, 'email' => "$name@example.com", 'password' => 'Senha@123', 'password_verify' => true, 'is_active' => true, 'role_id' => $role->id]);
}

it('lets a user edit their own record without sending a role', function () {
    Gate::before(fn () => true);
    $admin = editor('admin');

    $this->actingAs($admin)->put(route('user.update', $admin), ['name' => 'Novo Nome', 'email' => 'admin@example.com', 'username' => 'admin'])
        ->assertSessionHasNoErrors();

    expect($admin->fresh()->name)->toBe('Novo Nome');
});

it('treats an email that differs only by case as a duplicate', function () {
    Gate::before(fn () => true);
    $admin = editor('admin');
    $role = Role::firstOrCreate(['sigla' => 'EDITOR'], ['name' => 'EDITOR']);
    editor('maria', 'EDITOR');

    $this->actingAs($admin)->post(route('user.store'), ['name' => 'Outra', 'email' => 'MARIA@Example.com', 'username' => 'outra', 'role_id' => $role->id])
        ->assertSessionHasErrors('email');

    expect(User::where('username', 'outra')->exists())->toBeFalse();
});
