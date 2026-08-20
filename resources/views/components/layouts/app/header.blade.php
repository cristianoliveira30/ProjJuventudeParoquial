@php
    $notifications = $notifications ?? [];
    $unreadCount = collect($notifications)->whereNull('read_at')->count();
@endphp

<header id="app-header" class="fixed left-0 right-0 top-0 z-[7999] h-18 border-b border-slate-200/80 bg-white/95 backdrop-blur transition-[left] duration-300 lg:left-[17rem] dark:border-slate-800 dark:bg-slate-950/95">
    <div class="flex h-full items-center justify-between gap-4 px-4 sm:px-6">
        <div class="flex min-w-0 items-center gap-3">
            <button id="header-sidebar-toggle" type="button" aria-expanded="true"
                class="inline-flex size-10 shrink-0 items-center justify-center rounded-xl border border-slate-200 text-[#210124] transition hover:border-[#B3DEC1] hover:bg-[#E5FCF5] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/40 dark:border-slate-700 dark:text-white dark:hover:bg-slate-800">
                <span class="sr-only">Alternar menu</span>
                <x-bi-list class="size-5" />
            </button>

            <a href="{{ safe_route('home') }}" class="min-w-0 lg:hidden">
                <p class="truncate text-sm font-bold text-[#750D37] dark:text-white">Gestão Paroquial</p>
                <p class="truncate text-[11px] text-slate-500 dark:text-slate-400">São Domingos de Gusmão</p>
            </a>
        </div>

        <div class="flex items-center gap-2 sm:gap-3">
            <button type="button" onclick="window.AccessibilityManager?.toggleHighContrast?.()"
                class="inline-flex size-10 items-center justify-center rounded-full text-slate-500 transition hover:bg-[#E5FCF5] hover:text-[#750D37] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/40 dark:text-slate-300 dark:hover:bg-slate-800"
                aria-label="Alternar alto contraste" data-tooltip="Acessibilidade visual">
                <x-bi-universal-access-circle class="size-5" />
            </button>

            @auth
                <button type="button" data-dropdown-toggle="dropdown-user" data-dropdown-placement="bottom-end"
                    class="flex items-center gap-3 rounded-full p-1 pr-2 transition hover:bg-[#E5FCF5] focus:outline-none focus:ring-2 focus:ring-[#14B8A6]/40 dark:hover:bg-slate-800">
                    <span class="relative">
                        <img class="size-9 rounded-full object-cover ring-2 ring-[#B3DEC1]"
                            src="{{ Auth::user()->profile_photo_url ? asset(Auth::user()->profile_photo_url) . '?v=' . time() : 'https://ui-avatars.com/api/?background=750D37&color=fff&name=' . urlencode(Auth::user()->name) }}"
                            alt="Foto de {{ Auth::user()->name }}">
                        <span id="notif-avatar-badge" data-count="{{ $unreadCount }}" @class(['absolute -right-1 -top-1 size-4 rounded-full bg-[#14B8A6] text-center text-[9px] leading-4 text-white', 'hidden' => $unreadCount === 0])>{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    </span>
                    <span class="hidden text-left sm:block">
                        <span class="block max-w-36 truncate text-sm font-semibold text-[#210124] dark:text-white">{{ Auth::user()->name }}</span>
                        <span class="block text-[11px] text-slate-500 dark:text-slate-400">Minha conta</span>
                    </span>
                    <x-bi-chevron-down class="hidden size-3 text-slate-400 sm:block" />
                </button>

                <div id="dropdown-user" class="z-[9999] hidden w-60 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-xl dark:border-slate-700 dark:bg-slate-900">
                    <div class="border-b border-slate-100 px-4 py-3 dark:border-slate-800">
                        <p class="truncate text-sm font-semibold text-[#210124] dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="truncate text-xs text-slate-500 dark:text-slate-400">{{ Auth::user()->email }}</p>
                    </div>
                    <div class="p-2 text-sm">
                        <button type="button" data-modal-target="notifications-modal" data-modal-toggle="notifications-modal"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-[#E5FCF5] hover:text-[#750D37] dark:text-slate-300 dark:hover:bg-slate-800">
                            <x-bi-bell class="size-4" /> Notificações
                            <span id="notif-menu-badge" data-count="{{ $unreadCount }}" @class(['ml-auto rounded-full bg-[#750D37] px-2 py-0.5 text-[10px] text-white', 'hidden' => $unreadCount === 0])>{{ $unreadCount }}</span>
                        </button>
                        <button type="button" data-modal-target="settings-modal" data-modal-toggle="settings-modal"
                            class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-[#E5FCF5] hover:text-[#750D37] dark:text-slate-300 dark:hover:bg-slate-800">
                            <x-bi-gear class="size-4" /> Configurações
                        </button>
                        <a href="{{ route('account.security') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-slate-600 hover:bg-[#E5FCF5] hover:text-[#750D37] dark:text-slate-300 dark:hover:bg-slate-800">
                            <x-bi-shield-lock class="size-4" /> Segurança da conta
                        </a>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">
                                <x-bi-box-arrow-right class="size-4" /> Sair
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</header>

<x-modals.notifications :notifications="$notifications" />
<x-modals.settings />
