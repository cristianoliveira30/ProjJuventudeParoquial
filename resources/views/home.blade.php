<x-layouts.app :title="__('Início — Gestão Paroquial')">
    @php
        $encounters = [
            ['day' => '24', 'month' => 'MAI', 'title' => 'Encontro de oração', 'time' => 'Sábado, 19h30', 'place' => 'Salão paroquial', 'icon' => 'calendar-heart'],
            ['day' => '31', 'month' => 'MAI', 'title' => 'Formação de servos', 'time' => 'Sábado, 15h', 'place' => 'Sala de formação', 'icon' => 'heart'],
            ['day' => '07', 'month' => 'JUN', 'title' => 'Encontro das equipes', 'time' => 'Sábado, 16h', 'place' => 'Salão paroquial', 'icon' => 'people'],
        ];

        $quickActions = [
            ['label' => 'Adicionar usuário', 'icon' => 'person-plus', 'route' => 'user.create', 'permission' => 'users.create'],
            ['label' => 'Nova equipe', 'icon' => 'person-hearts', 'route' => '#'],
            ['label' => 'Novo evento', 'icon' => 'calendar-plus', 'route' => '#'],
            ['label' => 'Novo retiro', 'icon' => 'heart', 'route' => '#'],
            ['label' => 'Gerar relatório', 'icon' => 'file-earmark-text', 'route' => 'report.default'],
            ['label' => 'Enviar foto', 'icon' => 'image', 'route' => '#'],
        ];
    @endphp

    <div class="mx-auto w-full max-w-[92rem] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <section class="overflow-hidden rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-6 shadow-[0_10px_35px_rgba(20,184,166,.06)] dark:border-emerald-900/40 dark:bg-emerald-950/20 sm:p-8 lg:p-10">
            <div class="flex flex-col gap-7 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex max-w-3xl items-center gap-5">
                    <div class="hidden size-24 shrink-0 items-center justify-center rounded-full bg-white/60 sm:flex dark:bg-white/5">
                        <img src="{{ asset('assets/img/Ativo 2.svg') }}" class="h-18 w-auto opacity-90 dark:hidden" alt="">
                        <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="hidden h-18 w-auto opacity-90 dark:block" alt="">
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold tracking-[-0.035em] text-[#210124] sm:text-4xl dark:text-white">
                            Olá, {{ Str::before(auth()->user()->name ?? 'seja bem-vindo', ' ') }}!
                        </h1>
                        <p class="mt-3 max-w-2xl text-base leading-7 text-slate-600 dark:text-slate-300 sm:text-lg">
                            Organize a vida da paróquia, suas pastorais e comunidades em um só lugar.
                        </p>
                    </div>
                </div>

                <div class="flex w-full flex-col gap-3 sm:w-auto sm:min-w-64">
                    @can('users.create')
                        <a href="{{ route('user.create') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#14B8A6] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(20,184,166,.24)] transition hover:bg-[#0f9f91] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/45 focus:ring-offset-2">
                            <x-bi-plus-lg class="size-4" /> Adicionar usuário
                        </a>
                    @endcan
                    <a href="{{ safe_route('#') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl border border-[#14B8A6] bg-white px-5 text-sm font-semibold text-[#0f9688] transition hover:bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/35 dark:bg-slate-900 dark:text-[#5eead4]">
                        <x-bi-calendar-plus class="size-4" /> Criar evento
                    </a>
                </div>
            </div>
        </section>

        <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_minmax(24rem,.85fr)]">
            <section class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6 dark:border-slate-800">
                    <h2 class="text-lg font-bold text-[#210124] dark:text-white">Próximos encontros</h2>
                    <a href="{{ safe_route('#') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0f9688] hover:text-[#750D37]">Ver agenda <x-bi-arrow-right class="size-3.5" /></a>
                </div>

                <div class="divide-y divide-slate-100 px-5 sm:px-6 dark:divide-slate-800">
                    @foreach($encounters as $encounter)
                        <a href="{{ safe_route('#') }}" class="group grid grid-cols-[3.25rem_3rem_minmax(0,1fr)_auto] items-center gap-3 py-4 transition sm:grid-cols-[3.5rem_4rem_minmax(0,1fr)_auto] sm:gap-4">
                            <span class="flex size-12 items-center justify-center rounded-full bg-[#E5FCF5] text-[#14B8A6] sm:size-13 dark:bg-emerald-950/50">
                                <x-dynamic-component :component="'bi-' . $encounter['icon']" class="size-5" />
                            </span>
                            <span class="text-center leading-none">
                                <span class="block text-xl font-bold text-[#750D37] dark:text-rose-300">{{ $encounter['day'] }}</span>
                                <span class="mt-1 block text-[10px] font-semibold text-slate-500">{{ $encounter['month'] }}</span>
                            </span>
                            <span class="min-w-0">
                                <span class="block truncate text-sm font-semibold text-[#210124] dark:text-white">{{ $encounter['title'] }}</span>
                                <span class="mt-1 block text-xs text-slate-500 dark:text-slate-400">{{ $encounter['time'] }} · {{ $encounter['place'] }}</span>
                            </span>
                            <x-bi-chevron-right class="size-4 text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#14B8A6]" />
                        </a>
                    @endforeach
                </div>

                <a href="{{ safe_route('#') }}" class="flex items-center justify-center gap-2 border-t border-slate-100 py-4 text-xs font-semibold text-[#0f9688] hover:bg-[#E5FCF5]/50 dark:border-slate-800 dark:hover:bg-slate-800">Ver todos os encontros <x-bi-arrow-right class="size-3.5" /></a>
            </section>

            <div class="space-y-6">
                <section class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <h2 class="text-lg font-bold text-[#210124] dark:text-white">Acesso rápido</h2>
                    <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($quickActions as $action)
                            @continue(isset($action['permission']) && !auth()->user()?->can($action['permission']))
                            <a href="{{ safe_route($action['route']) }}" class="group flex min-h-24 flex-col items-center justify-center gap-2 rounded-xl border border-[#B3DEC1]/60 bg-[#E5FCF5]/45 px-3 text-center transition hover:-translate-y-0.5 hover:border-[#14B8A6] hover:bg-[#E5FCF5] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/35 dark:border-slate-700 dark:bg-slate-800/60 dark:hover:border-[#14B8A6]">
                                <x-dynamic-component :component="'bi-' . $action['icon']" class="size-5 text-[#14B8A6]" />
                                <span class="text-xs font-semibold text-[#210124] dark:text-white">{{ $action['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 sm:px-6 dark:border-slate-800">
                        <h2 class="text-lg font-bold text-[#210124] dark:text-white">Retiro Vide e Vede</h2>
                        <a href="{{ safe_route('#') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[#0f9688]">Ver detalhes <x-bi-arrow-right class="size-3.5" /></a>
                    </div>
                    <div class="flex flex-col gap-5 p-5 sm:flex-row sm:items-center sm:p-6">
                        <div class="flex size-24 shrink-0 items-center justify-center rounded-full bg-[#E5FCF5] text-[#750D37] dark:bg-rose-950/30 dark:text-rose-200"><x-bi-heart class="size-10" /></div>
                        <div>
                            <p class="text-sm leading-6 text-slate-600 dark:text-slate-300">Um espaço de encontro, serviço e descoberta do propósito de Deus para a vida dos nossos jovens.</p>
                            <p class="mt-3 flex items-center gap-2 text-xs font-medium text-slate-500 dark:text-slate-400"><x-bi-geo-alt class="size-3.5" /> Paróquia São Domingos de Gusmão</p>
                        </div>
                    </div>
                    <a href="{{ safe_route('#') }}" class="m-5 mt-0 flex min-h-11 items-center justify-center rounded-xl bg-[#E5FCF5] text-xs font-semibold text-[#0f9688] transition hover:bg-[#B3DEC1]/60 dark:bg-emerald-950/30 dark:text-emerald-200 sm:m-6 sm:mt-0">Acompanhar organização</a>
                </section>
            </div>
        </div>
    </div>
</x-layouts.app>
