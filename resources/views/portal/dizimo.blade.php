<x-layouts.public title="Dízimo — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Dízimo" description="Um gesto de gratidão e corresponsabilidade que sustenta a evangelização, a caridade e a vida da comunidade." icon="heart" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="grid gap-12 lg:grid-cols-[1.1fr_.9fr]">
                <article><h2 class="font-serif text-4xl font-semibold">Partilha que se transforma em missão</h2><p class="mt-6 text-lg leading-8 text-slate-600">O dízimo é uma resposta livre e consciente de quem reconhece os dons recebidos e deseja cuidar da comunidade. Esta página está preparada para receber orientações, prestação de contas e as formas oficiais de contribuição.</p><p class="mt-5 text-lg leading-8 text-slate-600">Os dados bancários e canais de contribuição serão incluídos pela administração em uma etapa posterior.</p></article>
                <aside class="rounded-2xl bg-[#210124] p-8 text-white"><x-bi-shield-check class="size-8 text-[#B3DEC1]" /><h2 class="mt-5 font-serif text-3xl font-semibold">Contribuição segura</h2><p class="mt-4 leading-7 text-white/70">Nenhum dado de pagamento foi publicado nesta etapa visual. As informações oficiais serão confirmadas pela secretaria paroquial.</p><a href="{{ route('portal.paroquia') }}#secretaria" class="mt-7 inline-flex items-center gap-2 text-sm font-semibold text-[#B3DEC1]">Falar com a secretaria <x-bi-arrow-right class="size-4" /></a></aside>
            </div>

            <div class="mt-16 grid divide-y divide-slate-200 border-y border-slate-200 md:grid-cols-3 md:divide-x md:divide-y-0">
                @foreach([
                    ['title' => 'Evangelização', 'text' => 'Formações, celebrações e ações pastorais.'],
                    ['title' => 'Caridade', 'text' => 'Acolhimento e cuidado com quem mais precisa.'],
                    ['title' => 'Manutenção', 'text' => 'Cuidado com os espaços e serviços da paróquia.'],
                ] as $item)
                    <div class="py-8 md:px-8 md:first:pl-0 md:last:pr-0"><h3 class="font-serif text-2xl font-semibold">{{ $item['title'] }}</h3><p class="mt-3 leading-7 text-slate-600">{{ $item['text'] }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
