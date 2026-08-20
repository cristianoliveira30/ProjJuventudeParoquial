@props(['notifications' => []])

@php
    // Mapa de classes para a cor (isolado no componente)
    $flagClass = [
        'sky' => 'bg-sky-100 text-sky-800 dark:bg-sky-900/30 dark:text-sky-200 black:bg-sky-900/30 black:text-sky-200',
        'emerald' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200 black:bg-emerald-900/30 black:text-emerald-200',
        'amber' => 'bg-amber-100 text-amber-900 dark:bg-amber-900/30 dark:text-amber-200 black:bg-amber-900/30 black:text-amber-200',
        'rose' => 'bg-rose-100 text-rose-900 dark:bg-rose-900/30 dark:text-rose-200 black:bg-rose-900/30 black:text-rose-200',
        'violet' => 'bg-violet-100 text-violet-900 dark:bg-violet-900/30 dark:text-violet-200 black:bg-violet-900/30 black:text-violet-200',
        'slate' => 'bg-slate-100 text-slate-800 dark:bg-gray-900 dark:text-slate-100 black:bg-zinc-800 black:text-zinc-200',
    ];
@endphp

<div id="notifications-modal" tabindex="-1" aria-hidden="true" data-scroll-lock
    class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-[9999] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] h-full backdrop-blur-sm bg-slate-900/50">

    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <div class="relative bg-white dark:bg-slate-900 black:bg-zinc-900 rounded-lg shadow-2xl border border-slate-200 dark:border-slate-700 black:border-zinc-800 overflow-hidden">
            {{-- Header --}}
            <div class="flex items-center justify-between p-4 border-b border-slate-200 dark:border-slate-700 black:border-zinc-800">
                <div class="flex items-center gap-2">
                    <x-bi-bell class="w-5 h-5 text-slate-700 dark:text-slate-200 black:text-zinc-200" />
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-100 black:text-zinc-100">
                        Central de Notificações
                    </h3>
                </div>
                <button type="button"
                    class="text-slate-400 hover:text-slate-800 dark:hover:text-white black:hover:text-white rounded-lg text-sm w-9 h-9 inline-flex items-center justify-center hover:bg-slate-100 dark:hover:bg-slate-800 black:hover:bg-zinc-800"
                    data-modal-hide="notifications-modal">
                    <x-bi-x-lg class="w-4 h-4" />
                </button>
            </div>

            {{-- Lista --}}
            <div class="p-4">
                <div class="max-h-[60vh] overflow-y-auto scrollbar-hide">
                    <ul class="space-y-2" id="notifications-list">
                        @forelse ($notifications as $n)
                            @php
                                $deliveryId = data_get($n, 'id');
                                $notifId = data_get($n, 'notification.id');
                                $title = data_get($n, 'notification.title', 'Sem título');
                                $message = data_get($n, 'notification.message', '');
                                $flagText = data_get($n, 'notification.flag');
                                $flagColor = data_get($n, 'notification.color', 'slate');
                                $time = data_get($n, 'delivered_at') ?? (data_get($n, 'read_at') ?? (data_get($n, 'updated_at') ?? ''));
                                $unread = empty($n['read_at']);
                                $notificationId = data_get($n, 'notification_id') ?? data_get($n, 'notification.id');
                            @endphp

                            <li>
                                <button type="button"
                                    class="notif-item cursor-pointer w-full text-left rounded-xl border border-slate-200 bg-white p-3 hover:bg-slate-50
                                    dark:bg-slate-900 dark:border-slate-700 dark:hover:bg-slate-800
                                    black:bg-zinc-900 black:border-zinc-800 black:hover:bg-zinc-800 transition-all group"
                                    data-notif-id="{{ $deliveryId }}" data-notification-id="{{ $notifId }}"
                                    data-read-url="{{ route('notifications.mark-read', ['id' => $notificationId]) }}"
                                    data-unread-default="{{ $unread ? '1' : '0' }}">
                                    <div class="flex items-start gap-3">
                                        <span data-notif-dot
                                            class="mt-1.5 w-2.5 h-2.5 rounded-full bg-sky-500 shadow-sm shadow-sky-500/50 {{ $unread ? '' : 'hidden' }}">
                                        </span>

                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <div class="font-bold text-slate-800 dark:text-slate-100 black:text-zinc-100 truncate text-sm">
                                                    {{ $title }}
                                                </div>

                                                @if (!empty($flagText))
                                                    <span class="shrink-0 inline-flex items-center rounded-full px-2 py-0.5 text-[10px] uppercase font-bold tracking-wide {{ $flagClass[$flagColor] ?? $flagClass['slate'] }}">
                                                        {{ $flagText }}
                                                    </span>
                                                @endif

                                                <div class="ml-auto text-xs text-slate-500 dark:text-slate-400 black:text-zinc-500">
                                                    {{ $time }}
                                                </div>
                                            </div>

                                            <div class="text-sm text-slate-600 dark:text-slate-300 black:text-zinc-400 leading-snug">
                                                {{ $message }}
                                            </div>
                                        </div>
                                    </div>
                                </button>
                            </li>
                        @empty
                            <li data-empty-notifications class="py-8 text-center text-slate-500 dark:text-slate-400 black:text-zinc-500">
                                <x-bi-inbox class="w-12 h-12 mx-auto mb-2 opacity-50" />
                                <p>Nenhuma notificação encontrada.</p>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
