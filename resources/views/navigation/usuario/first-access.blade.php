<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Definir Senha</title>

    <link rel="icon" href="{{ asset('assets/img/Ativo 2.svg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />


    @vite(['resources/css/app.css'])

    <script>
        const savedTheme = localStorage.getItem('color-theme') || 'light';
        const html = document.documentElement;

        html.classList.remove('dark', 'black');

        if (savedTheme === 'dark') {
            html.classList.add('dark');
        } else if (savedTheme === 'black') {
            html.classList.add('black');
        }
    </script>
</head>

<body
    class="bg-slate-50 dark:bg-slate-900 black:bg-zinc-950 min-h-screen py-8 flex flex-col justify-center items-center">
    <div
        class="w-full max-w-lg bg-white dark:bg-gray-900 black:bg-zinc-900 border border-slate-200 dark:border-slate-700 black:border-zinc-800 rounded-3xl shadow-xl overflow-hidden">
        <div
            class="px-6 py-6 border-b border-slate-100 dark:border-slate-700 black:border-zinc-800 bg-slate-50/50 dark:bg-gray-900/50 black:bg-zinc-900/50">
            <div class="flex items-center gap-4">
                <div
                    class="flex items-center justify-center w-12 h-12 rounded-full shrink-0 bg-sky-100 dark:bg-sky-900/40 black:bg-sky-900/20 text-sky-600 dark:text-sky-400">
                    <x-bi-shield-lock class="w-6 h-6" />
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 dark:text-slate-100 black:text-zinc-100">
                        Atualização de Segurança
                    </h2>
                    <p class="mt-0.5 text-sm text-slate-500 dark:text-slate-400 black:text-zinc-400 leading-snug">
                        Esta etapa é obrigatória no seu primeiro login.
                    </p>
                </div>
            </div>
        </div>

        <form id="first-access-form" action="{{ route('user.profile.password.update') }}" method="POST"
            class="px-6 py-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300 black:text-zinc-300 mb-2">
                        Nome do Usuário
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <x-bi-person class="w-5 h-5" />
                        </div>
                        <input type="text" value="{{ auth()->user()->name ?? '' }}" disabled
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-medium
                                   dark:border-slate-700 dark:bg-slate-900/50 dark:text-slate-400
                                   black:border-zinc-800 black:bg-zinc-950/50 black:text-zinc-500 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300 black:text-zinc-300 mb-2">
                        Login de Acesso
                    </label>
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <x-bi-person-vcard class="w-5 h-5" />
                        </div>
                        <input type="text" value="{{ auth()->user()->username ?? '' }}" disabled
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-slate-100 text-slate-500 text-sm font-medium
                                   dark:border-slate-700 dark:bg-slate-900/50 dark:text-slate-400
                                   black:border-zinc-800 black:bg-zinc-950/50 black:text-zinc-500 cursor-not-allowed">
                    </div>
                </div>

                <div>
                    <label for="password"
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300 black:text-zinc-300 mb-2">
                        Nova senha
                    </label>

                    <div class="relative">
                        <input id="password" name="password" type="password" placeholder="Nova senha"
                            class="w-full px-4 pr-12 py-3 rounded-xl border bg-white text-sm text-slate-800 shadow-sm transition-all duration-200
                                   focus:ring-2 focus:outline-none
                                   dark:bg-slate-900 dark:text-slate-100 black:bg-zinc-950 black:text-zinc-100
                                   @error('password') border-red-500 focus:border-red-500 focus:ring-red-500/20 dark:border-red-500 black:border-red-500 @else border-slate-300 focus:border-sky-500 focus:ring-sky-500/20 dark:border-slate-600 black:border-zinc-700 @enderror">

                        <button type="button" id="toggle-pass"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors"
                            aria-label="Mostrar senha">
                            <span id="icon-pass-hide">
                                <x-bi-eye-slash class="w-5 h-5" />
                            </span>
                            <span id="icon-pass-show" class="hidden">
                                <x-bi-eye class="w-5 h-5" />
                            </span>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-2 text-xs text-red-500 dark:text-red-400 flex items-center gap-1.5">
                            <x-bi-exclamation-circle class="w-4 h-4 shrink-0" />
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-semibold text-slate-700 dark:text-slate-300 black:text-zinc-300 mb-2">
                        Confirmar senha
                    </label>

                    <div class="relative">
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="Confirme a senha"
                            class="w-full px-4 pr-12 py-3 rounded-xl border border-slate-300 bg-white text-sm text-slate-800 shadow-sm transition-all duration-200
                                   focus:border-sky-500 focus:ring-2 focus:ring-sky-500/20 focus:outline-none
                                   dark:border-slate-600 dark:bg-slate-900 dark:text-slate-100 dark:focus:border-sky-500
                                   black:border-zinc-700 black:bg-zinc-950 black:text-zinc-100 black:focus:border-sky-500">

                        <button type="button" id="toggle-pass-confirm"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-sky-600 focus:outline-none transition-colors"
                            aria-label="Mostrar confirmação de senha">
                            <span id="icon-pass-confirm-hide">
                                <x-bi-eye-slash class="w-5 h-5" />
                            </span>
                            <span id="icon-pass-confirm-show" class="hidden">
                                <x-bi-eye class="w-5 h-5" />
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <div
                class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 flex gap-3 items-start
                        dark:border-amber-500/30 dark:bg-amber-500/10
                        black:border-amber-500/30 black:bg-amber-500/10">
                <div class="text-amber-500 shrink-0 mt-0.5">
                    <x-bi-exclamation-triangle class="w-5 h-5" />
                </div>
                <p class="text-xs text-amber-800 dark:text-amber-300 black:text-amber-300 leading-relaxed">
                    Use ao menos 10 caracteres, incluindo letra maiúscula, minúscula, número e símbolo.
                </p>
            </div>

            <div class="pt-2 flex gap-3 justify-end">
                <button type="submit" form="logout-first-access"
                    class="w-full flex items-center justify-center gap-2 px-2 py-3.5 rounded-xl bg-red-600 text-white text-sm font-semibold shadow-md shadow-red-500/20
                           hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/30 hover:-translate-y-0.5 transition-all duration-200
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500
                           dark:focus:ring-offset-slate-900 black:focus:ring-offset-zinc-900">
                    Sair
                </button>
                <button type="submit" id="save-btn"
                    class="w-full flex items-center justify-center gap-2 px-2 py-3.5 rounded-xl bg-sky-600 text-white text-sm font-semibold shadow-md shadow-sky-500/20
                           hover:bg-sky-700 hover:shadow-lg hover:shadow-sky-600/30 hover:-translate-y-0.5 transition-all duration-200
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-500
                           dark:focus:ring-offset-slate-900 black:focus:ring-offset-zinc-900">
                    <span id="save-btn-spinner" class="hidden">
                        <x-bi-arrow-clockwise class="w-6 h-6 animate-spin" />
                    </span>
                    <span id="save-btn-text">Salvar</span>
                </button>

            </div>
        </form>
        <form id="logout-first-access" action="{{ route('logout') }}" method="POST" class="hidden">
            @csrf
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('first-access-form');

            if (!form) return;

            const passwordInput = document.getElementById('password');
            const passwordConfirmationInput = document.getElementById('password_confirmation');

            function showError(title, msg) {
                if (window.Alerts?.showError) {
                    window.Alerts.showError({
                        title,
                        msg
                    });
                    return;
                }

                alert(`${title}\n\n${msg}`);
            }

            function showSuccess(msg, title = 'Sucesso') {
                if (window.Alerts?.showSuccess) {
                    window.Alerts.showSuccess(msg, title);
                    return;
                }

                alert(`${title}\n\n${msg}`);
            }

            function showLoading(title, msg) {
                if (window.Alerts?.loading) {
                    window.Alerts.loading(title, msg);
                }
            }

            function setupToggle({
                inputId,
                buttonId,
                hideIconId,
                showIconId
            }) {
                const input = document.getElementById(inputId);
                const button = document.getElementById(buttonId);
                const hideIcon = document.getElementById(hideIconId);
                const showIcon = document.getElementById(showIconId);

                if (!input || !button || !hideIcon || !showIcon) return;

                button.addEventListener('click', (e) => {
                    e.preventDefault();

                    const isHidden = input.type === 'password';

                    input.type = isHidden ? 'text' : 'password';
                    hideIcon.classList.toggle('hidden', isHidden);
                    showIcon.classList.toggle('hidden', !isHidden);
                });
            }

            setupToggle({
                inputId: 'password',
                buttonId: 'toggle-pass',
                hideIconId: 'icon-pass-hide',
                showIconId: 'icon-pass-show',
            });

            setupToggle({
                inputId: 'password_confirmation',
                buttonId: 'toggle-pass-confirm',
                hideIconId: 'icon-pass-confirm-hide',
                showIconId: 'icon-pass-confirm-show',
            });


            const saveBtn = document.getElementById('save-btn');
            const saveBtnSpinner = document.getElementById('save-btn-spinner');
            const saveBtnText = document.getElementById('save-btn-text');

            form.addEventListener('submit', (e) => {
                const password = passwordInput?.value?.trim() ?? '';
                const passwordConfirmation = passwordConfirmationInput?.value?.trim() ?? '';

                // Validação frontend
                if (!password || !passwordConfirmation) {
                    e.preventDefault();
                    showError('Verifique os dados', 'Preencha a nova senha e a confirmação.');
                    return;
                }

                if (password.length < 10) {
                    e.preventDefault();
                    showError('Senha inválida', 'Use ao menos 10 caracteres, com letras maiúsculas e minúsculas, número e símbolo.');
                    return;
                }

                if (password !== passwordConfirmation) {
                    e.preventDefault();
                    showError('Senhas diferentes', 'A confirmação da senha não confere.');
                    return;
                }

                // Loading no botão
                if (saveBtn && saveBtnSpinner && saveBtnText) {
                    saveBtn.disabled = true;
                    saveBtnSpinner.classList.remove('hidden');
                    saveBtnText.textContent = 'Salvando...';
                    saveBtn.classList.add('opacity-70', 'cursor-not-allowed');
                }
            });

            // seu código existente
            if (typeof Alerts !== 'undefined') {
                @if (session('success'))
                    Alerts.showSuccess(@json(session('success')));
                @endif

                @if (session('error'))
                    Alerts.showError({
                        title: "Ops!",
                        msg: @json(session('error'))
                    });
                @endif

                @if ($errors->any())
                    let msgErro = "";
                    @foreach ($errors->all() as $error)
                        msgErro += @json($error) + "\n";
                    @endforeach
                    Alerts.showError({
                        title: "Verifique os dados",
                        msg: msgErro
                    });
                @endif
            }
        });
    </script>
</body>

</html>
