<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

function staff(string $name, string $sigla, $createdAt): User
{
    $role = Role::firstOrCreate(['sigla' => $sigla], ['name' => $sigla]);
    $user = User::query()->create(['name' => $name, 'username' => $name, 'email' => "$name@example.com", 'password' => 'Senha@123', 'password_verify' => true, 'is_active' => true, 'role_id' => $role->id]);
    $user->forceFill(['created_at' => $createdAt])->save();

    return $user;
}

it('deletes users with no login for 2 years but keeps admins and recent logins', function () {
    $old = staff('antigo', 'EDITOR', now()->subYears(3));
    $admin = staff('admin', 'ADMIN', now()->subYears(3));
    $recent = staff('recente', 'EDITOR', now()->subYears(3));
    $new = staff('novo', 'EDITOR', now()->subMonth());

    DB::table(config('authentication-log.table_name'))->insert([
        'authenticatable_type' => $recent->getMorphClass(), 'authenticatable_id' => $recent->id, 'login_at' => now()->subMonths(2),
    ]);

    $this->artisan('users:prune-inactive')->assertSuccessful();

    expect(User::find($old->id))->toBeNull()
        ->and(User::find($admin->id))->not->toBeNull()
        ->and(User::find($recent->id))->not->toBeNull()
        ->and(User::find($new->id))->not->toBeNull();
});
