<?php

use App\Http\Middleware\EnsureFirstAccessPassword;
use App\Http\Middleware\TrackPageAccess;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\ParishAccessSeeder;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

function prepareParishAccessSchema(): void
{
    Schema::dropIfExists('permission_role');
    Schema::dropIfExists('permissions');
    Schema::dropIfExists('sessions');
    Schema::create('sessions', function (Blueprint $table) {
        $table->string('id')->primary();
        $table->unsignedBigInteger('user_id')->nullable();
    });
    Schema::dropIfExists('personal_access_tokens');
    Schema::create('personal_access_tokens', function (Blueprint $table) {
        $table->id();
        $table->morphs('tokenable');
    });
    Schema::dropIfExists('users');
    Schema::dropIfExists('roles');

    Schema::create('roles', function (Blueprint $table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('sigla')->unique();
        $table->string('description')->nullable();
        $table->timestamps();
    });

    Schema::create('permissions', function (Blueprint $table) {
        $table->id();
        $table->string('slug')->unique();
        $table->string('description')->nullable();
        $table->timestamps();
    });

    Schema::create('permission_role', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('permission_id');
        $table->unsignedBigInteger('role_id');
    });

    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->boolean('password_verify')->default(false);
        $table->boolean('is_active')->default(true);
        $table->unsignedBigInteger('role_id')->nullable();
        $table->string('profile_photo_url')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}

function parishUser(Role $role, array $attributes = []): User
{
    return User::query()->create(array_merge([
        'name' => 'Usuário Paroquial',
        'username' => 'usuario.paroquial',
        'email' => 'usuario@paroquia.test',
        'password' => 'Senha@12345',
        'password_verify' => true,
        'is_active' => true,
        'role_id' => $role->id,
    ], $attributes));
}

beforeEach(function () {
    if (! extension_loaded('pdo_sqlite')) {
        $this->markTestSkipped('The local PHP runtime does not have pdo_sqlite; this test runs under Sail.');
    }

    prepareParishAccessSchema();
});

it('gives administrators every parish permission', function () {
    $admin = parishUser(Role::query()->create(['name' => 'Administrador', 'sigla' => 'ADMIN']));

    expect(Gate::forUser($admin)->allows('users.view'))->toBeTrue()
        ->and(Gate::forUser($admin)->allows('roles.manage'))->toBeTrue();
});

it('limits non-admin users to permissions attached to their role', function () {
    $secretaryRole = Role::query()->create(['name' => 'Secretaria', 'sigla' => 'SEC']);
    $viewPermission = Permission::query()->create(['slug' => 'users.view', 'description' => 'Visualizar usuários']);
    $secretaryRole->permissions()->attach($viewPermission);
    $secretary = parishUser($secretaryRole);

    expect(Gate::forUser($secretary)->allows('users.view'))->toBeTrue()
        ->and(Gate::forUser($secretary)->denies('roles.manage'))->toBeTrue();
});

it('prevents a secretary from creating an administrator', function () {
    $adminRole = Role::query()->create(['name' => 'Administrador', 'sigla' => 'ADMIN']);
    $secretaryRole = Role::query()->create(['name' => 'Secretaria', 'sigla' => 'SEC']);
    $permission = Permission::query()->create(['slug' => 'users.create', 'description' => 'Adicionar usuários']);
    $secretaryRole->permissions()->attach($permission);
    $secretary = parishUser($secretaryRole);

    $this->withoutMiddleware([EnsureFirstAccessPassword::class, TrackPageAccess::class])
        ->actingAs($secretary)
        ->from(route('user.create'))
        ->post(route('user.store'), [
            'name' => 'Novo Administrador',
            'username' => 'novo.admin',
            'email' => 'novo.admin@paroquia.test',
            'role_id' => $adminRole->id,
            'temporary_password' => 'Temporaria@123',
        ])
        ->assertRedirect(route('user.create'))
        ->assertSessionHasErrors('role_id');

    expect(User::query()->where('username', 'novo.admin')->exists())->toBeFalse();
});

it('blocks every permission for an inactive user', function () {
    $admin = parishUser(Role::query()->create(['name' => 'Administrador', 'sigla' => 'ADMIN']), ['is_active' => false]);

    expect(Gate::forUser($admin)->denies('users.view'))->toBeTrue();
});

