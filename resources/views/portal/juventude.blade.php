<x-layouts.public title="Juventude Paroquial — São Domingos de Gusmão">
    <section class="grid min-h-[620px] bg-[#fbfbfb] lg:grid-cols-2">
        <div class="order-2 flex items-center px-5 py-16 sm:px-10 lg:order-1 lg:px-16 xl:pl-[max(4rem,calc((100vw-1440px)/2+3rem))]">
            <div class="max-w-xl">
                <h1 class="font-serif text-5xl font-semibold leading-[1.05] tracking-[-.035em] sm:text-6xl">Juventude Paroquial</h1>
                <p class="mt-6 text-xl leading-8 text-slate-600">Fé, amizade e serviço para caminhar com Cristo e transformar a comunidade.</p>
                <a href="#caminhada" class="mt-8 inline-flex items-center gap-3 rounded-xl bg-[#750D37] px-6 py-3.5 text-sm font-semibold text-white">Conheça nossa caminhada <x-bi-arrow-down class="size-4" /></a>
            </div>
        </div>
        <div class="order-1 min-h-[390px] overflow-hidden lg:order-2 lg:min-h-full">
            <img src="{{ asset('assets/img/portal/jovens02.jpg') }}" class="h-full w-full object-cover" alt="Jovens reunidos em momento de fé e amizade">
        </div>
    </section>

    <section id="caminhada" class="scroll-mt-28 py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="grid gap-12 lg:grid-cols-[1.1fr_.9fr]">
                <div><h2 class="font-serif text-4xl font-semibold">Um lugar para pertencer</h2><p class="mt-6 text-lg leading-8 text-slate-600">A página da juventude continuará reunindo encontros, retiros, equipes, formações e registros da caminhada dos jovens. Agora ela vive dentro de um portal que representa toda a paróquia.</p></div>
                <div class="border-l-4 border-[#14B8A6] pl-7"><p class="font-serif text-2xl font-semibold leading-snug">“Onde dois ou três estiverem reunidos em meu nome, eu estou ali, no meio deles.”</p><p class="mt-4 text-sm text-slate-500">Mateus 18,20</p></div>
            </div>
            <div class="mt-14 grid divide-y divide-slate-200 border-y border-slate-200 md:grid-cols-3 md:divide-x md:divide-y-0">
                @foreach([
                    ['title' => 'Encontros', 'text' => 'Momentos de oração, formação e convivência.', 'icon' => 'people'],
                    ['title' => 'Retiros', 'text' => 'Experiências de fé, silêncio e descoberta.', 'icon' => 'heart'],
                    ['title' => 'Serviço', 'text' => 'Jovens colocando seus dons a serviço da comunidade.', 'icon' => 'hand-thumbs-up'],
                ] as $item)
                    <div class="py-8 md:px-8 md:first:pl-0 md:last:pr-0"><x-dynamic-component :component="'bi-' . $item['icon']" class="size-7 text-[#008f83]" /><h3 class="mt-5 font-serif text-2xl font-semibold">{{ $item['title'] }}</h3><p class="mt-3 leading-7 text-slate-600">{{ $item['text'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-t border-slate-100 bg-[#fbfbfb] py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <h2 class="font-serif text-4xl font-semibold">Próximos retiros</h2>
            <p class="mt-4 max-w-2xl text-lg leading-8 text-slate-600">Experiências preparadas para fortalecer a fé, a amizade e o serviço.</p>
            <div class="mt-10 divide-y divide-slate-200 border-y border-slate-200">
                @forelse($retreats as $retreat)
                    <a href="{{ route('portal.retiros.show', $retreat) }}" class="group grid gap-5 py-7 sm:grid-cols-[8rem_minmax(0,1fr)_auto] sm:items-center">
                        <div><span class="block font-serif text-3xl font-semibold text-[#008f83]">{{ $retreat->starts_at->format('d') }}</span><span class="text-sm font-semibold uppercase text-[#008f83]">{{ $retreat->starts_at->translatedFormat('M Y') }}</span></div>
                        <div><h3 class="font-serif text-2xl font-semibold group-hover:text-[#750D37]">{{ $retreat->title }}</h3><p class="mt-2 leading-7 text-slate-600">{{ $retreat->summary }}</p><p class="mt-2 text-sm text-slate-500">{{ $retreat->location ?: 'Local a confirmar' }}@if($retreat->capacity) · {{ max(0, $retreat->capacity - $retreat->occupied_count) }} vagas disponíveis @endif</p></div>
                        <x-bi-arrow-right-circle class="size-6 text-[#008f83] transition group-hover:translate-x-1" />
                    </a>
                @empty
                    <div class="py-12 text-center text-slate-500">Os próximos retiros serão anunciados aqui.</div>
                @endforelse
            </div>
        </div>
    </section>
</x-layouts.public>
