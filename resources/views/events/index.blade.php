<x-layouts.app title="Eventos — Gestão Paroquial">
    <div class="mx-auto w-full max-w-[92rem] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <section class="rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-6 shadow-[0_10px_35px_rgba(20,184,166,.06)] sm:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div><p class="text-sm font-semibold text-[#008f83]">Vida paroquial</p><h1 class="mt-1 text-3xl font-bold tracking-[-.035em] text-[#210124] sm:text-4xl">Eventos e encontros</h1><p class="mt-3 max-w-2xl leading-7 text-slate-600">Organize a agenda e escolha quais atividades serão apresentadas à comunidade.</p></div>
                @can('events.create')<a href="{{ route('events.create') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(117,13,55,.2)] transition hover:bg-[#8d1042]"><x-bi-calendar-plus class="size-4" /> Novo evento</a>@endcan
            </div>
        </section>

        @if (session('success'))<div class="mt-6 rounded-xl border border-[#B3DEC1] bg-[#E5FCF5]/60 px-5 py-4 text-sm font-medium text-[#006f67]">{{ session('success') }}</div>@endif

        <section class="mt-6 overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
            <form method="GET" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:p-5 dark:border-slate-800">
                <label class="relative block min-w-0 flex-1"><span class="sr-only">Buscar evento</span><x-bi-search class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" /><input name="q" value="{{ $query }}" placeholder="Buscar por título, categoria ou local" class="min-h-11 w-full rounded-xl border-slate-200 pl-10 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6]/25 dark:border-slate-700 dark:bg-slate-800"></label>
                <select name="status" class="min-h-11 rounded-xl border-slate-200 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6]/25 dark:border-slate-700 dark:bg-slate-800"><option value="upcoming" @selected($status === 'upcoming')>Próximos</option><option value="past" @selected($status === 'past')>Encerrados</option><option value="published" @selected($status === 'published')>Publicados</option><option value="draft" @selected($status === 'draft')>Rascunhos</option><option value="all" @selected($status === 'all')>Todos</option></select>
                <button class="min-h-11 rounded-xl border border-[#14B8A6] px-4 text-sm font-semibold text-[#008f83] transition hover:bg-[#E5FCF5]">Filtrar</button>
            </form>

            @forelse ($events as $event)
                <article class="flex flex-col gap-5 border-b border-slate-100 p-5 last:border-b-0 sm:flex-row sm:items-center sm:p-6 dark:border-slate-800">
                    <div class="w-16 shrink-0 rounded-xl bg-[#E5FCF5] py-3 text-center"><span class="block font-serif text-2xl font-semibold text-[#750D37]">{{ $event->starts_at->format('d') }}</span><span class="text-[10px] font-bold uppercase text-[#008f83]">{{ $event->starts_at->translatedFormat('M') }}</span></div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 text-xs">@if ($event->published_at)<span class="rounded-full bg-[#E5FCF5] px-2.5 py-1 font-semibold text-[#008f83]">Publicado</span>@else<span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Rascunho</span>@endif @if ($event->category)<span class="text-slate-500">{{ $event->category }}</span>@endif</div>
                        <h2 class="mt-2 truncate text-lg font-bold text-[#210124] dark:text-white">{{ $event->title }}</h2>
                        <p class="mt-1 text-sm text-slate-500">{{ $event->starts_at->format('d/m/Y H:i') }}{{ $event->location ? ' · ' . $event->location : '' }}</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @can('events.update')<a href="{{ route('events.edit', $event) }}" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 px-3 text-sm font-semibold text-slate-600 transition hover:bg-[#E5FCF5] dark:border-slate-700 dark:text-slate-300"><x-bi-pencil class="size-4" /> Editar</a>@endcan
                        @can('events.publish')<form method="POST" action="{{ route('events.publication', $event) }}">@csrf @method('PATCH')<input type="hidden" name="published" value="{{ $event->published_at ? '0' : '1' }}"><button class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-[#B3DEC1] px-3 text-sm font-semibold text-[#008f83] transition hover:bg-[#E5FCF5]">@if($event->published_at)<x-bi-eye-slash class="size-4" /> Retirar @else<x-bi-send class="size-4" /> Publicar @endif</button></form>@endcan
                        @can('events.delete')<form method="POST" action="{{ route('events.destroy', $event) }}" onsubmit="return confirm('Excluir este evento? Esta ação não pode ser desfeita.');">@csrf @method('DELETE')<button class="inline-flex size-10 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50" aria-label="Excluir {{ $event->title }}"><x-bi-trash class="size-4" /></button></form>@endcan
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center"><x-bi-calendar-event class="mx-auto size-10 text-[#008f83]" /><h2 class="mt-4 text-lg font-bold text-[#210124] dark:text-white">Nenhum evento encontrado</h2><p class="mt-2 text-sm text-slate-500">Cadastre uma atividade para começar a organizar a agenda paroquial.</p></div>
            @endforelse

            @if ($events->hasPages())<div class="border-t border-slate-100 p-5 dark:border-slate-800">{{ $events->links() }}</div>@endif
        </section>
    </div>
</x-layouts.app>
