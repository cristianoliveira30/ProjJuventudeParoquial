<div id="settings-modal" tabindex="-1" aria-hidden="true" data-modal-backdrop="static" data-scroll-lock
    class="hidden overflow-hidden fixed top-0 right-0 left-0 z-[99997] justify-center items-center w-full md:inset-0 h-full backdrop-blur-sm bg-slate-900/50">

    <div class="relative w-full max-w-5xl h-[90vh] max-[800px]:h-[calc(100%-1rem)] p-4 max-[800px]:p-2">
        <div
            class="relative flex h-full bg-white dark:bg-slate-900 black:bg-zinc-950 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 black:border-zinc-800 ring-1 ring-black/5 dark:ring-white/10 overflow-hidden flex-row max-[800px]:flex-col">

            {{-- Botão fechar --}}
            <button type="button" data-modal-hide="settings-modal"
                class="absolute top-3 right-3 z-[99997]
                       inline-flex items-center justify-center w-9 h-9 rounded-full
                      text-slate-500 shadow-md
                       hover:bg-slate-100 hover:text-slate-800
                       dark:bg-gray-900/90 dark:text-slate-300 dark:border-slate-700 dark:hover:bg-slate-700 dark:hover:text-white
                       black:bg-zinc-900/90 black:text-zinc-300 black:border-zinc-800 black:hover:bg-zinc-800 black:hover:text-white
                       transition-colors">
                <x-bi-x-lg class="w-4 h-4" />
            </button>

            {{-- Sidebar --}}
            <div
                class="w-64 max-[800px]:w-full shrink-0 flex flex-col border-r max-[800px]:border-r-0 max-[800px]:border-b border-slate-200 dark:border-slate-700 black:border-zinc-800 bg-slate-50/80 dark:bg-gray-900/30 black:bg-zinc-900/50 backdrop-blur-xl">

                <div class="p-6 max-[800px]:p-4 max-[800px]:pr-14 pb-4 flex justify-between items-center">
                    <h2
                        class="text-xl font-bold text-slate-800 dark:text-white black:text-zinc-100 flex items-center gap-2">
                        <x-bi-gear-wide-connected class="w-6 h-6 text-sky-500" /> Ajustes
                    </h2>
                </div>
                <nav class="flex-1 px-3 py-2 space-y-1 overflow-y-auto max-[800px]:flex-none max-[800px]:flex max-[800px]:flex-row max-[800px]:gap-2 max-[800px]:space-y-0 max-[800px]:overflow-x-auto max-[800px]:pb-3 scroll-smooth"
                    id="settings-tabs-container">
                    <button type="button" data-tab-target="geral"
                        class="settings-tab-link active flex items-center w-full max-[800px]:w-auto whitespace-nowrap gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group bg-white dark:bg-gray-900 black:bg-zinc-800 shadow-sm text-sky-600 dark:text-sky-400 ring-1 ring-black/5 dark:ring-white/5">
                        <x-bi-hdd-stack class="w-4 h-4" /> Geral
                    </button>
                    <button type="button" data-tab-target="personalizacao"
                        class="settings-tab-link flex items-center w-full max-[800px]:w-auto whitespace-nowrap gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group text-slate-600 dark:text-slate-400 black:text-zinc-400 hover:bg-slate-100 dark:hover:bg-slate-800 black:hover:bg-zinc-900 black:hover:text-zinc-200">
                        <x-bi-palette class="w-4 h-4" /> Personalização
                    </button>
                    <button type="button" data-tab-target="conta"
                        class="settings-tab-link flex items-center w-full max-[800px]:w-auto whitespace-nowrap gap-3 px-3 py-2.5 text-sm font-medium rounded-lg transition-all duration-200 group text-slate-600 dark:text-slate-400 black:text-zinc-400 hover:bg-slate-100 dark:hover:bg-slate-800 black:hover:bg-zinc-900 black:hover:text-zinc-200">
                        <x-bi-person-circle class="w-4 h-4" /> Conta
                    </button>
                </nav>
                @auth
                    <div
                        class="p-4 mt-auto border-t border-slate-200 dark:border-slate-700 black:border-zinc-800 max-[800px]:hidden">
                        <div class="flex items-center gap-3 w-full">
                            <img src="{{ optional(auth()->user())->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(optional(auth()->user())->name) }}"
                                alt="Avatar"
                                class="shrink-0 w-10 h-10 rounded-full border border-slate-200 dark:border-slate-600 black:border-zinc-700 object-cover">
                            <div class="flex-1 min-w-0 flex flex-col justify-center">
                                <p
                                    class="text-sm font-bold text-slate-700 dark:text-slate-200 black:text-zinc-100 truncate leading-tight">
                                    {{ optional(auth()->user())->name }}
                                </p>
                                <p
                                    class="text-[11px] text-slate-500 black:text-zinc-500 truncate font-medium leading-tight">
                                    {{ optional(auth()->user())->email }}
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="p-4 mt-auto border-t border-slate-200 dark:border-slate-700 black:border-zinc-800 max-[800px]:hidden">
                        <div class="text-sm font-medium text-slate-700 dark:text-slate-200 black:text-zinc-100">
                            <a href="{{ route('login') }}" class="text-sky-600 hover:text-sky-500">Entrar</a>
                        </div>
                    </div>
                @endauth
            </div>

            <div class="flex-1 overflow-y-auto p-8 max-[800px]:p-4 lg:p-10 scroll-smooth">

                {{-- Aba Geral --}}
                <div id="content-geral" class="settings-content block animate-fade-in-up">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white black:text-zinc-100">Geral</h2>
                        <p class="text-slate-500 black:text-zinc-500 text-sm mt-1">Visão geral do sistema</p>
                    </div>
                    <div
                        class="relative overflow-hidden rounded-xl p-6 mb-8 shadow-lg border bg-white border-slate-200 dark:bg-slate-900 dark:border-slate-700 black:bg-zinc-950 black:border-zinc-800">
                        <div
                            class="absolute top-0 right-0 w-40 h-40 rounded-full -mr-10 -mt-10 blur-3xl bg-sky-100 dark:bg-sky-500/20 black:bg-sky-500/10">
                        </div>
                        <div class="relative z-10 flex gap-4">
                            <div
                                class="p-3 h-fit rounded-xl backdrop-blur-sm border bg-sky-100 border-sky-100 dark:bg-white/10 dark:border-white/10 black:bg-zinc-900 black:border-zinc-800">
                                <x-bi-shield-check class="w-6 h-6 text-sky-900 dark:text-sky-400 black:text-sky-500" />
                            </div>
                            <div>
                                <h4 class="text-lg font-bold mb-1 text-slate-800 dark:text-white black:text-zinc-100">
                                    Status da Conta</h4>
                                <p
                                    class="text-sm max-w-md leading-relaxed text-slate-500 dark:text-slate-300 black:text-zinc-400">
                                    Sua conta está ativa e operando normalmente.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aba Personalização --}}
                <div id="content-personalizacao" class="settings-content hidden animate-fade-in-up">
                    <div class="flex flex-col min-h-[calc(90vh-8rem)] max-[800px]:min-h-[calc(100vh-8rem)] relative">
                        {{-- Conteúdo Principal da Aba --}}
                        <div class="flex-1">
                            <div class="mb-6">
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white black:text-zinc-100">
                                    Personalização</h2>
                                <p class="text-slate-500 black:text-zinc-500 text-sm mt-1">Escolha a aparência que
                                    mais lhe agrada.</p>
                            </div>
                            <div class="space-y-6">
                                <div>
                                    <h3
                                        class="text-sm font-bold text-slate-800 dark:text-white black:text-zinc-200 mb-4">
                                        Tema da Interface</h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                        <button type="button" data-theme="light"
                                            class="set-theme-btn group relative block w-full text-left p-4 bg-slate-50 border-2 rounded-xl transition-all border-slate-200 hover:border-sky-500">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="p-2 bg-white rounded-lg shadow-sm">
                                                    <x-bi-sun class="w-6 h-6 text-amber-500" />
                                                </div>
                                                <div
                                                    class="theme-ring w-4 h-4 rounded-full border border-slate-300 group-hover:border-sky-500 transition-colors">
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold text-slate-800">Modo Claro</span>
                                        </button>
                                        {{-- comentado para a vedação --}}
                                        <button type="button" data-theme="dark"
                                            class="set-theme-btn group relative block w-full text-left p-4 bg-slate-800 border-2 rounded-xl transition-all border-slate-700 hover:border-purple-500">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="p-2 bg-slate-700 rounded-lg shadow-sm">
                                                    <x-bi-moon-stars class="w-6 h-6 text-purple-400" />
                                                </div>
                                                <div
                                                    class="theme-ring w-4 h-4 rounded-full border border-slate-500 group-hover:border-purple-500 transition-colors">
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold text-slate-100">Modo Neon</span>
                                        </button>
                                        <button type="button" data-theme="black"
                                            class="set-theme-btn group relative block w-full text-left p-4 bg-zinc-950 border-2 rounded-xl transition-all border-zinc-800 hover:border-gray-500">
                                            <div class="flex justify-between items-start mb-2">
                                                <div
                                                    class="p-2 bg-zinc-900 rounded-lg shadow-sm border border-zinc-800">
                                                    <div
                                                        class="w-6 h-6 bg-zinc-950 rounded-full border border-zinc-700">
                                                    </div>
                                                </div>
                                                <div
                                                    class="theme-ring w-4 h-4 rounded-full border border-zinc-700 group-hover:border-gray-500 transition-colors">
                                                </div>
                                            </div>
                                            <span class="text-sm font-bold text-zinc-300">Modo Black</span>
                                        </button>
                                    </div>
                                    <div id="theme-pending-alert"
                                        class="hidden mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200 black:border-amber-500/30 black:bg-amber-500/10 black:text-amber-200">
                                        Tema selecionado. Clique em <strong>Aplicar </strong> para confirmar a
                                        alteração.
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botão Fixo Flutuante no Canto Direito (Aplicar Tema) --}}
                        <div class="sticky bottom-0 w-full flex justify-end mt-8 pb-2 z-30 pointer-events-none">
                            <div
                                class="pointer-events-auto bg-white/70 dark:bg-gray-900/70 black:bg-zinc-900/70 backdrop-blur-md p-2 rounded-2xl shadow-xl ring-1 ring-slate-200/50 dark:ring-white/10 black:ring-white/5">
                                <button type="button" id="apply-theme-btn"
                                    class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-sky-600 rounded-xl hover:bg-sky-500 active:scale-95 transition-all shadow-lg shadow-sky-500/20 ring-1 ring-white/10">
                                    <span data-loading-text>Aplicar</span>
                                    <x-bi-check-lg data-loading-default-icon class="w-5 h-5" />
                                    <x-bi-arrow-clockwise data-loading-icon class="hidden animate-spin w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aba Conta --}}
                <div id="content-conta" class="settings-content hidden animate-fade-in-up">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-slate-800 dark:text-white black:text-zinc-100">Minha
                            Conta</h2>
                        <p class="text-slate-500 black:text-zinc-500 text-sm mt-1">Gerencie seus dados pessoais e
                            senha.</p>
                    </div>
                    <form id="form-update-user" action="{{ route('user.profile.update') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="space-y-8 max-w-3xl">
                            {{-- Foto --}}
                            <div
                                class="p-6 max-[800px]:p-4 rounded-xl border border-slate-200 dark:border-slate-700 black:border-zinc-800 bg-slate-50/50 dark:bg-gray-900/20 black:bg-zinc-900/50">
                                <div class="flex items-center gap-6 max-[800px]:flex-col max-[800px]:items-start">
                                    <div class="relative group">
                                        <img id="preview-avatar"
                                            src="{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                            alt="Profile"
                                            class="w-24 h-24 rounded-full object-cover border-4 border-white dark:border-slate-700 black:border-zinc-800 shadow-md">
                                        <label for="photo"
                                            class="absolute bottom-1 right-1 bg-sky-500 text-white p-2 rounded-full cursor-pointer hover:bg-sky-600 shadow-lg transition-transform hover:scale-105">
                                            <x-bi-camera class="w-4 h-4" />
                                        </label>
                                        <input type="file" id="photo" name="photo" class="hidden"
                                            accept="image/*">
                                    </div>
                                    <div>
                                        <h4
                                            class="text-base font-bold text-slate-800 dark:text-white black:text-zinc-100">
                                            Foto de Perfil</h4>
                                        <p class="text-xs text-slate-500 black:text-zinc-500 mt-1 max-w-xs">
                                            Carregue uma imagem para personalizar seu perfil. Suporta JPG, PNG.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Inputs --}}
                            <div>
                                <h3
                                    class="text-sm font-bold text-slate-800 dark:text-white black:text-zinc-100 uppercase tracking-wider mb-4 border-b border-slate-200 dark:border-slate-700 black:border-zinc-800 pb-2">
                                    Informações Básicas</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="md:col-span-2 space-y-1">
                                        <label
                                            class="text-xs font-bold text-slate-500 dark:text-slate-400 black:text-zinc-400">Nome
                                            Completo</label>
                                        <input type="text" name="name"
                                            value="{{ old('name', auth()->user()->name) }}"
                                            class="w-full bg-white dark:bg-gray-900 black:bg-zinc-900 border border-slate-300 dark:border-slate-700 black:border-zinc-800 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block p-2.5 dark:text-white black:text-zinc-100 black:placeholder-zinc-600 shadow-sm">
                                        @error('name')
                                            <span class="text-red-500 text-xs font-medium">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs font-bold text-slate-500 dark:text-slate-400 black:text-zinc-400">Email</label>
                                        <input type="email" name="email"
                                            value="{{ old('email', auth()->user()->email) }}"
                                            class="w-full bg-white dark:bg-gray-900 black:bg-zinc-900 border border-slate-300 dark:border-slate-700 black:border-zinc-800 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block p-2.5 dark:text-white black:text-zinc-100 black:placeholder-zinc-600 shadow-sm">
                                        @error('email')
                                            <span class="text-red-500 text-xs font-medium">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs font-bold text-slate-500 dark:text-slate-400 black:text-zinc-400">Usuário</label>
                                        <input type="text" name="username" value="{{ auth()->user()->username }}"
                                            class="w-full bg-white dark:bg-gray-900 black:bg-zinc-900 border border-slate-300 dark:border-slate-700 black:border-zinc-800 text-sm rounded-lg focus:ring-sky-500 focus:border-sky-500 block p-2.5 dark:text-white black:text-zinc-100 black:placeholder-zinc-600 shadow-sm">
                                        @error('username')
                                            <span class="text-red-500 text-xs font-medium">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Segurança --}}
                            <div class="flex flex-col gap-4 rounded-xl border border-[#B3DEC1]/70 bg-[#E5FCF5]/55 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-emerald-900/50 dark:bg-emerald-950/20">
                                <div class="flex items-start gap-3">
                                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white text-[#750D37] dark:bg-slate-900 dark:text-rose-300">
                                        <x-bi-shield-lock class="size-5" />
                                    </span>
                                    <div>
                                        <h3 class="text-sm font-bold text-[#210124] dark:text-white">Senha e segurança</h3>
                                        <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-300">A alteração de senha fica em uma área protegida e exige sua senha atual.</p>
                                    </div>
                                </div>
                                <a href="{{ route('account.security') }}" class="inline-flex min-h-10 shrink-0 items-center justify-center gap-2 rounded-xl border border-[#14B8A6] bg-white px-4 text-xs font-semibold text-[#0f9688] transition hover:bg-[#E5FCF5] dark:bg-slate-900 dark:text-emerald-300">
                                    Alterar senha <x-bi-arrow-right class="size-3.5" />
                                </a>
                            </div>

                            {{-- Rodapé de Ação - Aba Conta --}}
                            <div
                                class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-700 black:border-zinc-800 flex justify-end">
                                <button type="submit" id="save-account-btn" form="form-update-user"
                                    class="inline-flex items-center gap-2 px-8 py-3 text-sm font-bold text-white bg-sky-600 rounded-xl hover:bg-sky-500 active:scale-95 transition-all shadow-lg shadow-sky-500/20 ring-1 ring-white/10">
                                    <span data-loading-text>Salvar</span>
                                    <x-bi-cloud-arrow-up data-loading-default-icon class="w-5 h-5" />
                                    <x-bi-arrow-clockwise data-loading-icon class="hidden animate-spin w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
