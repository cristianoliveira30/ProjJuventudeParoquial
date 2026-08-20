<?php

use App\Models\Retreat;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function servantLinkAdmin(): User
{
    $role = Role::query()->create(['name' => 'Administrador do link', 'sigla' => 'ADMIN']);

    return User::query()->create(['name' => 'Responsável', 'username' => 'responsavel.link', 'email' => 'responsavel.link@example.com', 'password' => 'Senha@123', 'password_verify' => true, 'is_active' => true, 'role_id' => $role->id]);
}

function servantLinkRetreat(array $attributes = []): Retreat
{
    return Retreat::query()->create([...['title' => 'Retiro com servos', 'slug' => 'retiro-com-servos', 'description' => 'Teste do link reservado.', 'starts_at' => now()->addMonth(), 'servant_registration_token' => Str::random(64)], ...$attributes]);
}

it('only opens a valid enabled servant link', function () {
    $retreat = servantLinkRetreat(['servant_registration_enabled' => true]);

    $this->get(route('portal.servants.link', $retreat->servant_registration_token))->assertOk()->assertSee('Enviar inscrição para servir');
    $this->get('/servir/'.Str::random(64))->assertNotFound();

    $retreat->update(['servant_registration_enabled' => false]);
    $this->get(route('portal.servants.link', $retreat->servant_registration_token))->assertOk()->assertSee('Acesso indisponível');
});

it('regenerates the token and immediately invalidates the previous address', function () {
    $admin = servantLinkAdmin();
    $retreat = servantLinkRetreat(['servant_registration_enabled' => true]);
    $oldToken = $retreat->servant_registration_token;

    $this->actingAs($admin)->post(route('retreats.servant-link.regenerate', $retreat))->assertRedirect();
    $retreat->refresh();

    expect($retreat->servant_registration_token)->not->toBe($oldToken)->toHaveLength(64);
    $this->get(route('portal.servants.link', $oldToken))->assertNotFound();
    $this->get(route('portal.servants.link', $retreat->servant_registration_token))->assertOk();
});
