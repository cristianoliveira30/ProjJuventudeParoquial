<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class ParishAccessSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'ADMIN' => ['name' => 'Administrador', 'description' => 'Acesso completo ao sistema paroquial'],
            'SEC' => ['name' => 'Secretaria', 'description' => 'Atendimento e gestão de usuários'],
            'EDITOR' => ['name' => 'Editor do Portal', 'description' => 'Publicação e manutenção de conteúdo'],
            'COORD' => ['name' => 'Coordenador Pastoral', 'description' => 'Acompanhamento de uma pastoral ou movimento'],
        ];

        foreach ($roles as $sigla => $attributes) {
            Role::updateOrCreate(['sigla' => $sigla], $attributes);
        }

        foreach (config('permissions', []) as $slug => $description) {
            Permission::updateOrCreate(['slug' => $slug], ['description' => $description]);
        }

        $secretary = Role::where('sigla', 'SEC')->firstOrFail();
        $secretary->permissions()->syncWithoutDetaching(
            Permission::whereIn('slug', [
                'users.view',
                'users.create',
                'users.update',
                'users.status',
                'users.reset-password',
                'notifications.send',
                'retreats.view',
                'retreats.registrations',
            ])->pluck('id')
        );

        $editor = Role::where('sigla', 'EDITOR')->firstOrFail();
        $editor->permissions()->syncWithoutDetaching(
            Permission::whereIn('slug', [
                'posts.view',
                'posts.create',
                'posts.update',
                'posts.publish',
                'events.view',
                'events.create',
                'events.update',
                'events.publish',
                'groups.view',
                'groups.create',
                'groups.update',
                'groups.publish',
                'retreats.view',
                'retreats.create',
                'retreats.update',
                'retreats.publish',
                'retreats.registrations',
                'gallery.view',
                'gallery.create',
                'gallery.update',
                'gallery.photos',
            ])->pluck('id')
        );

        $coordinator = Role::where('sigla', 'COORD')->firstOrFail();
        $coordinator->permissions()->syncWithoutDetaching(
            Permission::whereIn('slug', [
                'events.view',
                'events.create',
                'events.update',
                'groups.view',
                'groups.update',
                'retreats.view',
                'retreats.create',
                'retreats.update',
                'retreats.registrations',
                'gallery.view',
                'gallery.create',
                'gallery.update',
                'gallery.photos',
            ])->pluck('id')
        );

        $admin = Role::where('sigla', 'ADMIN')->firstOrFail();
        $admin->permissions()->syncWithoutDetaching(Permission::query()->pluck('id'));
        $adminUser = User::query()->firstOrCreate(
            ['username' => config('parish.admin.username')],
            [
                'name' => config('parish.admin.name'),
                'email' => config('parish.admin.email'),
                'password' => config('parish.admin.password'),
                'password_verify' => false,
                'is_active' => true,
                'role_id' => $admin->id,
            ]
        );

        $adminUser->update([
            'role_id' => $admin->id,
            'is_active' => true,
            ...(! $adminUser->password_verify ? [
                'password' => config('parish.admin.password'),
            ] : []),
        ]);
    }
}
