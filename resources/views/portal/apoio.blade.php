<x-layouts.public title="Apoie o trabalho voluntário — Paróquia São Domingos de Gusmão">
    <section class="bg-[#E5FCF5]/45 px-5 py-16 sm:px-8 sm:py-24">
        <div class="mx-auto max-w-3xl overflow-hidden rounded-[1.5rem] border border-[#B3DEC1] bg-white shadow-[0_24px_70px_rgba(33,1,36,.08)]">
            <div class="grid md:grid-cols-[minmax(0,1fr)_18rem]">
                <div class="p-7 sm:p-10">
                    <a href="{{ route('portal.home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-[#008f83]"><x-bi-arrow-left class="size-4" /> Voltar ao portal</a>
                    <h1 class="mt-7 font-serif text-4xl font-semibold tracking-[-.03em] text-[#210124] sm:text-5xl">Apoie este trabalho voluntário</h1>
                    <p class="mt-5 text-lg leading-8 text-slate-600">Este portal foi desenvolvido voluntariamente por {{ config('parish.volunteer_author.name') }}. Se o trabalho foi útil para a comunidade, você pode contribuir de forma espontânea.</p>
                </div>
                <aside class="flex flex-col items-center justify-center bg-[#210124] p-7 text-center text-white sm:p-9">
                    <div class="rounded-2xl bg-white p-3 shadow-xl" aria-label="QR Code PIX demonstrativo">
                        <img src="{{ asset('assets/img/qr-code-pix.png') }}" alt="QR Code PIX demonstrativo" class="h-40 w-40 rounded-xl object-cover" />
                    </div>
                    <p class="mt-2 break-all text-sm text-white/75">{{ config('parish.volunteer_author.pix_key') }}</p>
                    <div class="mt-6 flex gap-3">
                        <a href="{{ config('parish.volunteer_author.github_url') }}" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-white/15 text-white/65 transition hover:border-[#B3DEC1] hover:text-[#B3DEC1]" aria-label="GitHub"><x-bi-github class="size-4" /></a>
                        <a href="{{ config('parish.volunteer_author.linkedin_url') }}" target="_blank" rel="noopener noreferrer" class="flex size-10 items-center justify-center rounded-full border border-white/15 text-white/65 transition hover:border-[#B3DEC1] hover:text-[#B3DEC1]" aria-label="LinkedIn"><x-bi-linkedin class="size-4" /></a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
