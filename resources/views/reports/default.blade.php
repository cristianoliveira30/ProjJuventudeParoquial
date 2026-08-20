<x-layouts.app :title="__('Relatórios')">
    <div class="mx-auto w-full max-w-6xl px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <section class="rounded-[1.25rem] border border-slate-200 bg-white p-6 shadow-[0_10px_35px_rgba(33,1,36,.05)] dark:border-slate-800 dark:bg-slate-900 sm:p-8">
            <div class="flex size-12 items-center justify-center rounded-xl bg-[#E5FCF5] text-[#14B8A6] dark:bg-emerald-950/40"><x-bi-bar-chart class="size-6" /></div>
            <h1 class="mt-5 text-3xl font-bold tracking-tight text-[#210124] dark:text-white">Relatórios paroquiais</h1>
            <p class="mt-3 max-w-2xl text-sm leading-6 text-slate-500 dark:text-slate-400">A estrutura está reservada para os relatórios dos próximos módulos da paróquia. Os modelos demonstrativos do sistema anterior foram removidos.</p>
            <a href="{{ route('home') }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-[#0f9688] hover:text-[#750D37]"><x-bi-arrow-left class="size-4" /> Voltar ao início</a>
        </section>
    </div>
</x-layouts.app>