it('ends an already open session when the user is deactivated', function () {
    Event::fake();
    $role = Role::query()->create(['name' => 'Editor do Portal', 'sigla' => 'EDITOR']);
    $user = parishUser($role, ['is_active' => false]);

    $this->withoutMiddleware([EnsureFirstAccessPassword::class, TrackPageAccess::class])
        ->actingAs($user)
        ->get(route('account.security'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('login');

    $this->assertGuest();
});

it('seeds one initial administrator and remains idempotent', function () {
    config()->set('parish.admin.password', 'SenhaInicial@123');
    $seeder = app(ParishAccessSeeder::class);
    $seeder->run();
    $seeder->run();

    $admin = User::query()->where('username', config('parish.admin.username'))->firstOrFail();
    $secretary = Role::query()->where('sigla', 'SEC')->firstOrFail();
    $editor = Role::query()->where('sigla', 'EDITOR')->firstOrFail();
    $administrator = Role::query()->where('sigla', 'ADMIN')->firstOrFail();

    expect(User::query()->where('username', config('parish.admin.username'))->count())->toBe(1)
        ->and($admin->role?->sigla)->toBe('ADMIN')
        ->and($admin->password_verify)->toBeFalse()
        ->and($admin->is_active)->toBeTrue()
        ->and($administrator->permissions->pluck('slug')->sort()->values()->all())->toBe(Permission::query()->pluck('slug')->sort()->values()->all())
        ->and($secretary->permissions->pluck('slug')->contains('retreats.registrations'))->toBeTrue()
        ->and($editor->permissions->pluck('slug')->sort()->values()->all())->toBe([
            'events.create',
            'events.publish',
            'events.update',
            'events.view',
            'gallery.create',
            'gallery.photos',
            'gallery.update',
            'gallery.view',
            'groups.create',
            'groups.publish',
            'groups.update',
            'groups.view',
            'posts.create',
            'posts.publish',
            'posts.update',
            'posts.view',
            'retreats.create',
            'retreats.publish',
            'retreats.registrations',
            'retreats.update',
            'retreats.view',
        ])
        ->and(Role::query()->where('sigla', 'COORD')->firstOrFail()->permissions->pluck('slug')->sort()->values()->all())->toBe([
            'events.create',
            'events.update',
            'events.view',
            'gallery.create',
            'gallery.photos',
            'gallery.update',
            'gallery.view',
            'groups.update',
            'groups.view',
            'retreats.create',
            'retreats.registrations',
            'retreats.update',
            'retreats.view',
        ]);
});

it('requires an explicit initial administrator password', function () {
    config()->set('parish.admin.password', null);

    expect(fn () => app(ParishAccessSeeder::class)->run())
        ->toThrow(LogicException::class, 'Defina PARISH_ADMIN_PASSWORD');
});

it('allows an administrator to create a user with mandatory first access', function () {
    $adminRole = Role::query()->create(['name' => 'Administrador', 'sigla' => 'ADMIN']);
    $secretaryRole = Role::query()->create(['name' => 'Secretaria', 'sigla' => 'SEC']);
    $admin = parishUser($adminRole, ['username' => 'admin', 'email' => 'admin@paroquia.test']);

    $this->withoutMiddleware([EnsureFirstAccessPassword::class, TrackPageAccess::class])
        ->actingAs($admin)
        ->post(route('user.store'), [
            'name' => 'Maria da Secretaria',
            'username' => 'maria.secretaria',
            'email' => 'maria@paroquia.test',
            'role_id' => $secretaryRole->id,
            'temporary_password' => 'Temporaria@123',
        ])
        ->assertRedirect(route('user.search'))
        ->assertSessionHas('temporary_password');

    $created = User::query()->where('username', 'maria.secretaria')->firstOrFail();

    expect($created->password_verify)->toBeFalse()
        ->and($created->is_active)->toBeTrue()
        ->and(Hash::check('Temporaria@123', $created->password))->toBeTrue();
});

it('changes the authenticated users password only with the current password', function () {
    $role = Role::query()->create(['name' => 'Editor do Portal', 'sigla' => 'EDITOR']);
    $user = parishUser($role);

    $this->withoutMiddleware([EnsureFirstAccessPassword::class, TrackPageAccess::class])
        ->actingAs($user)
        ->put(route('account.password.update'), [
            'current_password' => 'Senha@12345',
            'password' => 'NovaSenha@12345',
            'password_confirmation' => 'NovaSenha@12345',
        ])
        ->assertSessionHasNoErrors()
        ->assertSessionHas('success');

    expect(Hash::check('NovaSenha@12345', $user->refresh()->password))->toBeTrue();
});
