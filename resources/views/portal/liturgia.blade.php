@vite(['resources/js/liturgia.js'])
<x-layouts.public title="Liturgia Diária — Paróquia São Domingos de Gusmão">
    <x-portal.page-header :title="$liturgia['liturgia']" :description="$liturgia['data']" icon="book" />

    <div id="liturgia-skeleton" class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20" aria-busy="true">
        <div class="grid animate-pulse gap-10 lg:grid-cols-[.75fr_1.25fr]">
            <div class="h-80 rounded-2xl bg-slate-200"></div>
            <div class="space-y-8"><div class="h-10 w-2/3 rounded bg-slate-200"></div><div class="h-5 w-1/3 rounded bg-slate-200"></div><div class="h-48 rounded bg-slate-200"></div><div class="h-48 rounded bg-slate-200"></div></div>
        </div>
    </div>
    <section id="liturgia-content" class="hidden py-16 sm:py-20">
        <div class="mx-auto grid max-w-6xl gap-10 px-5 sm:px-8 lg:grid-cols-[.75fr_1.25fr]">
            <aside class="h-fit rounded-2xl border border-[#B3DEC1] bg-[#E5FCF5]/55 p-7">
                <p class="text-sm font-semibold text-[#008f83]">Liturgia de hoje</p>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="mt-3 font-serif text-3xl font-semibold">Oferendas</h2>
                    <p class="mt-4 leading-7 text-slate-600">{{ $liturgia['oferendas'] }}</p>
                </section>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="mt-3 font-serif text-3xl font-semibold">Comunhão</h2>
                    <p class="mt-4 leading-7 text-slate-600">{{ $liturgia['comunhao'] }}</p>
                </section>

                <p class="mt-6 rounded-xl bg-white p-4 text-sm leading-6 text-slate-500">{{ $liturgia['dia'] }}</p>
            </aside>

            <article class="max-w-3xl">
                <div class="border-b border-slate-200 pb-8">
                    <p class="text-sm font-semibold uppercase tracking-[.16em] text-[#750D37]">Leituras do dia</p>
                    <h2 class="mt-4 font-serif text-4xl font-semibold">Escutar, meditar e viver</h2>
                </div>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="font-serif text-4xl font-semibold">Primeira Leitura</h2>
                    @if (is_string($liturgia['primeiraLeitura']))
                        {{ $liturgia['primeiraLeitura'] }}
                    @else
                        <h3 class="mt-4 text-xl leading-8 text-slate-600 font-bold">{{ data_get($liturgia, 'primeiraLeitura.titulo', '') }}</h3>
                        <p class="font-serif text-lg font-semibold">{{ data_get($liturgia, 'primeiraLeitura.referencia', '') }}</p>
                        <p class="mt-4 text-lg leading-8 text-slate-600">{{ data_get($liturgia, 'primeiraLeitura.texto', '') }}</p>
                    @endif
                </section>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="font-serif text-4xl font-semibold">Salmo</h2>
                    @if (is_string($liturgia['salmo']))
                        {{ $liturgia['salmo'] }}
                    @else
                        <h3 class="mt-4 text-xg leading-8 text-slate-600 font-bold">{{ data_get($liturgia, 'salmo.referencia', '') }}</h3>
                        <p class="font-serif text-lg font-semibold">Refrão: {{ data_get($liturgia, 'salmo.refrao', '') }}</p>
                        <p class="mt-4 text-lg leading-8 text-slate-600">{{ data_get($liturgia, 'salmo.texto', '') }}</p>
                    @endif
                </section>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="font-serif text-4xl font-semibold">Segunda Leitura</h2>
                    @if (is_string($liturgia['segundaLeitura']))
                        {{ $liturgia['segundaLeitura'] }}
                    @else
                        <h3 class="mt-4 text-xg leading-8 text-slate-600 font-bold">{{ data_get($liturgia, 'segundaLeitura.referencia', '') }}</h3>
                        <p class="font-serif text-lg font-semibold">{{ data_get($liturgia, 'segundaLeitura.titulo', '') }}</p>
                        <p class="mt-4 text-lg leading-8 text-slate-600">{{ data_get($liturgia, 'segundaLeitura.texto', '') }}</p>
                    @endif
                </section>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="font-serif text-4xl font-semibold">Evangelho</h2>
                    @if (is_string($liturgia['evangelho']))
                        {{ $liturgia['evangelho'] }}
                    @else
                        <h3 class="mt-4 text-xg leading-8 text-slate-600 font-bold">{{ data_get($liturgia, 'evangelho.referencia', '') }}</h3>
                        <p class="font-serif text-lg font-semibold">{{ data_get($liturgia, 'evangelho.titulo', '') }}</p>
                        <p class="mt-4 text-lg leading-8 text-slate-600">{{ data_get($liturgia, 'evangelho.texto', '') }}</p>
                    @endif
                </section>
                <section class="border-b border-slate-200 py-8 last:border-b-0">
                    <h2 class="font-serif text-4xl font-semibold">Antifonas</h2>
                    @if (is_string($liturgia['antifonas']))
                        {{ $liturgia['antifonas'] }}
                    @else
                        <h3 class="mt-4 text-xg leading-8 text-slate-600 font-bold">{{ data_get($liturgia, 'antifonas.entrada', '') }}</h3>
                        <p class="mt-4 text-lg leading-8 text-slate-600">{{ data_get($liturgia, 'antifonas.comunhao', '') }}</p>
                    @endif
                </section>
            </article>
        </div>
    </section>
</x-layouts.public>
