<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Paróquia São Domingos de Gusmão' }}</title>
    <meta name="description" content="Portal da Paróquia São Domingos de Gusmão: celebrações, liturgia, notícias, pastorais, juventude e dízimo.">
    <link rel="icon" href="{{ asset('assets/img/Ativo 2.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:500,600,700" rel="stylesheet">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @stack('head')
</head>
<body class="min-h-dvh bg-white font-sans text-[#210124] antialiased">
    <a href="#conteudo" class="fixed left-4 top-3 z-[10000] -translate-y-20 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-[#210124] shadow-lg transition focus:translate-y-0">Ir para o conteúdo</a>

    @php
        $portalNav = [
            ['label' => 'Início', 'route' => 'portal.home'],
            ['label' => 'A Paróquia', 'route' => 'portal.paroquia', 'active' => 'portal.paroquia'],
            ['label' => 'Pastorais', 'route' => 'portal.paroquia', 'fragment' => '#pastorais', 'active' => 'portal.pastorais.*'],
            ['label' => 'Juventude', 'route' => 'portal.juventude'],
            ['label' => 'Notícias', 'route' => 'portal.noticias'],
            ['label' => 'Agenda', 'route' => 'portal.agenda'],
            ['label' => 'Galeria', 'route' => 'portal.galeria'],
        ];
        $adminUrl = auth()->check() ? route('home') : route('login');
        $adminLabel = auth()->check() ? 'Painel administrativo' : 'Área administrativa';
    @endphp

    <header class="relative z-[100] bg-[#210124] text-white shadow-[0_10px_35px_rgba(33,1,36,.12)]">
        <nav class="mx-auto flex min-h-24 max-w-[1440px] items-center justify-between gap-6 px-5 sm:px-8 xl:px-12" aria-label="Navegação principal">
            <a href="{{ route('portal.home') }}" class="flex shrink-0 items-center gap-3.5" aria-label="Página inicial da Paróquia São Domingos de Gusmão">
                <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="h-16 w-16 object-contain" alt="">
                <span class="font-serif text-lg font-semibold leading-[1.15] sm:text-xl">Paróquia São Domingos<br>de Gusmão</span>
            </a>

            <div class="hidden items-center gap-1 xl:flex">
                @foreach(array_slice($portalNav, 0, 2) as $item)
                    @php($isPortalActive = request()->routeIs($item['active'] ?? $item['route'], ...(!isset($item['active']) ? [$item['route'] . '.*'] : [])))
                    <a href="{{ route($item['route']) }}{{ $item['fragment'] ?? '' }}"
                        @class([
                            'rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#14B8A6]',
                            'bg-[#750D37] text-white' => $isPortalActive,
                            'text-white/80' => !$isPortalActive,
                        ])>{{ $item['label'] }}</a>
                @endforeach

                <button id="celebracoes-menu-button" data-dropdown-toggle="celebracoes-mega-menu" data-dropdown-placement="bottom"
                    type="button" class="inline-flex items-center gap-1 rounded-lg px-3 py-2 text-sm font-medium text-white/80 transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#14B8A6]" aria-expanded="false">
                    Celebrações <x-bi-chevron-down class="size-3" />
                </button>

                @foreach(array_slice($portalNav, 2) as $item)
                    @php($isPortalActive = request()->routeIs($item['active'] ?? $item['route'], ...(!isset($item['active']) ? [$item['route'] . '.*'] : [])))
                    <a href="{{ route($item['route']) }}{{ $item['fragment'] ?? '' }}"
                        @class([
                            'rounded-lg px-3 py-2 text-sm font-medium transition hover:bg-white/10 hover:text-white focus:outline-none focus:ring-2 focus:ring-[#14B8A6]',
                            'bg-[#750D37] text-white' => $isPortalActive,
                            'text-white/80' => !$isPortalActive,
                        ])>{{ $item['label'] }}</a>
                @endforeach
            </div>

            <div class="hidden shrink-0 items-center gap-4 xl:flex">
                <a href="{{ $adminUrl }}" class="border-b border-white/35 pb-0.5 text-xs font-medium text-white/75 transition hover:border-[#14B8A6] hover:text-white">{{ $adminLabel }}</a>
                <a href="{{ route('portal.dizimo') }}" class="rounded-xl border border-white/20 bg-[#750D37] px-4 py-3 text-sm font-semibold text-white transition hover:bg-[#8d1042] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]">Contribua com o dízimo</a>
            </div>

            <button type="button" data-collapse-toggle="public-mobile-menu" aria-controls="public-mobile-menu" aria-expanded="false"
                class="inline-flex size-11 items-center justify-center rounded-xl border border-white/15 text-white xl:hidden">
                <span class="sr-only">Abrir menu</span>
                <x-bi-list class="size-6" />
            </button>
        </nav>

        <div id="public-mobile-menu" class="hidden border-t border-white/10 bg-[#210124] px-5 pb-6 xl:hidden">
            <div class="mx-auto grid max-w-[1440px] gap-1 pt-4">
                @foreach($portalNav as $item)
                    <a href="{{ route($item['route']) }}{{ $item['fragment'] ?? '' }}" class="rounded-lg px-3 py-2.5 text-sm font-medium text-white/85 hover:bg-white/10">{{ $item['label'] }}</a>
                @endforeach
                <p class="mt-3 border-t border-white/10 px-3 pt-4 text-xs font-semibold uppercase tracking-[.18em] text-[#B3DEC1]">Celebrações</p>
                <a href="{{ route('portal.missas') }}" class="rounded-lg px-3 py-2.5 text-sm text-white/80 hover:bg-white/10">Horários de missa</a>
                <a href="{{ route('portal.liturgia') }}" class="rounded-lg px-3 py-2.5 text-sm text-white/80 hover:bg-white/10">Liturgia diária</a>
                <a href="{{ route('portal.dizimo') }}" class="mt-3 rounded-xl bg-[#750D37] px-4 py-3 text-center text-sm font-semibold text-white">Contribua com o dízimo</a>
                <a href="{{ $adminUrl }}" class="px-3 pt-3 text-center text-xs text-white/65">{{ $adminLabel }}</a>
            </div>
        </div>

        <div id="celebracoes-mega-menu" class="z-[120] hidden w-[min(92vw,62rem)] rounded-2xl border border-slate-200 bg-white text-[#210124] shadow-[0_24px_70px_rgba(33,1,36,.2)]">
            <div class="grid gap-7 p-7 md:grid-cols-4">
                <div>
                    <h2 class="font-serif text-xl font-semibold">Horários de Missa</h2>
                    <div class="mt-4 rounded-xl border border-[#B3DEC1] bg-[#E5FCF5]/65 p-4">
                        <p class="text-sm font-semibold">Domingo</p>
                        <p class="mt-1 text-sm leading-6 text-slate-600">07h30 · 09h00<br>10h30 · 19h00</p>
                    </div>
                    <a href="{{ route('portal.missas') }}" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Ver todos os horários <x-bi-arrow-right class="size-4" /></a>
                </div>
                <div>
                    <h2 class="font-serif text-xl font-semibold">Liturgia Diária</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><x-bi-book class="size-4 text-[#009c90]" /> Leituras do dia</li>
                        <li class="flex items-center gap-2"><x-bi-music-note-beamed class="size-4 text-[#009c90]" /> Salmo responsorial</li>
                        <li class="flex items-center gap-2"><x-bi-cup-straw class="size-4 text-[#009c90]" /> Evangelho do dia</li>
                    </ul>
                    <a href="{{ route('portal.liturgia') }}" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Acessar liturgia <x-bi-arrow-right class="size-4" /></a>
                </div>
                <div>
                    <h2 class="font-serif text-xl font-semibold">Confissões</h2>
                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                        <li class="flex items-center gap-2"><x-bi-person-check class="size-4 text-[#009c90]" /> Horários de confissão</li>
                        <li class="flex items-center gap-2"><x-bi-question-circle class="size-4 text-[#009c90]" /> Como se preparar</li>
                        <li class="flex items-center gap-2"><x-bi-heart class="size-4 text-[#009c90]" /> Exame de consciência</li>
                    </ul>
                    <a href="{{ route('portal.paroquia') }}#secretaria" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Saiba mais <x-bi-arrow-right class="size-4" /></a>
                </div>
                <div>
                    <h2 class="font-serif text-xl font-semibold">Intenções</h2>
                    <p class="mt-4 text-sm leading-6 text-slate-600">Entre em contato com a secretaria para informar sua intenção de missa.</p>
                    <a href="{{ route('portal.paroquia') }}#secretaria" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Falar com a secretaria <x-bi-arrow-right class="size-4" /></a>
                </div>
            </div>
        </div>
    </header>

    <main id="conteudo">{{ $slot }}</main>

    <footer class="bg-[#210124] text-white">
        <div class="mx-auto grid max-w-[1440px] gap-10 px-5 py-14 sm:px-8 md:grid-cols-2 xl:grid-cols-[1.5fr_repeat(3,1fr)] xl:px-12">
            <div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="h-16 w-16 object-contain" alt="">
                    <p class="font-serif text-xl font-semibold leading-tight">Paróquia São Domingos<br>de Gusmão</p>
                </div>
                <div class="mt-6 space-y-2 text-sm leading-6 text-white/65">
                    <p>Endereço da paróquia será informado aqui.</p>
                    <p>Telefone e atendimento da secretaria.</p>
                    <p>contato@saodomingos.org.br</p>
                </div>
            </div>
            <div>
                <h2 class="font-serif text-lg font-semibold">Celebrações</h2>
                <div class="mt-4 grid gap-2 text-sm text-white/65">
                    <a href="{{ route('portal.missas') }}" class="hover:text-[#B3DEC1]">Horários de missa</a>
                    <a href="{{ route('portal.liturgia') }}" class="hover:text-[#B3DEC1]">Liturgia diária</a>
                    <a href="{{ route('portal.paroquia') }}#secretaria" class="hover:text-[#B3DEC1]">Confissões</a>
                </div>
            </div>
            <div>
                <h2 class="font-serif text-lg font-semibold">Comunidade</h2>
                <div class="mt-4 grid gap-2 text-sm text-white/65">
                    <a href="{{ route('portal.paroquia') }}#pastorais" class="hover:text-[#B3DEC1]">Pastorais</a>
                    <a href="{{ route('portal.juventude') }}" class="hover:text-[#B3DEC1]">Juventude</a>
                    <a href="{{ route('portal.dizimo') }}" class="hover:text-[#B3DEC1]">Dízimo</a>
                    <a href="{{ route('portal.galeria') }}" class="hover:text-[#B3DEC1]">Galeria</a>
                </div>
            </div>
            <div>
                <h2 class="font-serif text-lg font-semibold">Secretaria</h2>
                <div class="mt-4 grid gap-2 text-sm text-white/65">
                    <a href="{{ route('portal.paroquia') }}#secretaria" class="hover:text-[#B3DEC1]">Contato</a>
                    <a href="{{ route('portal.noticias') }}" class="hover:text-[#B3DEC1]">Notícias</a>
                    <a href="{{ $adminUrl }}" class="mt-3 inline-flex items-center gap-2 text-[#B3DEC1]"><x-bi-lock class="size-4" /> {{ $adminLabel }}</a>
                </div>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="mx-auto flex max-w-[1440px] flex-col gap-3 px-5 py-5 text-xs text-white/45 sm:px-8 md:flex-row md:items-center md:justify-between xl:px-12">
                <p>© {{ date('Y') }} Paróquia São Domingos de Gusmão.</p>
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:gap-4">
                    <p>Uma comunidade de fé, encontro e missão.</p>
                    <span class="hidden h-3 w-px bg-white/15 sm:block" aria-hidden="true"></span>
                    <div class="flex items-center gap-2 text-white/30 transition hover:text-white/60 focus-within:text-white/70">
                        <span>Trabalho voluntário feito por {{ config('parish.volunteer_author.name') }}</span>
                        <a href="{{ config('parish.volunteer_author.github_url') }}" target="_blank" rel="noopener noreferrer" class="rounded p-1 transition hover:text-[#B3DEC1] focus:outline-none focus:ring-2 focus:ring-[#B3DEC1]/50" aria-label="GitHub de {{ config('parish.volunteer_author.name') }}"><x-bi-github class="size-3.5" /></a>
                        <a href="{{ config('parish.volunteer_author.linkedin_url') }}" target="_blank" rel="noopener noreferrer" class="rounded p-1 transition hover:text-[#B3DEC1] focus:outline-none focus:ring-2 focus:ring-[#B3DEC1]/50" aria-label="LinkedIn de {{ config('parish.volunteer_author.name') }}"><x-bi-linkedin class="size-3.5" /></a>
                        <a href="{{ route('portal.apoio') }}" class="rounded p-1 transition hover:text-rose-300 focus:outline-none focus:ring-2 focus:ring-rose-300/50" aria-label="Apoiar o trabalho voluntário"><x-bi-heart-fill class="size-3.5" /></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
