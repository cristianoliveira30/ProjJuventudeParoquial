<x-layouts.public :title="$event->title . ' — Paróquia São Domingos de Gusmão'">
    <article class="py-14 sm:py-20">
        <div class="mx-auto max-w-4xl px-5 sm:px-8">
            <a href="{{ route('portal.agenda') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#008f83] transition hover:text-[#750D37]"><x-bi-arrow-left class="size-4" /> Agenda paroquial</a>
            <div class="mt-10 grid gap-8 lg:grid-cols-[minmax(0,1fr)_17rem]">
                <div>
                    @if ($event->category)<p class="text-sm font-semibold uppercase tracking-[.16em] text-[#008f83]">{{ $event->category }}</p>@endif
                    <h1 class="mt-3 font-serif text-4xl font-semibold leading-tight tracking-[-.025em] text-[#210124] sm:text-5xl">{{ $event->title }}</h1>
                    @if ($event->coverImageUrl())<img src="{{ $event->coverImageUrl() }}" class="mt-9 aspect-[16/9] w-full rounded-2xl object-cover" alt="Imagem de {{ $event->title }}">@endif
                    <div class="mt-9 text-lg leading-8 text-slate-700">{!! nl2br(e($event->description)) !!}</div>
                </div>
                <aside class="h-fit rounded-2xl border border-[#B3DEC1] bg-[#E5FCF5]/55 p-6">
                    <p class="text-sm font-semibold text-[#008f83]">Quando</p>
                    <p class="mt-2 font-serif text-2xl font-semibold">{{ $event->starts_at->translatedFormat('d \\d\\e F \\d\\e Y') }}</p>
                    <p class="mt-2 text-sm text-slate-600">{{ $event->starts_at->translatedFormat('l, H\\hi') }}</p>
                    @if ($event->ends_at)<p class="mt-1 text-sm text-slate-500">até {{ $event->ends_at->translatedFormat('d/m/Y \\à\\s H\\hi') }}</p>@endif
                    @if ($event->location)<div class="mt-6 border-t border-[#B3DEC1] pt-5"><p class="text-sm font-semibold text-[#008f83]">Local</p><p class="mt-2 leading-6 text-slate-700">{{ $event->location }}</p></div>@endif
                </aside>
            </div>
        </div>
    </article>
</x-layouts.public>
