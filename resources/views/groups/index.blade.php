<x-layouts.app title="Pastorais — Gestão Paroquial">
    <div class="mx-auto w-full max-w-[92rem] px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <section class="rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-6 shadow-[0_10px_35px_rgba(20,184,166,.06)] sm:p-8">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div><p class="text-sm font-semibold text-[#008f83]">Comunidade e serviço</p><h1 class="mt-1 text-3xl font-bold tracking-[-.035em] text-[#210124] sm:text-4xl">Pastorais e equipes</h1><p class="mt-3 max-w-2xl leading-7 text-slate-600">Organize responsáveis, participantes, encontros e a apresentação pública de cada grupo.</p></div>
                @can('groups.create')<a href="{{ route('groups.create') }}" class="inline-flex min-h-12 items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_8px_20px_rgba(117,13,55,.2)] transition hover:bg-[#8d1042]"><x-bi-plus-lg class="size-4" /> Nova pastoral</a>@endcan
            </div>
        </section>

        @if(session('success'))<div class="mt-6 rounded-xl border border-[#B3DEC1] bg-[#E5FCF5]/60 px-5 py-4 text-sm font-medium text-[#006f67]">{{ session('success') }}</div>@endif

        <section class="mt-6 overflow-hidden rounded-[1.25rem] border border-slate-200 bg-white shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900">
            <form method="GET" class="flex flex-col gap-3 border-b border-slate-100 p-4 sm:flex-row sm:items-center sm:p-5 dark:border-slate-800">
                <label class="relative block min-w-0 flex-1"><span class="sr-only">Buscar pastoral</span><x-bi-search class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-slate-400" /><input name="q" value="{{ $query }}" placeholder="Buscar por nome, resumo ou local" class="min-h-11 w-full rounded-xl border-slate-200 pl-10 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6]/25 dark:border-slate-700 dark:bg-slate-800"></label>
                <select name="type" class="min-h-11 rounded-xl border-slate-200 text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6]/25 dark:border-slate-700 dark:bg-slate-800"><option value="all">Todos os tipos</option>@foreach(App\Models\PastoralGroup::TYPES as $value => $label)<option value="{{ $value }}" @selected($type === $value)>{{ $label }}</option>@endforeach</select>
                <button class="min-h-11 rounded-xl border border-[#14B8A6] px-4 text-sm font-semibold text-[#008f83] transition hover:bg-[#E5FCF5]">Filtrar</button>
            </form>

            @forelse($groups as $group)
                <article class="flex flex-col gap-5 border-b border-slate-100 p-5 last:border-b-0 sm:flex-row sm:items-center sm:p-6 dark:border-slate-800">
                    <div class="flex size-16 shrink-0 items-center justify-center overflow-hidden rounded-xl bg-[#E5FCF5] text-[#008f83]">@if($group->coverImageUrl())<img src="{{ $group->coverImageUrl() }}" class="h-full w-full object-cover" alt="">@else<x-bi-person-hearts class="size-6" />@endif</div>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 text-xs"><span class="font-semibold text-[#008f83]">{{ $group->typeLabel() }}</span>@if($group->published_at)<span class="rounded-full bg-[#E5FCF5] px-2.5 py-1 font-semibold text-[#008f83]">Publicada</span>@else<span class="rounded-full bg-slate-100 px-2.5 py-1 font-semibold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Rascunho</span>@endif</div>
                        <h2 class="mt-2 truncate text-lg font-bold text-[#210124] dark:text-white">{{ $group->name }}</h2>
                        <p class="mt-1 line-clamp-1 text-sm text-slate-500">{{ $group->summary }}</p>
                        <p class="mt-2 text-xs text-slate-400">{{ $group->coordinator?->name ? 'Responsável: ' . $group->coordinator->name . ' · ' : '' }}{{ $group->members_count }} {{ $group->members_count === 1 ? 'participante' : 'participantes' }}</p>
                    </div>
                    <div class="flex shrink-0 flex-wrap gap-2">
                        @can('groups.update')<a href="{{ route('groups.edit', $group) }}" class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-slate-200 px-3 text-sm font-semibold text-slate-600 transition hover:bg-[#E5FCF5] dark:border-slate-700 dark:text-slate-300"><x-bi-pencil class="size-4" /> Editar</a>@endcan
                        @can('groups.publish')<form method="POST" action="{{ route('groups.publication', $group) }}">@csrf @method('PATCH')<input type="hidden" name="published" value="{{ $group->published_at ? '0' : '1' }}"><button class="inline-flex min-h-10 items-center gap-2 rounded-xl border border-[#B3DEC1] px-3 text-sm font-semibold text-[#008f83] transition hover:bg-[#E5FCF5]">@if($group->published_at)<x-bi-eye-slash class="size-4" /> Retirar @else<x-bi-send class="size-4" /> Publicar @endif</button></form>@endcan
                        @can('groups.delete')<form method="POST" action="{{ route('groups.destroy', $group) }}" onsubmit="return confirm('Excluir esta pastoral? Esta ação não pode ser desfeita.');">@csrf @method('DELETE')<button class="inline-flex size-10 items-center justify-center rounded-xl border border-red-200 text-red-600 transition hover:bg-red-50" aria-label="Excluir {{ $group->name }}"><x-bi-trash class="size-4" /></button></form>@endcan
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center"><x-bi-person-hearts class="mx-auto size-10 text-[#008f83]" /><h2 class="mt-4 text-lg font-bold text-[#210124] dark:text-white">Nenhuma pastoral encontrada</h2><p class="mt-2 text-sm text-slate-500">Cadastre o primeiro serviço ou movimento da comunidade.</p></div>
            @endforelse

            @if($groups->hasPages())<div class="border-t border-slate-100 p-5 dark:border-slate-800">{{ $groups->links() }}</div>@endif
        </section>
    </div>
</x-layouts.app>
