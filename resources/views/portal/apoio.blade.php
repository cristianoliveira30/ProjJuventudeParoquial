<x-layouts.public title="Apoie o trabalho voluntário — Paróquia São Domingos de Gusmão">
    <main id="conteudo" class="bg-[#E5FCF5]/45 px-5 py-16 sm:px-8 sm:py-24">
        <div class="mx-auto max-w-3xl overflow-hidden rounded-[1.5rem] border border-[#B3DEC1] bg-white shadow-[0_24px_70px_rgba(33,1,36,.08)]">
            <div class="grid md:grid-cols-[minmax(0,1fr)_18rem]">
                <div class="p-7 sm:p-10">
                    <a href="{{ route('portal.home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]"><x-bi-arrow-left class="size-4" /> Voltar ao portal</a>
                    <h1 class="mt-7 font-serif text-4xl font-semibold tracking-[-.03em] text-[#210124] sm:text-5xl">Apoie este trabalho voluntário</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-600">Este portal foi desenvolvido voluntariamente por {{ config('parish.volunteer_author.name') }}. Se o trabalho foi útil para a comunidade, você pode contribuir de forma espontânea.</p>
                    <div class="mt-8 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm leading-6 text-amber-800"><strong>Dados demonstrativos:</strong> este QR Code e a chave PIX são provisórios e ainda não devem ser usados para pagamentos reais.</div>
                </div>
                <aside class="flex flex-col items-center justify-center bg-[#210124] p-7 text-center text-white sm:p-9">
                    <div class="rounded-2xl bg-white p-3 shadow-xl" aria-label="QR Code PIX demonstrativo">
                        <svg viewBox="0 0 21 21" class="size-44" role="img" aria-label="QR Code demonstrativo, sem dados reais">
                            <rect width="21" height="21" fill="white"/>
                            <path fill="#210124" d="M1 1h7v7H1zM13 1h7v7h-7zM1 13h7v7H1zM3 3v3h3V3zm12 0v3h3V3zM3 15v3h3v-3zM10 1h2v2h-2zM9 4h3v2H9zM10 7h2v3h-2zM13 9h2v2h-2zM16 9h4v2h-4zM9 12h2v2H9zM12 12h3v3h-3zM16 13h2v2h-2zM19 12h1v4h-1zM9 16h3v2H9zM13 17h2v3h-2zM16 16h4v2h-4zM9 19h2v1H9zM17 19h3v1h-3z"/>
                        </svg>
                    </div>
                    <p class="mt-5 text-xs font-semibold uppercase tracking-[.18em] text-[#B3DEC1]">PIX demonstrativo</p>
                    <p class="mt-2 break-all text-sm text-white/75">{{ config('parish.volunteer_author.pix_key') }}</p>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ config('parish.volunteer_author.github_url') }}" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-white/15 text-white/65 transition hover:border-[#B3DEC1] hover:text-[#B3DEC1]" aria-label="GitHub"><x-bi-github class="size-4" /></a>
                        <a href="{{ config('parish.volunteer_author.linkedin_url') }}" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-white/15 text-white/65 transition hover:border-[#B3DEC1] hover:text-[#B3DEC1]" aria-label="LinkedIn"><x-bi-linkedin class="size-4" /></a>
                    </div>
                </aside>
            </div>
        </div>
    </main>
</x-layouts.public>
