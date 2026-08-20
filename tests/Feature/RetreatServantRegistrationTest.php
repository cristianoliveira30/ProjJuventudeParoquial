<?php

use App\Models\Retreat;
use App\Models\RetreatMinistry;
use App\Models\RetreatServant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function stageFourRetreat(array $attributes = []): Retreat
{
    $retreat = Retreat::query()->create([...[
        'title' => 'Retiro etapa quatro',
        'slug' => 'retiro-etapa-quatro',
        'description' => 'Retiro usado nos testes dos servos.',
        'starts_at' => now()->addYear(),
        'servant_registration_token' => Str::random(64),
        'servant_registration_enabled' => true,
    ], ...$attributes]);

    RetreatMinistry::ensureDefaultsFor($retreat);

    return $retreat;
}

function stageFourPayload(Retreat $retreat, RetreatMinistry $ministry, array $attributes = []): array
{
    return [...[
        'name' => 'Maria Servidora',
        'birth_date' => now()->subYears(24)->format('Y-m-d'),
        'email' => 'maria.servidora@example.com',
        'phone' => '(91) 99999-0000',
        'community' => 'Comunidade São José',
        'mother_name' => 'Ana Maria',
        'father_name' => 'José Maria',
        'emergency_contact_name' => 'Ana Maria',
        'emergency_contact_phone' => '(91) 98888-0000',
        'allergies' => 'Alergia a camarão',
        'previous_retreat_year' => $retreat->starts_at->year - 1,
        'preferred_ministry_id' => $ministry->id,
        'consent' => '1',
        'health_data_consent' => '1',
    ], ...$attributes];
}

function stageFourAdmin(): User
{
    $role = Role::query()->create(['name' => 'Administrador etapa quatro', 'sigla' => 'ADMIN']);

    return User::query()->create([
        'name' => 'Administrador',
        'username' => 'admin.etapa.quatro',
        'email' => 'admin.etapa.quatro@example.com',
        'password' => 'Senha@123',
        'password_verify' => true,
        'is_active' => true,
        'role_id' => $role->id,
    ]);
}

it('registers a servant through the valid private link', function () {
    $retreat = stageFourRetreat();
    $ministry = $retreat->ministries()->firstOrFail();

    $this->post(route('portal.servants.register', $retreat->servant_registration_token), stageFourPayload($retreat, $ministry))
        ->assertRedirect()
        ->assertSessionHas('servant_registration_success');

    $servant = RetreatServant::query()->firstOrFail();
    expect($servant->retreat_id)->toBe($retreat->id)
        ->and($servant->preferred_ministry_id)->toBe($ministry->id)
        ->and($servant->previous_retreat_year)->toBe($retreat->starts_at->year - 1)
        ->and($servant->status)->toBe('pending')
        ->and($servant->consented_at)->not->toBeNull()
        ->and($servant->health_data_consented_at)->not->toBeNull();
});

it('rejects a ministry from another retreat and a non previous retreat year', function () {
    $retreat = stageFourRetreat();
    $otherRetreat = stageFourRetreat(['slug' => 'outro-retiro', 'servant_registration_token' => Str::random(64)]);
    $foreignMinistry = $otherRetreat->ministries()->firstOrFail();

    $this->from(route('portal.servants.link', $retreat->servant_registration_token))
        ->post(route('portal.servants.register', $retreat->servant_registration_token), stageFourPayload($retreat, $foreignMinistry, [
            'previous_retreat_year' => $retreat->starts_at->year,
        ]))
        ->assertRedirect(route('portal.servants.link', $retreat->servant_registration_token))
        ->assertSessionHasErrors(['preferred_ministry_id', 'previous_retreat_year']);

    expect(RetreatServant::query()->count())->toBe(0);
});

it('requires guardian data for a minor servant', function () {
    $retreat = stageFourRetreat();
    $ministry = $retreat->ministries()->firstOrFail();

    $this->post(route('portal.servants.register', $retreat->servant_registration_token), stageFourPayload($retreat, $ministry, [
        'birth_date' => $retreat->starts_at->copy()->subYears(16)->format('Y-m-d'),
    ]))->assertSessionHasErrors(['guardian_name', 'guardian_phone']);
});

