<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Área administrativa — Paróquia São Domingos de Gusmão</title>
    <link rel="icon" href="{{ asset('assets/img/Ativo 2.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700|playfair-display:500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-dvh bg-white font-sans text-[#210124]">
    <main class="grid min-h-dvh lg:grid-cols-[minmax(25rem,.82fr)_minmax(34rem,1.18fr)]">
        <section class="relative hidden overflow-hidden bg-[#210124] px-12 py-10 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="absolute -right-28 top-24 size-80 rounded-full border border-white/5"></div>
            <div class="absolute -bottom-36 -left-28 size-96 rounded-full border border-[#14B8A6]/15"></div>

            <a href="{{ route('portal.home') }}" class="relative flex items-center gap-4">
                <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="h-18 w-auto" alt="Símbolo da Paróquia São Domingos de Gusmão">
                <div>
                    <p class="font-serif text-xl font-semibold leading-5">Paróquia São Domingos</p>
                    <p class="mt-1 text-xs text-white/65">de Gusmão</p>
                </div>
            </a>

            <div class="relative max-w-lg pb-12">
                <h1 class="font-serif text-5xl font-semibold leading-[1.04] tracking-[-0.045em]">Cuidar da comunidade também é servir.</h1>
                <p class="mt-6 max-w-md text-base leading-7 text-white/68">Acesso reservado para organizar conteúdos, atividades e serviços da vida paroquial.</p>
                <div class="mt-10 h-1 w-20 rounded-full bg-[#14B8A6]"></div>
            </div>

            <a href="{{ route('portal.home') }}" class="relative inline-flex items-center gap-2 text-xs text-white/50 hover:text-white"><x-bi-arrow-left class="size-3" /> Voltar ao portal da paróquia</a>
        </section>

        <section class="flex items-center justify-center bg-[#f7f8f8] px-5 py-10 sm:px-10">
            <div class="w-full max-w-md">
                <div class="mb-9 lg:hidden">
                    <div class="flex items-center gap-3">
                        <span class="flex size-14 items-center justify-center rounded-2xl bg-[#210124]">
                            <img src="{{ asset('assets/img/Ativo 1.svg') }}" class="h-10 w-auto" alt="">
                        </span>
                        <div>
                            <p class="font-serif font-semibold text-[#750D37]">Paróquia São Domingos</p>
                            <p class="text-xs text-slate-500">de Gusmão</p>
                        </div>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold tracking-[-0.035em] text-[#210124] sm:text-4xl">Que bom ter você aqui.</h2>
                    <p class="mt-3 text-sm leading-6 text-slate-500">Entre com sua conta para acessar o painel administrativo.</p>
                </div>

                @if (session('status'))
                    <p class="mb-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('status') }}</p>
                @endif

                <form action="{{ route('login.post') }}" method="post" class="space-y-5" id="login-form">
                    @csrf
                    <div>
                        <label for="username" class="mb-2 block text-sm font-semibold text-[#210124]">Usuário</label>
                        <input id="username" name="username" type="text" autocomplete="username" required autofocus
                            value="{{ old('username') }}" placeholder="Digite seu usuário"
                            class="min-h-12 w-full rounded-xl border bg-white px-4 text-sm text-[#210124] outline-none transition placeholder:text-slate-400 focus:border-[#14B8A6] focus:ring-4 focus:ring-[#14B8A6]/10 {{ $errors->has('username') || $errors->has('login') ? 'border-red-400' : 'border-slate-200' }}">
                        @error('username')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label for="password" class="text-sm font-semibold text-[#210124]">Senha</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-[#008f83]">Esqueceu?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Digite sua senha"
                                class="min-h-12 w-full rounded-xl border bg-white px-4 pr-12 text-sm text-[#210124] outline-none transition placeholder:text-slate-400 focus:border-[#14B8A6] focus:ring-4 focus:ring-[#14B8A6]/10 {{ $errors->has('password') || $errors->has('login') ? 'border-red-400' : 'border-slate-200' }}">
                            <button type="button" id="toggle-pass" class="absolute right-3 top-1/2 inline-flex size-8 -translate-y-1/2 items-center justify-center rounded-lg text-slate-400 hover:bg-[#E5FCF5] hover:text-[#750D37]" aria-label="Mostrar senha">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                        @error('login')<p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" id="btn-login" class="flex min-h-12 w-full items-center justify-center gap-2 rounded-xl bg-[#750D37] px-5 text-sm font-semibold text-white shadow-[0_10px_25px_rgba(117,13,55,.2)] transition hover:bg-[#5f0a2d] focus:outline-none focus:ring-4 focus:ring-[#750D37]/15 disabled:cursor-not-allowed disabled:opacity-70">
                        <span id="btn-text">Entrar</span>
                        <svg id="btn-spinner" class="hidden size-4 animate-spin" viewBox="0 0 24 24" fill="none"><circle class="opacity-25" cx="12" cy="12" r="9" stroke="currentColor" stroke-width="3"/><path class="opacity-80" fill="currentColor" d="M21 12a9 9 0 0 0-9-9v3a6 6 0 0 1 6 6h3Z"/></svg>
                    </button>
                </form>

                <p class="mt-8 text-center text-xs leading-5 text-slate-400">Acesso restrito à administração paroquial.<br>Em caso de dificuldade, procure a secretaria.</p>
                <a href="{{ route('portal.home') }}" class="mt-5 flex items-center justify-center gap-2 text-xs font-semibold text-[#008f83] lg:hidden"><x-bi-arrow-left class="size-3" /> Voltar ao portal</a>
            </div>
        </section>
    </main>

    <script>
        const password = document.getElementById('password');
        const toggle = document.getElementById('toggle-pass');
        const form = document.getElementById('login-form');
        toggle?.addEventListener('click', () => {
            const showing = password.type === 'text';
            password.type = showing ? 'password' : 'text';
            toggle.setAttribute('aria-label', showing ? 'Mostrar senha' : 'Ocultar senha');
        });
        form?.addEventListener('submit', () => {
            const button = document.getElementById('btn-login');
            button.disabled = true;
            document.getElementById('btn-text')?.classList.add('hidden');
            document.getElementById('btn-spinner')?.classList.remove('hidden');
        });
    </script>
</body>
</html>
