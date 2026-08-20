<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        return view('roles.index', [
            'roles' => Role::query()
                ->whereIn('sigla', ['ADMIN', 'SEC', 'EDITOR', 'COORD'])
                ->with('permissions')
                ->withCount('users')
                ->orderByRaw("CASE sigla WHEN 'ADMIN' THEN 1 WHEN 'SEC' THEN 2 WHEN 'EDITOR' THEN 3 ELSE 4 END")
                ->get(),
            'permissions' => Permission::query()
                ->whereIn('slug', array_keys(config('permissions', [])))
                ->orderBy('description')
                ->get(),
            'page' => 'roles.index',
        ]);
    }

    public function update(Request $request, Role $role)
    {
        abort_unless(in_array($role->sigla, ['ADMIN', 'SEC', 'EDITOR', 'COORD'], true), 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:80', Rule::unique('roles', 'name')->ignore($role)],
            'description' => ['nullable', 'string', 'max:255'],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', Rule::in(array_keys(config('permissions', [])))],
        ]);

        DB::transaction(function () use ($role, $validated) {
            $role->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
            ]);

            if ($role->sigla !== 'ADMIN') {
                $permissionIds = Permission::query()
                    ->whereIn('slug', $validated['permissions'] ?? [])
                    ->pluck('id');
                $role->permissions()->sync($permissionIds);
            }
        });

        return back()->with('success', "Permissões de {$role->name} atualizadas.");
    }
}
