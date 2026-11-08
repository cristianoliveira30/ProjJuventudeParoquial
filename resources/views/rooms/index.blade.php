<x-layouts.app title="Salas — Gestão Paroquial">
    @php
        $field = 'mt-2 block min-h-11 w-full rounded-xl border-slate-300 bg-white text-sm focus:border-[#14B8A6] focus:ring-[#14B8A6] dark:border-slate-700 dark:bg-slate-950 dark:text-white';
        $card = 'rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900 sm:p-6';
        $btn = 'inline-flex min-h-10 items-center justify-center rounded-xl px-4 text-xs font-semibold';
    @endphp
    <div class="mx-auto w-full max-w-[92rem] space-y-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <section class="rounded-[1.25rem] border border-[#B3DEC1]/70 bg-[#E5FCF5] p-6 sm:p-8">
            <p class="text-sm font-semibold text-[#008f83]">Espaços da paróquia</p>
            <h1 class="mt-1 text-3xl font-bold tracking-[-.035em] text-[#210124] sm:text-4xl">Agendamento de salas</h1>
            <p class="mt-3 max-w-2xl leading-7 text-slate-600">Solicite uma sala para reunião, ensaio ou encontro. A secretaria aprova, e horários já aprovados não podem ser reservados de novo.</p>
        </section>

        @if(session('success'))<div class="rounded-xl border border-[#B3DEC1] bg-[#E5FCF5]/60 px-5 py-4 text-sm font-medium text-[#006f67]">{{ session('success') }}</div>@endif
        @if($errors->any())<div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 dark:border-red-900/60 dark:bg-red-950/30 dark:text-red-300"><ul class="list-disc space-y-1 pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif

        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_24rem]">
            <div class="space-y-6">
                @if($canManage && $pending->isNotEmpty())
                    <section class="{{ $card }}">
                        <h2 class="font-bold text-[#210124] dark:text-white">Aguardando resposta ({{ $pending->count() }})</h2>
                        <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                            @foreach($pending as $booking)
                                <article class="flex flex-col gap-3 py-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="font-semibold text-[#210124] dark:text-white">{{ $booking->title }} <span class="font-normal text-slate-500">· {{ $booking->room->name }}</span></p>
                                        <p class="text-sm text-slate-500">{{ $booking->starts_at->format('d/m/Y H:i') }} às {{ $booking->ends_at->format('H:i') }} · {{ $booking->requester?->name ?? 'Usuário removido' }}</p>
                                        @if($booking->purpose)<p class="mt-1 text-sm text-slate-600 dark:text-slate-300">{{ $booking->purpose }}</p>@endif
                                    </div>
                                    <form method="POST" action="{{ route('rooms.decide', $booking) }}" class="flex shrink-0 gap-2">@csrf @method('PATCH')
                                        <button name="decision" value="approved" class="{{ $btn }} bg-[#14B8A6] text-white hover:bg-[#0f9f91]">Aprovar</button>
                                        <button name="decision" value="rejected" class="{{ $btn }} border border-slate-300 text-slate-600 dark:border-slate-700 dark:text-slate-300">Recusar</button>
                                    </form>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="{{ $card }}">
                    <h2 class="font-bold text-[#210124] dark:text-white">Agenda aprovada</h2>
                    <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($agenda as $booking)
                            <article class="flex items-start justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-[#210124] dark:text-white">{{ $booking->title }} <span class="font-normal text-slate-500">· {{ $booking->room->name }}</span></p>
                                    <p class="text-sm text-slate-500">{{ $booking->starts_at->format('d/m/Y H:i') }} às {{ $booking->ends_at->format('H:i') }} · {{ $booking->requester?->name }}</p>
                                </div>
                                @if($canManage)<form method="POST" action="{{ route('rooms.cancel', $booking) }}" onsubmit="return confirm('Cancelar esta reserva?')">@csrf @method('PATCH')<button class="{{ $btn }} border border-red-200 text-red-600">Cancelar</button></form>@endif
                            </article>
                        @empty
                            <p class="py-6 text-center text-sm text-slate-500">Nenhuma reserva aprovada por enquanto.</p>
                        @endforelse
                    </div>
                </section>

                <section class="{{ $card }}">
                    <h2 class="font-bold text-[#210124] dark:text-white">Minhas solicitações</h2>
                    <div class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($mine as $booking)
                            <article class="flex items-start justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <p class="font-semibold text-[#210124] dark:text-white">{{ $booking->title }} <span class="font-normal text-slate-500">· {{ $booking->room->name }}</span></p>
                                    <p class="text-sm text-slate-500">{{ $booking->starts_at->format('d/m/Y H:i') }} às {{ $booking->ends_at->format('H:i') }} · <strong>{{ \App\Models\RoomBooking::STATUSES[$booking->status] }}</strong></p>
                                </div>
                                @if(in_array($booking->status, ['pending', 'approved'], true))<form method="POST" action="{{ route('rooms.cancel', $booking) }}" onsubmit="return confirm('Cancelar esta solicitação?')">@csrf @method('PATCH')<button class="{{ $btn }} border border-slate-300 text-slate-600 dark:border-slate-700 dark:text-slate-300">Cancelar</button></form>@endif
                            </article>
                        @empty
                            <p class="py-6 text-center text-sm text-slate-500">Você não tem solicitações futuras.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <aside class="space-y-6">
                @if($canRequest)
                    <section class="{{ $card }}">
                        <h2 class="font-bold text-[#210124] dark:text-white">Solicitar sala</h2>
                        <form method="POST" action="{{ route('rooms.store') }}" onsubmit="this.querySelector('button[type=submit]').disabled = true" class="mt-4 space-y-4">@csrf
                            <label class="block text-sm font-semibold">Sala<select name="room_id" required class="{{ $field }}"><option value="">Selecione</option>@foreach($rooms->where('is_active', true) as $room)<option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>{{ $room->name }}@if($room->capacity) ({{ $room->capacity }} lugares)@endif</option>@endforeach</select></label>
                            <label class="block text-sm font-semibold">Título<input name="title" value="{{ old('title') }}" required maxlength="180" class="{{ $field }}"></label>
                            <label class="block text-sm font-semibold">Finalidade <span class="font-normal text-slate-400">(opcional)</span><textarea name="purpose" rows="3" maxlength="1000" class="{{ $field }}">{{ old('purpose') }}</textarea></label>
                            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1 xl:grid-cols-2">
                                <label class="block text-sm font-semibold">Início<input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" required class="{{ $field }}"></label>
                                <label class="block text-sm font-semibold">Término<input type="datetime-local" name="ends_at" value="{{ old('ends_at') }}" required class="{{ $field }}"></label>
                            </div>
                            <button type="submit" class="{{ $btn }} min-h-11 w-full bg-[#750D37] text-white hover:bg-[#5f0b2e]">Enviar solicitação</button>
                        </form>
                    </section>
                @endif

                @if($canManage)
                    <section class="{{ $card }}">
                        <h2 class="font-bold text-[#210124] dark:text-white">Salas cadastradas</h2>
                        <ul class="mt-4 divide-y divide-slate-100 dark:divide-slate-800">
                            @forelse($rooms as $room)
                                <li class="flex items-center justify-between gap-3 py-2 text-sm"><span>{{ $room->name }}@if($room->capacity) <span class="text-slate-500">· {{ $room->capacity }}</span>@endif @unless($room->is_active)<span class="text-xs text-slate-400">(inativa)</span>@endunless</span>
                                    <form method="POST" action="{{ route('rooms.room.toggle', $room) }}">@csrf @method('PATCH')<button class="{{ $btn }} border border-slate-300 text-slate-600 dark:border-slate-700 dark:text-slate-300">{{ $room->is_active ? 'Desativar' : 'Ativar' }}</button></form></li>
                            @empty
                                <li class="py-3 text-sm text-slate-500">Cadastre a primeira sala abaixo.</li>
                            @endforelse
                        </ul>
                        <form method="POST" action="{{ route('rooms.room.store') }}" class="mt-4 grid grid-cols-[1fr_6rem] gap-2">@csrf
                            <input name="name" required maxlength="120" placeholder="Nome da sala" aria-label="Nome da sala" class="{{ $field }} mt-0">
                            <input name="capacity" type="number" min="1" placeholder="Lugares" aria-label="Capacidade" class="{{ $field }} mt-0">
                            <button class="{{ $btn }} col-span-2 bg-[#14B8A6] text-white hover:bg-[#0f9f91]">Adicionar sala</button>
                        </form>
                    </section>
                @endif
            </aside>
        </div>
    </div>
</x-layouts.app>
