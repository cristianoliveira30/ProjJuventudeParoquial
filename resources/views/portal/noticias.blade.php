<x-layouts.public title="Notícias — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Notícias da paróquia" description="Celebrações, encontros, avisos e histórias que fazem parte da vida da nossa comunidade." icon="newspaper" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <article class="grid gap-8 border-b border-slate-200 pb-12 lg:grid-cols-[1.05fr_.95fr] lg:items-center">
                <img src="{{ asset('assets/img/portal/festividade.jpg') }}" class="aspect-[4/3] w-full rounded-2xl object-cover" alt="Comunidade reunida em frente à igreja">
                <div><p class="text-sm font-medium text-[#008f83]">16 de agosto de 2026</p><h2 class="mt-3 font-serif text-4xl font-semibold leading-tight">Festa de São Domingos reúne nossa comunidade</h2><p class="mt-5 text-lg leading-8 text-slate-600">Estrutura visual para a futura publicação de notícias pela administração do portal.</p><button type="button" class="mt-7 inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]">Continuar lendo <x-bi-arrow-right class="size-4" /></button></div>
            </article>

            <div class="divide-y divide-slate-200">
                @foreach([
                    ['title' => 'Inscrições abertas para a catequese', 'date' => '14 de agosto de 2026', 'text' => 'Informações sobre turmas, idades e período de inscrição serão publicadas aqui.'],
                    ['title' => 'Mutirão solidário neste sábado', 'date' => '12 de agosto de 2026', 'text' => 'A comunidade será convidada a participar das ações e iniciativas sociais da paróquia.'],
                    ['title' => 'Encontro de preparação para o batismo', 'date' => '10 de agosto de 2026', 'text' => 'Datas, orientações e documentos necessários poderão ser consultados no portal.'],
                ] as $news)
                    <article class="grid gap-4 py-8 sm:grid-cols-[11rem_1fr_auto] sm:items-center"><p class="text-sm text-slate-500">{{ $news['date'] }}</p><div><h2 class="font-serif text-2xl font-semibold">{{ $news['title'] }}</h2><p class="mt-2 leading-7 text-slate-600">{{ $news['text'] }}</p></div><x-bi-arrow-right class="hidden size-5 text-[#008f83] sm:block" /></article>
                @endforeach
            </div>

            <div class="mt-10 rounded-xl border border-[#B3DEC1] bg-[#E5FCF5]/55 p-5 text-sm leading-6 text-slate-600"><strong class="text-[#210124]">Próxima etapa:</strong> esta listagem será alimentada por publicações criadas no painel administrativo, com categorias, imagens e páginas individuais.</div>
        </div>
    </section>
</x-layouts.public>
