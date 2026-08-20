@php
    $items = [
        ['label' => 'Início', 'icon' => 'house-door', 'route' => 'home'],
        ['label' => 'Usuários', 'icon' => 'people', 'route' => 'user.search', 'permission' => 'users.view'],
        ['label' => 'Perfis de acesso', 'icon' => 'shield-check', 'route' => 'roles.index', 'permission' => 'roles.manage'],
        ['label' => 'Notificações', 'icon' => 'bell', 'route' => 'notifications.dashboard', 'permission' => 'notifications.send'],
        ['label' => 'Retiros', 'icon' => 'heart', 'route' => '#'],
        ['label' => 'Eventos', 'icon' => 'calendar-event', 'route' => '#'],
        ['label' => 'Equipes', 'icon' => 'person-hearts', 'route' => '#'],
        ['label' => 'Relatórios', 'icon' => 'bar-chart', 'route' => 'report.default'],
        ['label' => 'Galeria', 'icon' => 'images', 'route' => '#'],
    ];
@endphp

<div id="sidebar-backdrop" class="fixed inset-0 z-[7980] hidden bg-[#210124]/45 backdrop-blur-sm lg:hidden"></div>

<aside id="top-bar-sidebar" aria-label="Navegação principal"
    class="fixed bottom-0 left-0 top-0 z-[7990] flex w-[17rem] -translate-x-full flex-col overflow-hidden bg-[#210124] text-white shadow-2xl transition-[width,transform] duration-300 lg:translate-x-0">
    <a href="{{ safe_route('home') }}" class="flex h-44 shrink-0 flex-col items-center justify-center border-b border-white/10 px-6 text-center">
        <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="mb-3 h-18 w-auto" alt="Símbolo da Juventude Paroquial">
        <span class="sidebar-brand-text text-xl font-bold leading-5">Gestão<br>Paroquial</span>
        <span class="sidebar-label mt-2 text-xs leading-4 text-white/70">Paróquia São Domingos<br>de Gusmão</span>
    </a>

    <nav class="flex-1 space-y-1.5 overflow-y-auto px-3 py-6">
        @foreach($items as $item)
            @continue(isset($item['permission']) && !auth()->user()?->can($item['permission']))
            @php
                $prefix = Illuminate\Support\Str::before($item['route'], '.');
                $isActive = $item['route'] === 'home'
                    ? request()->routeIs('home')
                    : request()->routeIs($item['route']) || request()->routeIs($prefix . '.*');
            @endphp
            <a href="{{ safe_route($item['route']) }}" data-tooltip="{{ $item['label'] }}"
                @class([
                    'sidebar-link group flex items-center gap-3 rounded-xl px-3.5 py-3 text-sm font-medium transition focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/70',
                    'bg-[#750D37] text-white shadow-[0_10px_28px_rgba(117,13,55,.35)]' => $isActive,
                    'text-white/78 hover:bg-white/10 hover:text-white' => !$isActive,
                ])>
                <x-dynamic-component :component="'bi-' . $item['icon']" class="size-5 shrink-0" />
                <span class="sidebar-label">{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <button id="sidebar-collapse-button" type="button"
        class="hidden items-center gap-3 border-t border-white/10 px-6 py-5 text-xs text-white/70 transition hover:bg-white/5 hover:text-white lg:flex">
        <x-bi-chevron-left class="size-4 transition-transform" />
        <span class="sidebar-label">Recolher menu</span>
    </button>
</aside>

<style>
    @media (min-width: 1024px) {
        body.sidebar-collapsed #top-bar-sidebar { width: 5.25rem; }
        body.sidebar-collapsed #top-bar-sidebar .sidebar-label,
        body.sidebar-collapsed #top-bar-sidebar .sidebar-brand-text { display: none; }
        body.sidebar-collapsed #top-bar-sidebar > a { height: 5.5rem; padding: .75rem; }
        body.sidebar-collapsed #top-bar-sidebar > a img { height: 3.5rem; margin: 0; }
        body.sidebar-collapsed #top-bar-sidebar .sidebar-link { justify-content: center; padding-inline: .75rem; }
        body.sidebar-collapsed #app-shell { padding-left: 5.25rem; }
        body.sidebar-collapsed #app-header { left: 5.25rem; }
        body.sidebar-collapsed #sidebar-collapse-button { justify-content: center; padding-inline: .75rem; }
        body.sidebar-collapsed #sidebar-collapse-button svg { transform: rotate(180deg); }
        body.sidebar-collapsed .sidebar-link { position: relative; }
        body.sidebar-collapsed .sidebar-link:hover::after {
            content: attr(data-tooltip); position: absolute; left: calc(100% + .75rem); top: 50%;
            transform: translateY(-50%); white-space: nowrap; border-radius: .625rem;
            background: #210124; padding: .45rem .65rem; font-size: .75rem; color: white;
            box-shadow: 0 10px 24px rgba(33,1,36,.24);
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('top-bar-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const headerToggle = document.getElementById('header-sidebar-toggle');
        const collapseToggle = document.getElementById('sidebar-collapse-button');
        const desktop = window.matchMedia('(min-width: 1024px)');
        const key = 'sidebarCollapsed';

        const closeMobile = () => {
            sidebar?.classList.add('-translate-x-full');
            backdrop?.classList.add('hidden');
            document.body.style.overflow = '';
            headerToggle?.setAttribute('aria-expanded', 'false');
        };

        const sync = () => {
            if (desktop.matches) {
                sidebar?.classList.remove('-translate-x-full');
                backdrop?.classList.add('hidden');
                document.body.classList.toggle('sidebar-collapsed', localStorage.getItem(key) === 'true');
                headerToggle?.setAttribute('aria-expanded', 'true');
                document.body.style.overflow = '';
            } else {
                document.body.classList.remove('sidebar-collapsed');
                closeMobile();
            }
        };

        const toggle = () => {
            if (desktop.matches) {
                const collapsed = document.body.classList.toggle('sidebar-collapsed');
                localStorage.setItem(key, String(collapsed));
            } else {
                const opening = sidebar?.classList.contains('-translate-x-full');
                sidebar?.classList.toggle('-translate-x-full');
                backdrop?.classList.toggle('hidden', !opening);
                document.body.style.overflow = opening ? 'hidden' : '';
                headerToggle?.setAttribute('aria-expanded', String(opening));
            }
        };

        headerToggle?.addEventListener('click', toggle);
        collapseToggle?.addEventListener('click', toggle);
        backdrop?.addEventListener('click', closeMobile);
        desktop.addEventListener('change', sync);
        sync();
    });
</script>
