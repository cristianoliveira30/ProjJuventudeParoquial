<x-layouts.app title="Segurança da conta — Paróquia São Domingos de Gusmão">
    <div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="mb-6">
            <p class="text-xs font-semibold uppercase tracking-[.18em] text-[#0f9688]">Minha conta</p>
            <h1 class="mt-2 text-3xl font-bold tracking-[-0.035em] text-[#210124] dark:text-white">Senha e segurança</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Troque sua senha sempre que suspeitar de acesso indevido. Sua senha atual será exigida para confirmar a alteração.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-200">
                <x-bi-check-circle class="mt-0.5 size-4 shrink-0" /> {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_19rem]">
            <form action="{{ route('account.password.update') }}" method="POST" class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900 sm:p-7">
                @csrf
                @method('PUT')

                <div class="space-y-5">
                    <div>
                        <label for="current_password" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Senha atual</label>
                        <input id="current_password" type="password" name="current_password" required autocomplete="current-password"
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('current_password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="password" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Nova senha</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Confirmar nova senha</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="mt-7 flex justify-end border-t border-slate-100 pt-6 dark:border-slate-800">
                    <button type="submit" class="inline-flex min-h-11 w-full items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(117,13,55,.22)] transition hover:bg-[#5f0b2e] focus:outline-none focus:ring-2 focus:ring-[#750D37]/40 focus:ring-offset-2 sm:w-auto">
                        <x-bi-shield-check class="size-4" /> Atualizar senha
                    </button>
                </div>
            </form>

            <aside class="rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-5 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                <span class="flex size-10 items-center justify-center rounded-full bg-white text-[#750D37] dark:bg-slate-900 dark:text-rose-300"><x-bi-lock class="size-5" /></span>
                <h2 class="mt-4 text-sm font-bold text-[#210124] dark:text-white">Uma boa senha</h2>
                <ul class="mt-3 space-y-2 text-xs leading-5 text-slate-600 dark:text-slate-300">
                    <li class="flex gap-2"><x-bi-check2 class="mt-1 size-3.5 shrink-0 text-[#14B8A6]" /> Tem ao menos 10 caracteres</li>
                    <li class="flex gap-2"><x-bi-check2 class="mt-1 size-3.5 shrink-0 text-[#14B8A6]" /> Combina maiúsculas e minúsculas</li>
                    <li class="flex gap-2"><x-bi-check2 class="mt-1 size-3.5 shrink-0 text-[#14B8A6]" /> Inclui número e símbolo</li>
                    <li class="flex gap-2"><x-bi-check2 class="mt-1 size-3.5 shrink-0 text-[#14B8A6]" /> Não é reutilizada em outros serviços</li>
                </ul>
            </aside>
        </div>
    </div>
</x-layouts.app>
