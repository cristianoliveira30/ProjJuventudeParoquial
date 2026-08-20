<x-layouts.public :title="$group->name . ' — Paróquia São Domingos de Gusmão'">
    <article class="py-14 sm:py-20">
        <div class="mx-auto max-w-4xl px-5 sm:px-8">
            <a href="{{ route('portal.paroquia') }}#pastorais" class="inline-flex items-center gap-2 text-sm font-semibold text-[#008f83] transition hover:text-[#750D37]"><x-bi-arrow-left class="size-4" /> Pastorais e movimentos</a>
            <div class="mt-10 grid gap-9 lg:grid-cols-[minmax(0,1fr)_17rem]">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[.16em] text-[#008f83]">{{ $group->typeLabel() }}</p>
                    <h1 class="mt-3 font-serif text-4xl font-semibold leading-tight tracking-[-.025em] sm:text-5xl">{{ $group->name }}</h1>
                    @if($group->coverImageUrl())<img src="{{ $group->coverImageUrl() }}" class="mt-9 aspect-[16/9] w-full rounded-2xl object-cover" alt="Imagem de {{ $group->name }}">@endif
                    <div class="mt-9 text-lg leading-8 text-slate-700">{!! nl2br(e($group->description)) !!}</div>
                </div>
                <aside class="h-fit rounded-2xl border border-[#B3DEC1] bg-[#E5FCF5]/55 p-6">
                    @if($group->coordinator)<p class="text-sm font-semibold text-[#008f83]">Responsável</p><p class="mt-2 font-serif text-xl font-semibold">{{ $group->coordinator->name }}</p>@endif
                    @if($group->meeting_schedule)<div class="mt-5 border-t border-[#B3DEC1] pt-5"><p class="text-sm font-semibold text-[#008f83]">Encontros</p><p class="mt-2 leading-6 text-slate-700">{{ $group->meeting_schedule }}</p></div>@endif
                    @if($group->meeting_location)<div class="mt-5 border-t border-[#B3DEC1] pt-5"><p class="text-sm font-semibold text-[#008f83]">Local</p><p class="mt-2 leading-6 text-slate-700">{{ $group->meeting_location }}</p></div>@endif
                    @if($group->public_contact)<div class="mt-5 border-t border-[#B3DEC1] pt-5"><p class="text-sm font-semibold text-[#008f83]">Como participar</p><p class="mt-2 break-words leading-6 text-slate-700">{{ $group->public_contact }}</p></div>@endif
                </aside>
            </div>
        </div>
    </article>
</x-layouts.public>
