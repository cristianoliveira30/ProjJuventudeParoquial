<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    private const PARISH_ROLES = ['ADMIN', 'SEC', 'EDITOR', 'COORD'];

    public function create()
    {
        return view('users.create', [
            'roles' => $this->parishRoles(request()->user()),
            'page' => 'user.create',
        ]);
    }

    public function store(Request $request)
    {
        $allowedRoles = $this->allowedRoleSiglas($request->user());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z0-9._-]+$/', 'unique:users,username'],
            'role_id' => ['required', Rule::exists('roles', 'id')->whereIn('sigla', $allowedRoles)],
            'temporary_password' => ['nullable', 'string', 'max:100', Password::min(10)->mixedCase()->numbers()->symbols()],
        ], [
            'username.regex' => 'Use apenas letras, números, ponto, hífen ou sublinhado no usuário.',
        ]);

        $temporaryPassword = $validated['temporary_password'] ?: $this->temporaryPassword();

        $user = User::create([
            'name' => $validated['name'],
            'email' => Str::lower($validated['email']),
            'username' => Str::lower($validated['username']),
            'role_id' => $validated['role_id'],
            'password' => $temporaryPassword,
            'password_verify' => false,
            'is_active' => true,
        ]);

        return redirect()->route('user.search')->with([
            'success' => 'Usuário cadastrado. Compartilhe a senha temporária por um canal seguro.',
            'temporary_password' => $temporaryPassword,
            'temporary_password_user' => $user->name,
        ]);
    }

    public function search(Request $request)
    {
        $query = trim((string) $request->input('q'));
        $status = $request->input('status', 'all');
        $role = $request->integer('role');

        $users = User::query()
            ->with('role')
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($nested) use ($query) {
                    $nested->where('name', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('username', 'like', "%{$query}%");
                });
            })
            ->when($status === 'active', fn ($builder) => $builder->where('is_active', true))
            ->when($status === 'inactive', fn ($builder) => $builder->where('is_active', false))
            ->when($role, fn ($builder) => $builder->where('role_id', $role))
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'roles' => $this->parishRoles($request->user()),
            'parishRoleSiglas' => self::PARISH_ROLES,
            'legacyUsersCount' => User::query()
                ->whereDoesntHave('role', fn ($builder) => $builder->whereIn('sigla', self::PARISH_ROLES))
                ->count(),
            'query' => $query,
            'status' => $status,
            'selectedRole' => $role,
            'page' => 'user.search',
        ]);
    }

    public function updateAdmin(Request $request, User $user)
    {
        $this->ensureCanManage($request->user(), $user);
        $allowedRoles = $this->allowedRoleSiglas($request->user());
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'username' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($user)],
            'role_id' => ['required', Rule::exists('roles', 'id')->whereIn('sigla', $allowedRoles)],
        ]);

        if ($user->is($request->user())) {
            unset($validated['role_id']);
        }

        $user->update([
            ...$validated,
            'email' => Str::lower($validated['email']),
            'username' => Str::lower($validated['username']),
        ]);

        return back()->with('success', 'Usuário atualizado com sucesso.');
    }

    public function toggleStatus(Request $request, User $user)
    {
        $this->ensureCanManage($request->user(), $user);
        abort_if($user->is($request->user()), 422, 'Você não pode desativar sua própria conta.');

        $user->update(['is_active' => ! $user->is_active]);

        if (! $user->is_active) {
            $this->revokeUserAccess($user);
        }

        return back()->with('success', $user->is_active ? 'Usuário ativado.' : 'Usuário desativado.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->ensureCanManage($request->user(), $user);
        abort_if($user->is($request->user()), 422, 'Use a página Segurança da conta para trocar sua senha.');

        $temporaryPassword = $this->temporaryPassword();
        $user->update([
            'password' => $temporaryPassword,
            'password_verify' => false,
        ]);
        $this->revokeUserAccess($user);

        return back()->with([
            'success' => 'Senha redefinida. O usuário deverá criar uma nova senha no próximo acesso.',
            'temporary_password' => $temporaryPassword,
            'temporary_password_user' => $user->name,
        ]);
    }

    public function showSecurity()
    {
        return view('account.security', ['page' => 'account.security']);
    }

    public function showFirstAccess(Request $request)
    {
        if ($request->user()->password_verify) {
            return redirect()->route('account.security');
        }

        return view('navigation.usuario.first-access');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(10)->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.current_password' => 'A senha atual está incorreta.',
            'password.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
            'password_verify' => true,
        ]);
        $this->revokeUserAccess($request->user(), $request->session()->getId());

        return back()->with('success', 'Senha alterada com sucesso.');
    }

    public function updateFirstAccessPassword(Request $request)
    {
        abort_if($request->user()?->password_verify === true, 403, 'A senha inicial já foi definida.');

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(10)->mixedCase()->numbers()->symbols()],
        ], [
            'password.confirmed' => 'A confirmação da nova senha não confere.',
        ]);

        $request->user()->update([
            'password' => $validated['password'],
            'password_verify' => true,
        ]);
        $this->revokeUserAccess($request->user(), $request->session()->getId());

        return redirect()->route('home')->with('success', 'Senha definida com sucesso.');
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'username' => ['required', 'string', 'max:80', 'regex:/^[a-zA-Z0-9._-]+$/', Rule::unique('users', 'username')->ignore($user)],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => Str::lower($validated['email']),
            'username' => Str::lower($validated['username']),
        ]);

        if ($request->hasFile('photo')) {
            if ($user->profile_photo_url && str_starts_with($user->profile_photo_url, '/storage/')) {
                Storage::disk('public')->delete(Str::after($user->profile_photo_url, '/storage/'));
            }

            $path = $request->file('photo')->store('profile-photos', 'public');
            $user->update(['profile_photo_url' => '/storage/'.$path]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Dados da conta atualizados.',
                'user' => $user->fresh()->only(['name', 'email', 'username', 'profile_photo_url']),
            ]);
        }

        return back()->with('success', 'Dados da conta atualizados.');
    }

    private function parishRoles(User $actor)
    {
        return Role::query()->whereIn('sigla', $this->allowedRoleSiglas($actor))->orderBy('name')->get();
    }

    private function allowedRoleSiglas(User $actor): array
    {
        return $actor->role?->sigla === 'ADMIN'
            ? self::PARISH_ROLES
            : array_values(array_diff(self::PARISH_ROLES, ['ADMIN']));
    }

    private function ensureCanManage(User $actor, User $target): void
    {
        abort_if(
            $actor->role?->sigla !== 'ADMIN' && $target->role?->sigla === 'ADMIN',
            403,
            'Somente um administrador pode gerenciar outra conta administrativa.'
        );
    }

    private function revokeUserAccess(User $user, ?string $exceptSessionId = null): void
    {
        if (Schema::hasTable('personal_access_tokens')) {
            $user->tokens()->delete();
        }

        if (Schema::hasTable('sessions')) {
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->when($exceptSessionId, fn ($query) => $query->where('id', '!=', $exceptSessionId))
                ->delete();
        }
    }

    private function temporaryPassword(): string
    {
        return Str::random(8).'@'.random_int(10, 99).'Aa';
    }
}
