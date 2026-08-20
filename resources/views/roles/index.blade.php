<x-layouts.app title="Perfis de acesso — Paróquia São Domingos de Gusmão">
    <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[.18em] text-[#0f9688]">Autorizações</p>
            <h1 class="mt-2 text-3xl font-bold tracking-[-0.035em] text-[#210124] dark:text-white">Perfis de acesso</h1>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600 dark:text-slate-300">Defina as ações permitidas para cada responsabilidade. O administrador permanece com acesso completo para evitar o bloqueio da gestão.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-200">
                <x-bi-check-circle class="mt-0.5 size-4 shrink-0" /> {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-5 lg:grid-cols-2">
            @foreach($roles as $role)
                <form action="{{ route('roles.update', $role) }}" method="POST" class="flex flex-col rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
                    @csrf
                    @method('PUT')
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 p-5 dark:border-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="flex size-11 shrink-0 items-center justify-center rounded-full {{ $role->sigla === 'ADMIN' ? 'bg-[#750D37] text-white' : 'bg-[#E5FCF5] text-[#0f9688] dark:bg-emerald-950/40 dark:text-emerald-300' }}"><x-bi-shield-check class="size-5" /></span>
                            <div><p class="text-xs font-semibold text-slate-400">{{ $role->sigla }}</p><h2 class="font-bold text-[#210124] dark:text-white">{{ $role->name }}</h2></div>
                        </div>
                        <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-semibold text-slate-500 dark:bg-slate-800 dark:text-slate-300">{{ $role->users_count }} usuário(s)</span>
                    </div>

                    <div class="flex-1 space-y-5 p-5">
                        <div class="grid gap-4 sm:grid-cols-2">
                            <label><span class="mb-1.5 block text-xs font-semibold">Nome do perfil</span><input name="name" value="{{ $role->name }}" required class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950"></label>
                            <label><span class="mb-1.5 block text-xs font-semibold">Descrição</span><input name="description" value="{{ $role->description }}" class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950"></label>
                        </div>

                        <fieldset>
                            <legend class="mb-3 text-xs font-bold uppercase tracking-wider text-slate-500">Permissões</legend>
                            @if($role->sigla === 'ADMIN')
                                <div class="flex gap-3 rounded-xl border border-[#B3DEC1]/70 bg-[#E5FCF5] p-4 text-sm text-slate-700 dark:border-emerald-900/50 dark:bg-emerald-950/20 dark:text-slate-200"><x-bi-patch-check class="mt-0.5 size-4 shrink-0 text-[#14B8A6]" /><p><strong>Acesso completo.</strong> Este perfil recebe automaticamente todas as permissões atuais e futuras.</p></div>
                            @else
                                <div class="grid gap-2 sm:grid-cols-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 p-3 transition hover:border-[#14B8A6] hover:bg-[#E5FCF5]/40 dark:border-slate-700 dark:hover:bg-emerald-950/20">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->slug }}" @checked($role->permissions->contains('id', $permission->id)) class="mt-0.5 rounded border-slate-300 text-[#750D37] focus:ring-[#14B8A6]">
                                            <span class="text-xs font-medium leading-5 text-slate-700 dark:text-slate-200">{{ $permission->description }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif
                        </fieldset>
                    </div>

                    <div class="flex justify-end border-t border-slate-100 px-5 py-4 dark:border-slate-800">
                        <button class="inline-flex min-h-10 items-center justify-center gap-2 rounded-xl bg-[#750D37] px-4 text-xs font-semibold text-white transition hover:bg-[#5f0b2e]"><x-bi-check2 class="size-4" /> Salvar perfil</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</x-layouts.app>
