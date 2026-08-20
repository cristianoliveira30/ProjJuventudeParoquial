<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
    <meta name="app-user-id" content="{{ auth()->id() ?? '' }}">
    <meta name="app-route" content="{{ optional(request()->route())->getName() ?? '' }}">
    <title>{{ $title ?? config('app.name', 'Juventude Paroquial') }}</title>
    <link rel="icon" href="{{ asset('assets/img/Ativo 2.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet">
    <script>
        window.__page = @json($page ?? Route::currentRouteName());
        (() => {
            try {
                const html = document.documentElement;
                const theme = localStorage.getItem('theme') || 'light';
                const highContrast = localStorage.getItem('high-contrast') === 'true';
                if (highContrast) html.classList.add('dark', 'hc');
                else if (theme === 'dark' || theme === 'black') html.classList.add(theme);
            } catch (_) {}
        })();
    </script>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @stack('head')
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f7f8f8] text-[#210124] antialiased dark:bg-slate-950 dark:text-white">
    <div class="min-h-dvh">
        @include('components.layouts.app.header')

        @unless(request()->routeIs('first.access'))
            @include('components.layouts.app.sidebar')
        @endunless

        <div id="app-shell" @class([
            'min-h-dvh pt-18 transition-[padding] duration-300',
            'lg:pl-[17rem]' => !request()->routeIs('first.access'),
        ])>
            <main class="min-h-[calc(100dvh-4.5rem)]">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
            @include('components.layouts.app.footer')
        </div>
    </div>

    @stack('scripts')
    @if (session('access_denied_alert'))
        <script>
            document.addEventListener('DOMContentLoaded', () => window.Alerts?.showError?.({
                title: 'Acesso negado',
                msg: @json(session('access_denied_alert')),
            }), { once: true });
        </script>
    @endif
</body>
</html>
