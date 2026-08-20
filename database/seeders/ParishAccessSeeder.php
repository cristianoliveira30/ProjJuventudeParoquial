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
        $secretary->permissions()->sync(
            Permission::whereIn('slug', [
                'users.view',
                'users.create',
                'users.update',
                'users.status',
                'users.reset-password',
                'notifications.send',
            ])->pluck('id')
        );

        $admin = Role::where('sigla', 'ADMIN')->firstOrFail();
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
            ...(! $adminUser->password_verify ? [
                'password' => config('parish.admin.password'),
                'is_active' => true,
            ] : []),
        ]);
    }
}
