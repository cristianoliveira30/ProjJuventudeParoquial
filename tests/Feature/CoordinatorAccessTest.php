<?php

use App\Models\Retreat;
use App\Models\RetreatRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

function coordinator(string $name): User
{
    $role = Role::firstOrCreate(['sigla' => 'COORD'], ['name' => 'Coordenador Pastoral']);

    return User::query()->create(['name' => $name, 'username' => $name, 'email' => "$name@example.com", 'password' => 'Senha@123', 'password_verify' => true, 'is_active' => true, 'role_id' => $role->id]);
}

it('blocks a coordinator from managing another coordinator retreat', function () {
    Gate::before(fn () => true); // isola a regra de dono: a permissão por perfil é testada em outro lugar
    $owner = coordinator('dono');
    $other = coordinator('outro');
    $retreat = Retreat::query()->create(['title' => 'Retiro', 'slug' => 'retiro', 'description' => 'x', 'starts_at' => now()->addMonth(), 'coordinator_id' => $owner->id]);

    foreach ([
        ['get', route('retreats.registrations.index', $retreat)],
        ['get', route('retreats.servants.index', $retreat)],
        ['get', route('retreats.ministries.index', $retreat)],
        ['get', route('retreats.servant-link.show', $retreat)],
        ['get', route('retreats.edit', $retreat)],
    ] as [$method, $url]) {
        $this->actingAs($other)->$method($url)->assertForbidden();
        $this->actingAs($owner)->$method($url)->assertOk();
    }
});
