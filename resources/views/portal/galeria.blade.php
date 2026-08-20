<x-layouts.public title="Galeria — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Galeria da comunidade" description="Celebrações, encontros e momentos que contam a história da nossa paróquia." icon="images" />
    <main id="conteudo" class="mx-auto max-w-[1440px] px-5 py-14 sm:px-8 xl:px-12">
        <div class="grid gap-x-6 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($albums as $album)<a href="{{ route('portal.galeria.show', $album) }}" class="group block"><div class="aspect-[4/3] overflow-hidden rounded-[1.25rem] bg-[#E5FCF5]">@if($album->coverImageUrl())<img src="{{ $album->coverImageUrl() }}" alt="" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">@else<div class="flex h-full items-center justify-center text-[#008f83]"><x-bi-images class="size-12" /></div>@endif</div><div class="mt-4 flex items-start justify-between gap-4"><div><h2 class="font-serif text-2xl font-semibold group-hover:text-[#750D37]">{{ $album->title }}</h2><p class="mt-1 text-sm text-slate-500">{{ $album->event_date?->translatedFormat('d \d\e F \d\e Y') ?? 'Memórias da paróquia' }}</p></div><span class="shrink-0 text-xs font-semibold text-[#008f83]">{{ $album->photos_count }} fotos</span></div></a>@empty<div class="col-span-full py-20 text-center"><x-bi-images class="mx-auto size-12 text-[#008f83]" /><h2 class="mt-4 font-serif text-2xl font-semibold">Novas memórias em breve</h2><p class="mt-2 text-slate-500">Os álbuns publicados aparecerão aqui.</p></div>@endforelse
        </div>
        @if($albums->hasPages())<div class="mt-12">{{ $albums->links() }}</div>@endif
    </main>
</x-layouts.public>
