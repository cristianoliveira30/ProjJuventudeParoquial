<?php

use App\Models\Permission;
use App\Models\Retreat;
use App\Models\RetreatRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function openRetreat(array $attributes = []): Retreat
{
    return Retreat::query()->create([...[
        'title' => 'Retiro de inscrição',
        'slug' => 'retiro-de-inscricao',
        'description' => 'Retiro usado nos testes de inscrição pública.',
        'starts_at' => now()->addMonth(),
        'published_at' => now()->subDay(),
        'capacity' => 1,
    ], ...$attributes]);
}

function registrationPayload(array $attributes = []): array
{
    return [...[
        'name' => 'João Participante',
        'birth_date' => now()->subYears(20)->format('Y-m-d'),
        'email' => 'joao@example.com',
        'phone' => '(91) 99999-0000',
        'emergency_contact_name' => 'Ana',
        'emergency_contact_phone' => '(91) 98888-0000',
        'consent' => '1',
        'health_data_consent' => '1',
    ], ...$attributes];
}

it('puts registrations beyond capacity on the waitlist', function () {
    $retreat = openRetreat();

    $this->post(route('portal.retiros.register', $retreat), registrationPayload())->assertSessionHasNoErrors();
    $this->post(route('portal.retiros.register', $retreat), registrationPayload(['email' => 'maria@example.com']))->assertSessionHasNoErrors();

    expect(RetreatRegistration::query()->orderBy('id')->pluck('status')->all())->toBe(['pending', 'waitlist']);
});

it('requires guardian data for a minor participant', function () {
    $retreat = openRetreat();

    $this->post(route('portal.retiros.register', $retreat), registrationPayload([
        'birth_date' => now()->subYears(15)->format('Y-m-d'),
    ]))->assertSessionHasErrors(['guardian_name', 'guardian_phone']);

    expect(RetreatRegistration::query()->count())->toBe(0);
});

it('only lets a coordinator see registrations of the retreats they coordinate', function () {
    $role = Role::query()->create(['name' => 'Coordenador', 'sigla' => 'COORD']);
    $role->permissions()->attach(Permission::query()->create(['slug' => 'retreats.registrations', 'description' => 'Inscrições']));
    [$owner, $other] = collect(['dono', 'outro'])->map(fn ($name) => User::query()->create([
        'name' => $name,
        'username' => $name,
        'email' => "{$name}@example.com",
        'password' => 'Senha@123',
        'password_verify' => true,
        'is_active' => true,
        'role_id' => $role->id,
    ]))->all();
    $retreat = openRetreat(['coordinator_id' => $owner->id]);

    $this->actingAs($other)->get(route('retreats.registrations.index', $retreat))->assertForbidden();
    $this->actingAs($owner)->get(route('retreats.registrations.index', $retreat))->assertOk();
});

it('does not accept registrations for an unpublished retreat', function () {
    $retreat = openRetreat(['published_at' => null]);

    $this->post(route('portal.retiros.register', $retreat), registrationPayload())->assertNotFound();
});
