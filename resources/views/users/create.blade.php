<x-layouts.app title="Novo usuário — Paróquia São Domingos de Gusmão">
    <div class="mx-auto w-full max-w-5xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <a href="{{ route('user.search') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0f9688] hover:text-[#750D37]">
                    <x-bi-arrow-left class="size-3.5" /> Voltar para usuários
                </a>
                <h1 class="mt-3 text-3xl font-bold tracking-[-0.035em] text-[#210124] dark:text-white">Adicionar usuário</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-300">Crie um acesso para alguém da secretaria, comunicação ou coordenação pastoral.</p>
            </div>
            <span class="inline-flex w-fit items-center gap-2 rounded-full bg-[#E5FCF5] px-3 py-1.5 text-xs font-semibold text-[#0f9688] dark:bg-emerald-950/40 dark:text-emerald-300">
                <x-bi-shield-check class="size-4" /> Acesso administrativo
            </span>
        </div>

        <form action="{{ route('user.store') }}" method="POST" class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_19rem]">
            @csrf

            <section class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900 sm:p-7">
                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Nome completo</label>
                        <input id="name" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('name')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">E-mail</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email"
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('email')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label for="username" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Usuário de acesso</label>
                        <input id="username" name="username" value="{{ old('username') }}" required autocomplete="off" placeholder="ex.: maria.silva"
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        @error('username')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="role_id" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Perfil de acesso</label>
                        <select id="role_id" name="role_id" required
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                            <option value="">Selecione o que esta pessoa poderá fazer</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }} — {{ $role->description }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="temporary_password" class="mb-2 block text-sm font-semibold text-[#210124] dark:text-white">Senha temporária <span class="font-normal text-slate-400">(opcional)</span></label>
                        <input id="temporary_password" type="password" name="temporary_password" autocomplete="new-password"
                            class="block min-h-12 w-full rounded-xl border-slate-300 bg-white text-sm shadow-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white">
                        <p class="mt-2 text-xs leading-5 text-slate-500 dark:text-slate-400">Se ficar em branco, o sistema gera uma senha segura. Uma senha informada deve ter 10 caracteres, maiúscula, minúscula, número e símbolo.</p>
                        @error('temporary_password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-7 flex flex-col-reverse gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:justify-end dark:border-slate-800">
                    <a href="{{ route('user.search') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 px-5 text-sm font-semibold text-slate-600 hover:bg-slate-50 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-800">Cancelar</a>
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(117,13,55,.22)] transition hover:bg-[#5f0b2e] focus:outline-none focus:ring-2 focus:ring-[#750D37]/40 focus:ring-offset-2">
                        <x-bi-person-plus class="size-4" /> Criar acesso
                    </button>
                </div>
            </section>

            <aside class="space-y-4">
                <div class="rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-5 dark:border-emerald-900/50 dark:bg-emerald-950/20">
                    <span class="flex size-10 items-center justify-center rounded-full bg-white text-[#750D37] dark:bg-slate-900 dark:text-rose-300"><x-bi-key class="size-5" /></span>
                    <h2 class="mt-4 text-sm font-bold text-[#210124] dark:text-white">Primeiro acesso protegido</h2>
                    <p class="mt-2 text-xs leading-5 text-slate-600 dark:text-slate-300">A senha temporária será exibida uma única vez. Ao entrar, o novo usuário deverá substituí-la antes de acessar o painel.</p>
                </div>
                <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-sm font-bold text-[#210124] dark:text-white">Perfis disponíveis</h2>
                    <div class="mt-3 space-y-3">
                        @foreach($roles as $role)
                            <div>
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200">{{ $role->name }}</p>
                                <p class="mt-0.5 text-[11px] leading-4 text-slate-500 dark:text-slate-400">{{ $role->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </aside>
        </form>
    </div>
</x-layouts.app>