it('does not allow the same email twice in one retreat', function () {
    $retreat = stageFourRetreat();
    $ministry = $retreat->ministries()->firstOrFail();
    $payload = stageFourPayload($retreat, $ministry);

    $this->post(route('portal.servants.register', $retreat->servant_registration_token), $payload)
        ->assertSessionHasNoErrors();
    $this->post(route('portal.servants.register', $retreat->servant_registration_token), $payload)
        ->assertSessionHasErrors('email');

    expect($retreat->servants()->count())->toBe(1);
});

it('does not accept registrations through a closed servant link', function () {
    $retreat = stageFourRetreat(['servant_registration_enabled' => false]);
    $ministry = $retreat->ministries()->firstOrFail();

    $this->post(route('portal.servants.register', $retreat->servant_registration_token), stageFourPayload($retreat, $ministry))
        ->assertNotFound();
});

it('allows an administrator to approve and assign a servant ministry', function () {
    $admin = stageFourAdmin();
    $retreat = stageFourRetreat();
    $preferred = $retreat->ministries()->firstOrFail();
    $assigned = $retreat->ministries()->whereKeyNot($preferred->id)->firstOrFail();
    $servant = $retreat->servants()->create([
        ...collect(stageFourPayload($retreat, $preferred))->except(['consent', 'health_data_consent'])->all(),
        'status' => 'pending',
        'consented_at' => now(),
        'health_data_consented_at' => now(),
    ]);

    $this->actingAs($admin)->patch(route('retreats.servants.update', [$retreat, $servant]), [
        'status' => 'approved',
        'assigned_ministry_id' => $assigned->id,
        'internal_notes' => 'Confirmado pela coordenação.',
    ])->assertRedirect()->assertSessionHas('success');

    $servant->refresh();
    expect($servant->status)->toBe('approved')
        ->and($servant->assigned_ministry_id)->toBe($assigned->id)
        ->and($servant->approved_by)->toBe($admin->id)
        ->and($servant->approved_at)->not->toBeNull();
});

it('does not allow assigning a ministry from another retreat', function () {
    $admin = stageFourAdmin();
    $retreat = stageFourRetreat();
    $preferred = $retreat->ministries()->firstOrFail();
    $otherRetreat = stageFourRetreat(['slug' => 'retiro-estrangeiro', 'servant_registration_token' => Str::random(64)]);
    $foreignMinistry = $otherRetreat->ministries()->firstOrFail();
    $servant = $retreat->servants()->create([
        ...collect(stageFourPayload($retreat, $preferred))->except(['consent', 'health_data_consent'])->all(),
        'status' => 'pending',
        'consented_at' => now(),
        'health_data_consented_at' => now(),
    ]);

    $this->actingAs($admin)->patch(route('retreats.servants.update', [$retreat, $servant]), [
        'status' => 'approved',
        'assigned_ministry_id' => $foreignMinistry->id,
    ])->assertSessionHasErrors('assigned_ministry_id');

    expect($servant->fresh()->assigned_ministry_id)->toBeNull();
});

it('requires a ministry before approving a servant', function () {
    $admin = stageFourAdmin();
    $retreat = stageFourRetreat();
    $preferred = $retreat->ministries()->firstOrFail();
    $servant = $retreat->servants()->create([
        ...collect(stageFourPayload($retreat, $preferred))->except(['consent', 'health_data_consent'])->all(),
        'status' => 'pending',
        'consented_at' => now(),
        'health_data_consented_at' => now(),
    ]);

    $this->actingAs($admin)->patch(route('retreats.servants.update', [$retreat, $servant]), [
        'status' => 'approved',
        'assigned_ministry_id' => '',
    ])->assertSessionHasErrors('assigned_ministry_id');

    expect($servant->fresh()->status)->toBe('pending');
});
