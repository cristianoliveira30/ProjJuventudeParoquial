<x-layouts.app :title="__('Enviar notificação')">
    <div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#14B8A6]">Comunicação interna</p>
            <h1 class="mt-1 text-3xl font-bold tracking-tight text-[#210124] dark:text-white">Enviar notificação</h1>
            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Envie avisos para todos, para perfis de acesso ou para pessoas específicas.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800 dark:border-rose-900 dark:bg-rose-950/40 dark:text-rose-200">
                <ul class="list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('notifications.create') }}" class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
            @csrf
            <div class="space-y-7 p-5 sm:p-7">
                <fieldset>
                    <legend class="text-sm font-bold text-[#210124] dark:text-white">Destinatários</legend>
                    <div class="mt-3 grid gap-3 sm:grid-cols-2">
                        <label class="cursor-pointer rounded-xl border border-slate-200 p-4 transition has-[:checked]:border-[#14B8A6] has-[:checked]:bg-[#E5FCF5] dark:border-slate-700 dark:has-[:checked]:bg-emerald-950/30">
                            <span class="flex items-start gap-3">
                                <input type="radio" name="destino" value="todos" @checked(old('destino', 'todos') === 'todos') class="mt-1 text-[#14B8A6] focus:ring-[#14B8A6]">
                                <span><span class="block text-sm font-semibold text-slate-800 dark:text-white">Todos os usuários ativos</span><span class="mt-1 block text-xs text-slate-500">Alcança toda a equipe cadastrada.</span></span>
                            </span>
                        </label>
                        <label class="cursor-pointer rounded-xl border border-slate-200 p-4 transition has-[:checked]:border-[#14B8A6] has-[:checked]:bg-[#E5FCF5] dark:border-slate-700 dark:has-[:checked]:bg-emerald-950/30">
                            <span class="flex items-start gap-3">
                                <input type="radio" name="destino" value="filtros" @checked(old('destino') === 'filtros') class="mt-1 text-[#14B8A6] focus:ring-[#14B8A6]">
                                <span><span class="block text-sm font-semibold text-slate-800 dark:text-white">Selecionar destinatários</span><span class="mt-1 block text-xs text-slate-500">Escolha pessoas e/ou perfis de acesso.</span></span>
                            </span>
                        </label>
                    </div>
                </fieldset>

                <div id="notification-targets" class="hidden grid gap-5 rounded-xl border border-slate-200 bg-slate-50/70 p-4 dark:border-slate-700 dark:bg-slate-950/40 md:grid-cols-2">
                    <fieldset>
                        <legend class="text-sm font-semibold text-slate-700 dark:text-slate-200">Perfis de acesso</legend>
                        <div class="mt-3 max-h-52 space-y-2 overflow-y-auto pr-1">
                            @foreach($roles as $role)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-white px-3 py-2.5 text-sm shadow-sm dark:bg-slate-900">
                                    <input type="checkbox" name="roles[]" value="{{ $role->id }}" @checked(in_array($role->id, old('roles', []))) class="rounded text-[#14B8A6] focus:ring-[#14B8A6]">
                                    <span>{{ $role->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="text-sm font-semibold text-slate-700 dark:text-slate-200">Usuários específicos</legend>
                        <div class="mt-3 max-h-52 space-y-2 overflow-y-auto pr-1">
                            @foreach($users as $user)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg bg-white px-3 py-2.5 text-sm shadow-sm dark:bg-slate-900">
                                    <input type="checkbox" name="users[]" value="{{ $user->id }}" @checked(in_array($user->id, old('users', []))) class="rounded text-[#14B8A6] focus:ring-[#14B8A6]">
                                    <span class="min-w-0"><span class="block truncate font-medium">{{ $user->name }}</span><span class="block truncate text-xs text-slate-500">{{ $user->email }}</span></span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </div>

                <div class="grid gap-5">
                    <label>
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-200">Título</span>
                        <input name="title" value="{{ old('title') }}" maxlength="150" required class="block min-h-11 w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950" placeholder="Ex.: Reunião do conselho pastoral">
                    </label>
                    <label>
                        <span class="mb-1.5 block text-sm font-semibold text-slate-700 dark:text-slate-200">Mensagem</span>
                        <textarea name="message" rows="5" maxlength="1000" required class="block w-full rounded-xl border-slate-300 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950" placeholder="Escreva o aviso que será enviado...">{{ old('message') }}</textarea>
                    </label>
                </div>
            </div>

            <div class="flex justify-end border-t border-slate-100 bg-slate-50/70 px-5 py-4 dark:border-slate-800 dark:bg-slate-950/30 sm:px-7">
                <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#14B8A6] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(20,184,166,.22)] transition hover:bg-[#0f9f91] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/45">
                    <x-bi-send class="size-4" /> Enviar notificação
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const targets = document.getElementById('notification-targets');
                const syncTargets = () => targets?.classList.toggle('hidden', document.querySelector('input[name="destino"]:checked')?.value !== 'filtros');
                document.querySelectorAll('input[name="destino"]').forEach(input => input.addEventListener('change', syncTargets));
                syncTargets();
            });
        </script>
    @endpush
</x-layouts.app>
