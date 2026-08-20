<x-layouts.public title="Horários de Missa — Paróquia São Domingos de Gusmão">
    <x-portal.page-header title="Horários de Missa" description="Encontre os horários das celebrações e organize sua participação na vida litúrgica da comunidade." icon="clock" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <div class="mb-10 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-5 py-4 text-sm leading-6 text-amber-900">
                <x-bi-info-circle class="mt-1 size-4 shrink-0" />
                <p>Estrutura visual demonstrativa. Antes da publicação oficial, a secretaria atualizará horários, locais e celebrações especiais.</p>
            </div>

            <div class="grid gap-12 lg:grid-cols-[1.2fr_.8fr]">
                <div>
                    <h2 class="font-serif text-3xl font-semibold">Celebrações na Igreja Matriz</h2>
                    <div class="mt-7 divide-y divide-slate-200 border-y border-slate-200">
                        @foreach([
                            ['day' => 'Domingo', 'times' => ['07h30', '09h00', '10h30', '19h00'], 'note' => 'Celebrações dominicais'],
                            ['day' => 'Segunda a sexta', 'times' => ['07h00', '19h00'], 'note' => 'Missa ferial'],
                            ['day' => 'Sábado', 'times' => ['07h00', '17h00'], 'note' => 'Missa matinal e vespertina'],
                        ] as $schedule)
                            <div class="grid gap-4 py-7 sm:grid-cols-[12rem_1fr] sm:items-center">
                                <div><h3 class="font-serif text-xl font-semibold">{{ $schedule['day'] }}</h3><p class="mt-1 text-sm text-slate-500">{{ $schedule['note'] }}</p></div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($schedule['times'] as $time)
                                        <span class="rounded-lg border border-[#B3DEC1] bg-[#E5FCF5]/65 px-4 py-2 text-sm font-semibold text-[#006f67]">{{ $time }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <aside class="rounded-2xl bg-[#210124] p-7 text-white sm:p-9">
                    <x-bi-calendar-heart class="size-8 text-[#B3DEC1]" />
                    <h2 class="mt-6 font-serif text-3xl font-semibold">Celebrações especiais</h2>
                    <p class="mt-4 leading-7 text-white/70">Festas, solenidades e alterações de horário serão destacadas aqui e também na página de notícias.</p>
                    <div class="mt-8 border-t border-white/15 pt-6">
                        <p class="text-sm font-semibold text-[#B3DEC1]">Precisa confirmar um horário?</p>
                        <a href="{{ route('portal.paroquia') }}#secretaria" class="mt-4 inline-flex items-center gap-2 text-sm font-semibold">Fale com a secretaria <x-bi-arrow-right class="size-4" /></a>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</x-layouts.public>
