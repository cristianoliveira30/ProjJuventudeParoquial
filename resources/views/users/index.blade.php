<x-layouts.app title="Usuários — Paróquia São Domingos de Gusmão">
    <div class="mx-auto w-full max-w-[92rem] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[.18em] text-[#0f9688]">Administração</p>
                <h1 class="mt-2 text-3xl font-bold tracking-[-0.035em] text-[#210124] dark:text-white">Usuários e acessos</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Controle quem acessa o painel e qual responsabilidade cada pessoa possui.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row">
                @can('roles.manage')
                    <a href="{{ route('roles.index') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#14B8A6] bg-white px-4 text-sm font-semibold text-[#0f9688] transition hover:bg-[#E5FCF5] dark:bg-slate-900 dark:text-emerald-300">
                        <x-bi-shield-check class="size-4" /> Perfis de acesso
                    </a>
                @endcan
                @can('users.create')
                    <a href="{{ route('user.create') }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(117,13,55,.22)] transition hover:bg-[#5f0b2e] focus:outline-none focus:ring-2 focus:ring-[#750D37]/40 focus:ring-offset-2">
                        <x-bi-person-plus class="size-4" /> Adicionar usuário
                    </a>
                @endcan
            </div>
        </div>

        @if(session('success'))
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-200">
                <x-bi-check-circle class="mt-0.5 size-4 shrink-0" /> {{ session('success') }}
            </div>
        @endif

        @if($legacyUsersCount > 0)
            <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900 dark:border-amber-900/60 dark:bg-amber-950/20 dark:text-amber-200">
                <x-bi-exclamation-triangle class="mt-0.5 size-4 shrink-0" />
                <p><strong>{{ $legacyUsersCount }} usuário(s) com perfil legado.</strong> Ao editar um deles, escolha explicitamente um dos novos perfis paroquiais.</p>
            </div>
        @endif

        @if(session('temporary_password'))
            <div class="mt-5 rounded-[1.25rem] border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/60 dark:bg-amber-950/20">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-bold text-amber-900 dark:text-amber-200">Senha temporária de {{ session('temporary_password_user') }}</p>
                        <p class="mt-1 text-xs leading-5 text-amber-800/80 dark:text-amber-300/80">Ela será mostrada apenas agora. Compartilhe por um canal seguro.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <code id="temporary-password" class="rounded-lg border border-amber-300 bg-white px-3 py-2 text-sm font-bold text-[#210124] dark:border-amber-800 dark:bg-slate-950 dark:text-white">{{ session('temporary_password') }}</code>
                        <button type="button" data-copy-password class="inline-flex size-10 items-center justify-center rounded-lg bg-[#750D37] text-white hover:bg-[#5f0b2e]" aria-label="Copiar senha" title="Copiar senha">
                            <x-bi-copy class="size-4" />
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <section class="mt-6 overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
            <form method="GET" action="{{ route('user.search') }}" class="grid gap-3 border-b border-slate-100 p-4 sm:grid-cols-[minmax(16rem,1fr)_12rem_14rem_auto] sm:p-5 dark:border-slate-800">
                <label class="relative">
                    <span class="sr-only">Buscar usuários</span>
                    <x-bi-search class="pointer-events-none absolute left-4 top-1/2 size-4 -translate-y-1/2 text-slate-400" />
                    <input name="q" value="{{ $query }}" placeholder="Nome, e-mail ou usuário"
                        class="block min-h-11 w-full rounded-xl border-slate-300 bg-white pl-11 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                </label>
                <select name="status" aria-label="Filtrar por situação" class="min-h-11 rounded-xl border-slate-300 bg-white text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <option value="all" @selected($status === 'all')>Todos os status</option>
                    <option value="active" @selected($status === 'active')>Ativos</option>
                    <option value="inactive" @selected($status === 'inactive')>Inativos</option>
                </select>
                <select name="role" aria-label="Filtrar por perfil" class="min-h-11 rounded-xl border-slate-300 bg-white text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                    <option value="">Todos os perfis</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" @selected($selectedRole === $role->id)>{{ $role->name }}</option>
                    @endforeach
                </select>
                <button class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#14B8A6] px-4 text-sm font-semibold text-white transition hover:bg-[#0f9f91]">
                    Filtrar
                </button>
            </form>

            <div class="hidden overflow-x-auto md:block">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[11px] uppercase tracking-wider text-slate-500 dark:bg-slate-950/50 dark:text-slate-400">
                        <tr>
                            <th class="px-5 py-3 font-semibold">Usuário</th>
                            <th class="px-5 py-3 font-semibold">Perfil</th>
                            <th class="px-5 py-3 font-semibold">Acesso</th>
                            <th class="px-5 py-3 text-right font-semibold">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($users as $user)
                            @php($isParishRole = in_array($user->role?->sigla, $parishRoleSiglas, true))
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img class="size-10 rounded-full object-cover ring-2 ring-[#B3DEC1]" src="{{ $user->profile_photo_url ? asset($user->profile_photo_url) : 'https://ui-avatars.com/api/?background=750D37&color=fff&name=' . urlencode($user->name) }}" alt="">
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-[#210124] dark:text-white">{{ $user->name }} @if($user->is(auth()->user()))<span class="text-[10px] font-medium text-[#0f9688]">(você)</span>@endif</p>
                                            <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ '@' . $user->username }} · {{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4"><span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-[#E5FCF5] text-[#0f9688] dark:bg-emerald-950/40 dark:text-emerald-300' => $isParishRole, 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300' => ! $isParishRole])>{{ $user->role?->name ?? 'Sem perfil' }}{{ $isParishRole ? '' : ' · legado' }}</span></td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <span @class(['rounded-full px-2.5 py-1 text-xs font-semibold', 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' => $user->is_active, 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' => !$user->is_active])>{{ $user->is_active ? 'Ativo' : 'Inativo' }}</span>
                                        @unless($user->password_verify)<span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-950/40 dark:text-amber-300">Primeiro acesso</span>@endunless
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-1">
                                        @if(auth()->user()->role?->sigla === 'ADMIN' || $user->role?->sigla !== 'ADMIN')
                                            @can('users.update')
                                                <button type="button" data-open-user="{{ $user->id }}" class="inline-flex size-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-[#E5FCF5] hover:text-[#750D37] dark:hover:bg-slate-800" title="Editar"><x-bi-pencil-square class="size-4" /></button>
                                            @endcan
                                            @can('users.reset-password')
                                                @unless($user->is(auth()->user()))
                                                    <form action="{{ route('user.reset-password', $user) }}" method="POST" onsubmit="return confirm('Redefinir a senha de {{ addslashes($user->name) }}?')">@csrf<button class="inline-flex size-9 items-center justify-center rounded-lg text-slate-500 transition hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/30" title="Redefinir senha"><x-bi-key class="size-4" /></button></form>
                                                @endunless
                                            @endcan
                                            @can('users.status')
                                                @unless($user->is(auth()->user()))
                                                    <form action="{{ route('user.status', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_active ? 'Desativar' : 'Ativar' }} o acesso de {{ addslashes($user->name) }}?')">@csrf @method('PATCH')<button class="inline-flex size-9 items-center justify-center rounded-lg transition {{ $user->is_active ? 'text-slate-500 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-950/30' : 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/30' }}" title="{{ $user->is_active ? 'Desativar' : 'Ativar' }}"><x-dynamic-component :component="$user->is_active ? 'bi-person-x' : 'bi-person-check'" class="size-4" /></button></form>
                                                @endunless
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-5 py-14 text-center text-sm text-slate-500">Nenhum usuário encontrado com esses filtros.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="divide-y divide-slate-100 md:hidden dark:divide-slate-800">
                @forelse($users as $user)
                    @php($isParishRole = in_array($user->role?->sigla, $parishRoleSiglas, true))
                    <article class="p-4">
                        <div class="flex items-start gap-3">
                            <img class="size-11 rounded-full object-cover ring-2 ring-[#B3DEC1]" src="{{ $user->profile_photo_url ? asset($user->profile_photo_url) : 'https://ui-avatars.com/api/?background=750D37&color=fff&name=' . urlencode($user->name) }}" alt="">
                            <div class="min-w-0 flex-1">
                                <p class="font-semibold text-[#210124] dark:text-white">{{ $user->name }}</p>
                                <p class="truncate text-xs text-slate-500">{{ '@' . $user->username }} · {{ $user->email }}</p>
                                <div class="mt-2 flex flex-wrap gap-2"><span class="rounded-full px-2 py-1 text-[11px] font-semibold {{ $isParishRole ? 'bg-[#E5FCF5] text-[#0f9688]' : 'bg-amber-50 text-amber-700' }}">{{ $user->role?->name ?? 'Sem perfil' }}{{ $isParishRole ? '' : ' · legado' }}</span><span class="rounded-full px-2 py-1 text-[11px] font-semibold {{ $user->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">{{ $user->is_active ? 'Ativo' : 'Inativo' }}</span></div>
                            </div>
                            @if(auth()->user()->role?->sigla === 'ADMIN' || $user->role?->sigla !== 'ADMIN')
                                @can('users.update')<button type="button" data-open-user="{{ $user->id }}" class="inline-flex size-9 items-center justify-center rounded-lg bg-slate-50 text-slate-500 dark:bg-slate-800"><x-bi-pencil-square class="size-4" /></button>@endcan
                            @endif
                        </div>
                        @if(! $user->is(auth()->user()) && (auth()->user()->role?->sigla === 'ADMIN' || $user->role?->sigla !== 'ADMIN'))
                            <div class="mt-3 flex gap-2 border-t border-slate-100 pt-3 dark:border-slate-800">
                                @can('users.reset-password')
                                    <form class="flex-1" action="{{ route('user.reset-password', $user) }}" method="POST" onsubmit="return confirm('Redefinir a senha de {{ addslashes($user->name) }}?')">@csrf<button class="inline-flex min-h-9 w-full items-center justify-center gap-2 rounded-lg border border-slate-200 text-[11px] font-semibold text-slate-600 dark:border-slate-700 dark:text-slate-300"><x-bi-key class="size-3.5" /> Redefinir senha</button></form>
                                @endcan
                                @can('users.status')
                                    <form class="flex-1" action="{{ route('user.status', $user) }}" method="POST" onsubmit="return confirm('{{ $user->is_active ? 'Desativar' : 'Ativar' }} o acesso de {{ addslashes($user->name) }}?')">@csrf @method('PATCH')<button class="inline-flex min-h-9 w-full items-center justify-center gap-2 rounded-lg border border-slate-200 text-[11px] font-semibold {{ $user->is_active ? 'text-red-600' : 'text-emerald-600' }} dark:border-slate-700"><x-dynamic-component :component="$user->is_active ? 'bi-person-x' : 'bi-person-check'" class="size-3.5" /> {{ $user->is_active ? 'Desativar' : 'Ativar' }}</button></form>
                                @endcan
                            </div>
                        @endif
                    </article>
                @empty
                    <p class="p-10 text-center text-sm text-slate-500">Nenhum usuário encontrado.</p>
                @endforelse
            </div>

            @if($users->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 dark:border-slate-800">{{ $users->links() }}</div>
            @endif
        </section>
    </div>

    @can('users.update')
        @foreach($users as $user)
            @continue(auth()->user()->role?->sigla !== 'ADMIN' && $user->role?->sigla === 'ADMIN')
            <dialog id="user-dialog-{{ $user->id }}" @if((int) old('_edit_user') === $user->id && $errors->any()) data-open-on-load @endif class="w-[calc(100%-2rem)] max-w-xl rounded-[1.25rem] bg-white p-0 text-[#210124] shadow-2xl backdrop:bg-[#210124]/55 backdrop:backdrop-blur-sm dark:bg-slate-900 dark:text-white">
                @php($hasErrors = (int) old('_edit_user') === $user->id && $errors->any())
                <form action="{{ route('user.update', $user) }}" method="POST" onsubmit="this.querySelector('button:not([type=button])').disabled = true">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_edit_user" value="{{ $user->id }}">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-slate-800">
                        <div><h2 class="font-bold">Editar usuário</h2><p class="mt-0.5 text-xs text-slate-500">{{ $user->name }}</p></div>
                        <button type="button" data-close-user="{{ $user->id }}" class="inline-flex size-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Fechar"><x-bi-x-lg class="size-4" /></button>
                    </div>
                    @if($hasErrors)<div class="mx-5 mt-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300"><ul class="list-disc space-y-1 pl-4">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
                    <div class="grid gap-4 p-5 sm:grid-cols-2">
                        <label class="sm:col-span-2"><span class="mb-1.5 block text-xs font-semibold">Nome completo</span><input name="name" value="{{ $hasErrors ? old('name') : $user->name }}" required class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950"></label>
                        <label><span class="mb-1.5 block text-xs font-semibold">E-mail</span><input type="email" name="email" value="{{ $hasErrors ? old('email') : $user->email }}" required class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950"></label>
                        <label><span class="mb-1.5 block text-xs font-semibold">Usuário</span><input name="username" value="{{ $hasErrors ? old('username') : $user->username }}" required class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950"></label>
                        <label class="sm:col-span-2"><span class="mb-1.5 block text-xs font-semibold">Perfil de acesso</span><select name="role_id" required @disabled($user->is(auth()->user())) class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] disabled:cursor-not-allowed disabled:bg-slate-100 dark:border-slate-700 dark:bg-slate-950 dark:disabled:bg-slate-800">@unless($roles->contains('id', $user->role_id))<option value="" selected disabled>Escolha um perfil paroquial</option>@endunless @foreach($roles as $role)<option value="{{ $role->id }}" @selected($user->role_id === $role->id)>{{ $role->name }}</option>@endforeach</select>@if(! $roles->contains('id', $user->role_id))<p class="mt-1.5 text-[11px] font-medium text-amber-600">O perfil atual é legado e precisa ser substituído.</p>@elseif($user->is(auth()->user()))<input type="hidden" name="role_id" value="{{ $user->role_id }}"><p class="mt-1.5 text-[11px] text-slate-500">Seu próprio perfil não pode ser alterado aqui.</p>@endif</label>
                    </div>
                    <div class="flex justify-end gap-3 border-t border-slate-100 px-5 py-4 dark:border-slate-800"><button type="button" data-close-user="{{ $user->id }}" class="min-h-10 rounded-xl border border-slate-300 px-4 text-xs font-semibold dark:border-slate-700">Cancelar</button><button class="min-h-10 rounded-xl bg-[#750D37] px-4 text-xs font-semibold text-white hover:bg-[#5f0b2e]">Salvar alterações</button></div>
                </form>
            </dialog>
        @endforeach
    @endcan

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('[data-open-user]').forEach(button => button.addEventListener('click', () => document.getElementById(`user-dialog-${button.dataset.openUser}`)?.showModal()));
                document.querySelectorAll('[data-close-user]').forEach(button => button.addEventListener('click', () => document.getElementById(`user-dialog-${button.dataset.closeUser}`)?.close()));
                document.querySelectorAll('dialog[id^="user-dialog-"]').forEach(dialog => dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); }));
                document.querySelector('dialog[data-open-on-load]')?.showModal();
                document.querySelector('[data-copy-password]')?.addEventListener('click', async () => {
                    const password = document.getElementById('temporary-password')?.textContent?.trim();
                    if (!password) return;
                    await navigator.clipboard.writeText(password);
                    window.Alerts?.showSuccess?.('Senha temporária copiada.');
                });
            });
        </script>
    @endpush
</x-layouts.app>
