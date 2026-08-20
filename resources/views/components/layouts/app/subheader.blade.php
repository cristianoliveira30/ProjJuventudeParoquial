@props([
    'title' => 'Título',
    'subtitle' => 'Subtítulo ou descrição breve.',
])
<div data-dashboard-subheader class="w-full px-4 sm:px-6 my-5">
    <div class="flex items-center justify-between gap-2 flex-wrap">
        <div class="">
            <h2 class="text-2xl font-bold black:text-zinc-300 text-slate-800 dark:text-white">{{ $title }}</h2>
            <p class="text-slate-500 black:text-zinc-200 dark:text-slate-400">{{ $subtitle }}</p>
        </div>

        {{-- Atualizado --}}
        <span data-subheader-updated
            class=" inline-flex items-center gap-1 rounded-lg px-2.5 py-1 text-xs font-medium
                   bg-slate-200 text-slate-800
                   dark:bg-gray-900 dark:text-slate-100
                   black:bg-zinc-900 black:text-zinc-100">
            <svg class="w-3.5 h-3.5 opacity-80" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M12 6v6l4 2M22 12A10 10 0 1 1 2 12a10 10 0 0 1 20 0Z" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" />
            </svg>
            <span class="opacity-80" data-box-updated-at>Carregando...</span>
            <span data-subheader-updated-text class="font-semibold"></span>
        </span>
    </div>
</div>

{{-- usar quando for setar os dados --}}
@push('scripts')
    <script>
        function applyDashboardSubheader(payload) {
            const root = document.querySelector('[data-dashboard-subheader]');
            if (!root) return;

            const meta = payload?.meta;
            if (!meta) return;

            // Mostrar wrapper
            root.classList.remove('hidden');

            // Período
            const period = root.querySelector('[data-subheader-period]');
            const periodText = root.querySelector('[data-subheader-period-text]');
            if (meta.period_label) {
                period?.classList.remove('hidden');
                if (periodText) periodText.textContent = meta.period_label;
            }

            // Atualizado
            const upd = root.querySelector('[data-subheader-updated]');
            const updText = root.querySelector('[data-subheader-updated-text]');
            if (meta.updated_at) {
                upd?.classList.remove('hidden');
                if (updText) updText.textContent = meta.updated_at;
            }

            // Status
            const status = root.querySelector('[data-subheader-status]');
            const statusText = root.querySelector('[data-subheader-status-text]');
            const statusDot = root.querySelector('[data-subheader-status-dot]');

            const popText = root.querySelector('[data-popover-status-text]');
            const popPill = root.querySelector('[data-popover-status-pill]');
            const popItems = root.querySelector('[data-popover-status-items]');
            const popActions = root.querySelector('[data-popover-status-actions]');

            if (meta.operation_status || meta.operation_status_label) {
                status?.classList.remove('hidden');

                const statusKey = (meta.operation_status || '').toLowerCase(); // ok|attention|critical
                const label = meta.operation_status_label || 'Operação';

                if (statusText) statusText.textContent = label;

                // classes base (neutro)
                status.classList.remove(
                    'bg-amber-100', 'text-amber-900',
                    'dark:bg-amber-900/30', 'dark:text-amber-200',
                    'black:bg-amber-900/30', 'black:text-amber-200'
                );
                if (statusDot) statusDot.className = 'inline-block w-2 h-2 rounded-full bg-slate-400';

                // atenção (amarelo/laranja) / crítico (vermelho) / ok (verde)
                if (statusKey === 'attention') {
                    status.classList.add(
                        'bg-amber-100', 'text-amber-900',
                        'dark:bg-amber-900/30', 'dark:text-amber-200',
                        'black:bg-amber-900/30', 'black:text-amber-200'
                    );
                    if (statusDot) statusDot.className = 'inline-block w-2 h-2 rounded-full bg-amber-500';
                    if (popPill) popPill.textContent = 'Atenção';
                } else if (statusKey === 'critical') {
                    if (statusDot) statusDot.className = 'inline-block w-2 h-2 rounded-full bg-red-500';
                    if (popPill) {
                        popPill.textContent = 'Crítico';
                        popPill.className =
                            'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ' +
                            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200 black:bg-red-900/30 black:text-red-200';
                    }
                } else if (statusKey === 'ok') {
                    if (statusDot) statusDot.className = 'inline-block w-2 h-2 rounded-full bg-emerald-500';
                    if (popPill) {
                        popPill.textContent = 'Normal';
                        popPill.className =
                            'inline-flex items-center rounded-md px-2 py-0.5 text-xs font-semibold ' +
                            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-200 black:bg-emerald-900/30 black:text-emerald-200';
                    }
                }

                // Popover conteúdo (opcional)
                if (popText) popText.textContent = payload?.alert?.text || 'Sem detalhes adicionais no momento.';

                // Itens/bullets (opcional)
                const items = payload?.alert?.items || [];
                if (popItems) {
                    popItems.innerHTML = '';
                    if (items.length) {
                        popItems.classList.remove('hidden');
                        items.forEach((t) => {
                            const li = document.createElement('li');
                            li.textContent = t;
                            popItems.appendChild(li);
                        });
                    } else {
                        popItems.classList.add('hidden');
                    }
                }

                // Actions (opcional)
                const actions = payload?.alert?.actions || [];
                if (popActions) {
                    popActions.innerHTML = '';
                    if (actions.length) {
                        popActions.classList.remove('hidden');
                        actions.forEach((a) => {
                            const btn = document.createElement('a');
                            btn.href = a.url || '#';
                            btn.className =
                                'inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-semibold ' +
                                'bg-slate-900 text-white hover:bg-slate-800 ' +
                                'dark:bg-gray-900 dark:hover:bg-slate-600 ' +
                                'black:bg-zinc-800 black:hover:bg-zinc-700';
                            btn.textContent = a.label || 'Ação';
                            popActions.appendChild(btn);
                        });
                    } else {
                        popActions.classList.add('hidden');
                    }
                }
            }
        }
    </script>
@endpush
