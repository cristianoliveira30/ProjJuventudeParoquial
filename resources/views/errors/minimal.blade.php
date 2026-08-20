@php
    $code = trim($__env->yieldContent('code')) ?: '500';
    $title = trim($__env->yieldContent('title')) ?: 'Ops! Algo deu errado';
    $message = trim($__env->yieldContent('message')) ?: 'Algo inesperado aconteceu.';

    $actionLabel = trim($__env->yieldContent('action_label'));
    $actionUrl = trim($__env->yieldContent('action_url'));

    $hasAction = filled($actionUrl);
    $fallbackUrl = url()->previous() !== url()->current() ? url()->previous() : url('/');

    $isSessionExpired = $code === '419';
@endphp

<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <script>
        (function() {
            try {
                const stored = localStorage.getItem('color-theme');
                const html = document.documentElement;

                html.classList.remove('dark', 'black');

                if (stored === 'black') {
                    html.classList.add('black');
                } else if (stored === 'dark') {
                    html.classList.add('dark');
                } else if (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    html.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>

    <style>
        :root {
            --bg: #f8fafc;
            --card: rgba(255, 255, 255, .92);
            --text: #0f172a;
            --muted: #64748b;
            --border: rgba(203, 213, 225, .85);
            --icon-bg: #fee2e2;
            --icon-border: #fecaca;
            --icon: #ef4444;
            --button: #0284c7;
            --button-hover: #0ea5e9;
            --shadow: 0 24px 80px rgba(15, 23, 42, .16);
        }

        html.dark {
            --bg: #020617;
            --card: rgba(15, 23, 42, .82);
            --text: #f8fafc;
            --muted: #cbd5e1;
            --border: rgba(51, 65, 85, .9);
            --icon-bg: rgba(127, 29, 29, .35);
            --icon-border: rgba(185, 28, 28, .65);
            --icon: #f87171;
            --button: #0ea5e9;
            --button-hover: #38bdf8;
            --shadow: 0 24px 80px rgba(0, 0, 0, .45);
        }

        html.black {
            --bg: #09090b;
            --card: rgba(24, 24, 27, .86);
            --text: #fafafa;
            --muted: #a1a1aa;
            --border: rgba(63, 63, 70, .9);
            --icon-bg: rgba(127, 29, 29, .35);
            --icon-border: rgba(185, 28, 28, .65);
            --icon: #f87171;
            --button: #0891b2;
            --button-hover: #06b6d4;
            --shadow: 0 24px 80px rgba(0, 0, 0, .55);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
        }

        body {
            min-height: 100vh;
            min-height: 100dvh;
            display: grid;
            place-items: center;
            padding: 24px;
            background:
                radial-gradient(circle at top, rgba(14, 165, 233, .12), transparent 36rem),
                var(--bg);
            color: var(--text);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .error-card {
            width: 100%;
            max-width: 620px;
            padding: 42px;
            text-align: center;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 28px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }

        .error-icon {
            width: 82px;
            height: 82px;
            margin: 0 auto 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            background: var(--icon-bg);
            border: 1px solid var(--icon-border);
            color: var(--icon);
        }

        .error-code {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .error-code span {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        }

        h1 {
            margin: 0 0 16px;
            font-size: clamp(28px, 4vw, 42px);
            line-height: 1.1;
            font-weight: 750;
            letter-spacing: -0.04em;
        }

        p {
            margin: 0 auto 34px;
            max-width: 500px;
            color: var(--muted);
            font-size: 17px;
            line-height: 1.65;
        }

        .error-button {
            appearance: none;
            border: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            min-height: 50px;
            padding: 0 24px;
            border-radius: 18px;
            background: var(--button);
            color: #fff;
            font-size: 16px;
            font-weight: 700;
            text-decoration: none;
            transition: transform .15s ease, background .15s ease;
        }

        .error-button:hover {
            background: var(--button-hover);
        }

        .error-button:active {
            transform: scale(.97);
        }

        @media (max-width: 640px) {
            .error-card {
                padding: 32px 22px;
                border-radius: 24px;
            }
        }
    </style>
</head>

<body>
    <main class="error-card">
        <div class="error-icon" aria-hidden="true">
            @if ($isSessionExpired)
                <svg width="38" height="38" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M16.5 10.5V7.125A4.125 4.125 0 0 0 8.25 7.125V10.5m-.75 0h9a1.5 1.5 0 0 1 1.5 1.5v6A1.5 1.5 0 0 1 16.5 19.5h-9A1.5 1.5 0 0 1 6 18v-6a1.5 1.5 0 0 1 1.5-1.5Z" />
                </svg>
            @else
                <svg width="38" height="38" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.7">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v4m0 4h.01M10.29 4.86 3.82 16a1.8 1.8 0 0 0 1.54 2.7h13.28A1.8 1.8 0 0 0 20.18 16L13.71 4.86a1.8 1.8 0 0 0-3.42 0Z" />
                </svg>
            @endif
        </div>
<br>
        <div class="error-code">
            Código do erro <span>{{ $code }}</span>
        </div>

        <h1>{{ $title }}</h1>

        <p>{!! $message !!}</p>

        @if ($hasAction)
            <a href="{{ $actionUrl }}" class="error-button">
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 12H9.75m8.25 0-3-3m3 3-3 3" />
                </svg>

                {{ $actionLabel ?: 'Continuar' }}
            </a>
        @else
            <button type="button" class="error-button"
                onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = @js($fallbackUrl); }">
                <svg width="20" height="20" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>

                Voltar
            </button>
        @endif
    </main>
</body>

</html>
