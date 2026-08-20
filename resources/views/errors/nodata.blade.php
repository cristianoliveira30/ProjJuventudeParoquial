<x-layouts.app title="{{ $title ?? 'Sem dados' }}">
    <div class="w-full min-h-[calc(100vh-4rem)] flex flex-col items-center justify-center px-6">
        {{-- CARD DE MENSAGEM --}}
        <div
            class="w-full max-w-md p-8 text-center rounded-2xl border border-solid transition-colors
                    bg-white border-slate-300
                    dark:bg-gray-900/50 dark:border-slate-700
                    black:bg-zinc-900 black:border-zinc-800">

            <div
                class="mx-auto mb-4 flex h-18 w-18 items-center justify-center rounded-full
                        bg-slate-100 text-slate-400
                        dark:bg-gray-900 dark:text-slate-400
                        black:bg-zinc-800 black:text-zinc-600">
                <x-bi-cone-striped class="h-6 w-6" />
            </div>

            {{-- TÍTULO --}}
            <h1
                class="text-lg font-['Arial_Black'] tracking-tight
                       text-slate-800 dark:text-white black:text-zinc-100">
                {{ $title ?? 'Em Construção' }}
            </h1>

            {{-- TEXTO DE APOIO --}}
            <p
                class="mt-2 text-sm leading-relaxed font-['Arial_Roman, Arial']
                      text-slate-500 dark:text-slate-400 black:text-zinc-500">
                No momento não existem dados para serem exibidos nesta seção ou a funcionalidade ainda está sendo
                implementada.
            </p>

            {{-- RODAPÉ TÉCNICO (OPCIONAL) --}}
            @isset($route_name)
                <div
                    class="mt-6 border-t pt-4 text-xs font-['Arial_Roman, Arial']
                            border-slate-100 text-slate-400
                            dark:border-slate-700 dark:text-slate-500
                            black:border-zinc-800 black:text-zinc-600">
                    Rota: {{ $route_name }}
                </div>
            @endisset
        </div>
    </div>
</x-layouts.app>
