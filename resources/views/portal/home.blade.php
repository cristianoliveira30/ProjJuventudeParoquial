<x-layouts.public title="Paróquia São Domingos de Gusmão">
    <section class="overflow-hidden bg-white">
        <div class="mx-auto grid min-h-[640px] max-w-[1600px] lg:grid-cols-[.92fr_1.08fr]">
            <div class="relative z-10 flex items-center px-5 py-16 sm:px-8 lg:px-12 xl:pl-[max(3rem,calc((100vw-1440px)/2+3rem))]">
                <div class="max-w-2xl">
                    <h1 class="font-serif text-5xl font-semibold leading-[1.04] tracking-[-.035em] text-[#210124] sm:text-6xl xl:text-7xl">Uma comunidade de fé, encontro e missão.</h1>
                    <p class="mt-7 max-w-xl text-lg leading-8 text-slate-600 sm:text-xl">Terra Firme, Av. São Domingos de Gusmão com Av. Celso Malcher</p>
                    <a href="{{ route('portal.missas') }}" class="mt-9 inline-flex min-h-13 items-center gap-3 rounded-xl bg-[#750D37] px-6 py-3.5 text-sm font-semibold text-white shadow-[0_14px_35px_rgba(117,13,55,.2)] transition hover:-translate-y-0.5 hover:bg-[#8d1042] focus:outline-none focus:ring-4 focus:ring-[#750D37]/20">
                        Ver horários de missa <x-bi-arrow-right class="size-4" />
                    </a>
                </div>
            </div>
            <div class="relative min-h-[390px] lg:min-h-full">
                <div class="absolute inset-y-0 left-0 z-10 hidden w-28 bg-gradient-to-r from-white to-transparent lg:block"></div>
                <img src="{{ asset('assets/img/portal/ramos.jpeg') }}" class="h-full w-full object-cover object-center rounded" alt="Comunidade reunida no interior da igreja após a celebração">
            </div>
        </div>

        <div class="relative z-20 mx-auto -mt-1 max-w-[1440px] px-5 pb-14 sm:px-8 lg:-mt-16 xl:px-12">
            <div class="grid overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-[0_18px_55px_rgba(33,1,36,.12)] md:grid-cols-3">
                <a href="{{ route('portal.missas') }}" class="group flex items-center gap-4 border-b border-slate-200 p-6 transition hover:bg-[#E5FCF5]/55 md:border-b-0 md:border-r">
                    <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-[#E5FCF5] text-[#006f67]"><x-bi-clock class="size-6" /></span>
                    <span class="min-w-0 flex-1"><span class="block break-words font-serif text-lg font-semibold">Próxima missa</span><span class="mt-1 block text-sm font-semibold text-[#008f83]">Hoje, 19h</span></span>
                    <x-bi-chevron-right class="ml-auto size-4 shrink-0 text-slate-400 transition group-hover:translate-x-1" />
                </a>
                <a href="{{ route('portal.liturgia') }}" class="group flex items-center gap-4 border-b border-slate-200 p-6 transition hover:bg-[#E5FCF5]/55 md:border-b-0 md:border-r">
                    <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-[#E5FCF5] text-[#006f67]"><x-bi-book class="size-6" /></span>
                    <span class="min-w-0 flex-1"><span class="block break-words font-serif text-lg font-semibold">Liturgia de hoje</span><span class="mt-1 block text-sm font-semibold text-[#008f83]">Leituras, salmo e Evangelho</span></span>
                    <x-bi-chevron-right class="ml-auto size-4 shrink-0 text-slate-400 transition group-hover:translate-x-1" />
                </a>
                <a href="{{ route('portal.paroquia') }}#secretaria" class="group flex items-center gap-4 p-6 transition hover:bg-[#E5FCF5]/55">
                    <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-[#E5FCF5] text-[#006f67]"><x-bi-headset class="size-6" /></span>
                    <span class="min-w-0 flex-1"><span class="block break-words font-serif text-lg font-semibold">Atendimento paroquial</span><span class="mt-1 block text-sm font-semibold text-[#008f83]">Segunda a sexta</span></span>
                    <x-bi-chevron-right class="ml-auto size-4 shrink-0 text-slate-400 transition group-hover:translate-x-1" />
                </a>
            </div>
            <p class="mt-3 text-center text-xs text-slate-400">Conteúdo demonstrativo nesta etapa visual. Horários oficiais serão publicados pela secretaria.</p>
        </div>
    </section>

    <section class="border-t border-slate-100 py-20 sm:py-24">
        <div class="mx-auto max-w-[1440px] px-5 sm:px-8 xl:px-12">
            <div class="flex items-end justify-between gap-6">
                <h2 class="font-serif text-4xl font-semibold tracking-[-.025em] sm:text-5xl">Notícias da paróquia</h2>
                <a href="{{ route('portal.noticias') }}" class="hidden items-center gap-2 text-sm font-semibold text-[#008f83] sm:inline-flex">Ver todas as notícias <x-bi-arrow-right class="size-4" /></a>
            </div>

            @if ($featuredPost)
                <div class="mt-12 grid gap-12 lg:grid-cols-[1.55fr_.85fr]">
                    <article class="grid gap-7 sm:grid-cols-[1.05fr_.95fr] sm:items-center">
                        <a href="{{ route('portal.noticias.show', $featuredPost) }}" class="block overflow-hidden rounded-2xl">
                            @if ($featuredPost->coverImageUrl())
                                <img src="{{ $featuredPost->coverImageUrl() }}" class="aspect-[4/3] h-full w-full object-cover transition duration-500 hover:scale-[1.025]" alt="Imagem de capa: {{ $featuredPost->title }}">
                            @else
                                <div class="flex aspect-[4/3] items-center justify-center bg-[#E5FCF5] text-[#008f83]"><x-bi-newspaper class="size-14" /></div>
                            @endif
                        </a>
                        <div>
                            <p class="text-sm font-medium text-[#008f83]">{{ $featuredPost->published_at->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
                            <h3 class="mt-3 font-serif text-3xl font-semibold leading-tight sm:text-4xl">{{ $featuredPost->title }}</h3>
                            <p class="mt-5 leading-7 text-slate-600">{{ $featuredPost->excerpt }}</p>
                            <a href="{{ route('portal.noticias.show', $featuredPost) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Ler notícia <x-bi-arrow-right class="size-4" /></a>
                        </div>
                    </article>

                    <div class="divide-y divide-slate-200 border-y border-slate-200 lg:border-y-0 lg:border-l lg:pl-10">
                        @foreach($recentPosts as $post)
                            <a href="{{ route('portal.noticias.show', $post) }}" class="group flex items-center gap-5 py-7 first:pt-0 last:pb-0">
                                <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-[#E5FCF5] text-[#008f83]"><x-bi-newspaper class="size-6" /></span>
                                <span class="min-w-0"><span class="block font-serif text-xl font-semibold leading-snug group-hover:text-[#750D37]">{{ $post->title }}</span><span class="mt-2 block text-sm text-slate-500">{{ $post->published_at->translatedFormat('d \\d\\e F \\d\\e Y') }}</span></span>
                                <x-bi-arrow-right class="ml-auto size-4 shrink-0 text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#008f83]" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="mt-12 rounded-2xl border border-dashed border-[#B3DEC1] bg-[#E5FCF5]/45 px-6 py-12 text-center sm:px-12">
                    <x-bi-newspaper class="mx-auto size-10 text-[#008f83]" />
                    <h3 class="mt-4 font-serif text-3xl font-semibold">As notícias da comunidade chegarão aqui</h3>
                    <p class="mx-auto mt-3 max-w-xl leading-7 text-slate-600">A equipe pastoral poderá publicar celebrações, avisos e encontros pelo painel de gestão.</p>
                </div>
            @endif

            <a href="{{ route('portal.noticias') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83] sm:hidden">Ver todas as notícias <x-bi-arrow-right class="size-4" /></a>
        </div>
    </section>

    <section class="px-5 pb-20 sm:px-8 sm:pb-24 xl:px-12">
        <div class="mx-auto max-w-[1344px] rounded-[1.75rem] bg-[#E5FCF5]/75 px-6 py-10 sm:px-10 lg:px-12">
            <div class="flex items-center justify-between gap-6">
                <h2 class="font-serif text-3xl font-semibold sm:text-4xl">Agenda da semana</h2>
                <x-bi-calendar3 class="size-6 text-[#008f83]" />
            </div>
            <div class="mt-9 grid divide-y divide-[#B3DEC1] lg:grid-cols-4 lg:divide-x lg:divide-y-0">
                @forelse($upcomingEvents as $event)
                    <div class="flex gap-5 py-6 lg:px-7 lg:py-2 lg:first:pl-0 lg:last:pr-0">
                        <div class="shrink-0 text-center"><span class="block font-serif text-4xl font-semibold text-[#008f83]">{{ $event->starts_at->format('d') }}</span><span class="text-xs font-semibold tracking-wider text-[#008f83]">{{ Str::upper($event->starts_at->translatedFormat('D')) }}</span></div>
                        <div class="border-l border-[#76bfb4] pl-5"><p class="flex items-center gap-2 text-sm font-semibold text-[#008f83]"><x-bi-clock class="size-4" />{{ $event->starts_at->format('H\\hi') }}</p><a href="{{ route('portal.agenda.show', $event) }}" class="mt-3 block font-medium hover:text-[#750D37]">{{ $event->title }}</a></div>
                    </div>
                @empty
                    <div class="py-5 text-sm text-slate-500 lg:col-span-4">A agenda da comunidade será publicada em breve.</div>
                @endforelse
            </div>
            <a href="{{ route('portal.agenda') }}" class="mt-8 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Ver agenda completa <x-bi-arrow-right class="size-4" /></a>
        </div>
    </section>

    <section class="grid bg-[#fbfbfb] lg:grid-cols-2">
        <div class="min-h-[400px] overflow-hidden lg:min-h-[560px]">
            <img src="{{ asset('assets/img/portal/jovens.jpg') }}" class="h-full w-full object-cover" alt="Jovens reunidos em encontro de fé e partilha">
        </div>
        <div class="flex items-center px-6 py-16 sm:px-12 lg:px-16 xl:px-24">
            <div class="max-w-xl">
                <h2 class="font-serif text-4xl font-semibold tracking-[-.025em] sm:text-5xl">Juventude Paroquial</h2>
                <p class="mt-6 text-lg leading-8 text-slate-600">Fé, amizade e serviço para caminhar com Cristo e transformar a comunidade.</p>
                <a href="{{ route('portal.juventude') }}" class="mt-8 inline-flex items-center gap-3 text-base font-semibold text-[#008f83]">Conheça a juventude <x-bi-arrow-right class="size-4" /></a>
            </div>
        </div>
    </section>

    <section class="bg-[#210124] text-white">
        <div class="mx-auto flex max-w-[1440px] flex-col gap-10 px-5 py-16 sm:px-8 lg:flex-row lg:items-center lg:justify-between xl:px-12">
            <div class="flex max-w-4xl items-start gap-6">
                <x-bi-heart class="mt-1 hidden size-16 shrink-0 text-[#B3DEC1] sm:block" />
                <div><h2 class="font-serif text-4xl font-semibold tracking-[-.02em] sm:text-5xl">Dízimo é partilha, cuidado e missão.</h2><p class="mt-4 max-w-2xl text-lg leading-8 text-white/70">Sua contribuição sustenta a evangelização e as obras da nossa paróquia.</p></div>
            </div>
            <a href="{{ route('portal.dizimo') }}" class="inline-flex min-h-14 shrink-0 items-center justify-center gap-3 rounded-xl bg-[#750D37] px-7 py-4 text-sm font-semibold text-white transition hover:bg-[#8d1042]">Saiba como contribuir <x-bi-arrow-right class="size-4" /></a>
        </div>
    </section>
</x-layouts.public>
