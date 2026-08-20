<x-layouts.public title="Notícias — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Notícias da paróquia" description="Celebrações, encontros, avisos e histórias que fazem parte da vida da nossa comunidade." icon="newspaper" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            @forelse ($posts as $post)
                <article class="grid gap-6 border-b border-slate-200 py-8 first:pt-0 sm:grid-cols-[11rem_1fr_auto] sm:items-center">
                    <p class="text-sm text-slate-500">{{ $post->published_at->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
                    <div>
                        @if ($post->category)<p class="text-sm font-semibold text-[#008f83]">{{ $post->category }}</p>@endif
                        <h2 class="mt-1 font-serif text-2xl font-semibold">{{ $post->title }}</h2>
                        <p class="mt-2 leading-7 text-slate-600">{{ $post->excerpt }}</p>
                    </div>
                    <a href="{{ route('portal.noticias.show', $post) }}" class="inline-flex size-10 items-center justify-center rounded-full bg-[#E5FCF5] text-[#008f83] transition hover:bg-[#B3DEC1]" aria-label="Ler {{ $post->title }}"><x-bi-arrow-right class="size-5" /></a>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-[#B3DEC1] bg-[#E5FCF5]/45 px-6 py-14 text-center">
                    <x-bi-newspaper class="mx-auto size-10 text-[#008f83]" />
                    <h2 class="mt-4 font-serif text-3xl font-semibold">Ainda não há notícias publicadas</h2>
                    <p class="mx-auto mt-3 max-w-lg leading-7 text-slate-600">Em breve, você encontrará aqui os avisos e acontecimentos da nossa comunidade.</p>
                </div>
            @endforelse

            @if ($posts->hasPages())
                <div class="mt-10">{{ $posts->links() }}</div>
            @endif
        </div>
    </section>
</x-layouts.public>
