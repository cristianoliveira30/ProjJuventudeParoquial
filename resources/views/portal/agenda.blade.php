<x-layouts.public title="Agenda — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Agenda paroquial" description="Celebrações, formações, encontros e atividades abertas à nossa comunidade." icon="calendar-event" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            @forelse ($events as $event)
                <article class="grid gap-5 border-b border-slate-200 py-8 first:pt-0 sm:grid-cols-[7rem_minmax(0,1fr)_auto] sm:items-center">
                    <div class="flex items-center gap-3 sm:block sm:text-center">
                        <span class="font-serif text-4xl font-semibold text-[#008f83] sm:block">{{ $event->starts_at->format('d') }}</span>
                        <span class="text-sm font-semibold uppercase tracking-wider text-[#008f83] sm:mt-1 sm:block">{{ $event->starts_at->translatedFormat('M') }}</span>
                    </div>
                    <div>
                        @if ($event->category)<p class="text-sm font-semibold text-[#008f83]">{{ $event->category }}</p>@endif
                        <h2 class="mt-1 font-serif text-2xl font-semibold">{{ $event->title }}</h2>
                        <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-slate-500">
                            <span class="inline-flex items-center gap-2"><x-bi-clock class="size-4 text-[#008f83]" /> {{ $event->starts_at->translatedFormat('l, H\\hi') }}</span>
                            @if ($event->location)<span class="inline-flex items-center gap-2"><x-bi-geo-alt class="size-4 text-[#008f83]" /> {{ $event->location }}</span>@endif
                        </div>
                    </div>
                    <a href="{{ route('portal.agenda.show', $event) }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl border border-[#B3DEC1] px-4 text-sm font-semibold text-[#008f83] transition hover:bg-[#E5FCF5]">Ver detalhes <x-bi-arrow-right class="size-4" /></a>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-[#B3DEC1] bg-[#E5FCF5]/45 px-6 py-14 text-center">
                    <x-bi-calendar-event class="mx-auto size-10 text-[#008f83]" />
                    <h2 class="mt-4 font-serif text-3xl font-semibold">Nenhum evento anunciado</h2>
                    <p class="mx-auto mt-3 max-w-lg leading-7 text-slate-600">Acompanhe o portal para conhecer as próximas celebrações e atividades da comunidade.</p>
                </div>
            @endforelse

            @if ($events->hasPages())<div class="mt-10">{{ $events->links() }}</div>@endif
        </div>
    </section>
</x-layouts.public>
