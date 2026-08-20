<x-layouts.public :title="$post->title . ' — Paróquia São Domingos de Gusmão'">
    <article class="py-14 sm:py-20">
        <div class="mx-auto max-w-3xl px-5 sm:px-8">
            <a href="{{ route('portal.noticias') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#008f83] transition hover:text-[#750D37]"><x-bi-arrow-left class="size-4" /> Todas as notícias</a>
            @if ($post->category)<p class="mt-10 text-sm font-semibold uppercase tracking-[.16em] text-[#008f83]">{{ $post->category }}</p>@endif
            <h1 class="mt-3 font-serif text-4xl font-semibold leading-tight tracking-[-.025em] text-[#210124] sm:text-5xl">{{ $post->title }}</h1>
            <p class="mt-5 text-sm text-slate-500">Publicado em {{ $post->published_at->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
            @if ($post->coverImageUrl())
                <img src="{{ $post->coverImageUrl() }}" class="mt-10 aspect-[16/9] w-full rounded-2xl object-cover" alt="Imagem de capa: {{ $post->title }}">
            @endif
            <div class="prose prose-lg mt-10 max-w-none leading-8 text-slate-700 prose-headings:font-serif prose-headings:text-[#210124]">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>
    </article>
</x-layouts.public>
