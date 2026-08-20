<x-layouts.public title="A Paróquia — São Domingos de Gusmão">
    <x-portal.page-header title="A Paróquia" description="Uma comunidade chamada a celebrar, acolher e servir, caminhando unida na fé e na missão." icon="building" />

    <section class="py-16 sm:py-20">
        <div class="mx-auto grid max-w-6xl gap-12 px-5 sm:px-8 lg:grid-cols-[1.15fr_.85fr] lg:items-start">
            <article>
                <h2 class="font-serif text-4xl font-semibold">Nossa história será contada aqui</h2>
                <p class="mt-6 text-lg leading-8 text-slate-600">Esta página está preparada para receber a história da comunidade, sua caminhada pastoral, seus padroeiros e os momentos que formaram a identidade da Paróquia São Domingos de Gusmão.</p>
                <p class="mt-5 text-lg leading-8 text-slate-600">Também será possível apresentar o pároco, a equipe pastoral, os espaços da paróquia e as orientações para quem deseja participar.</p>
            </article>
            <aside id="secretaria" class="scroll-mt-28 rounded-2xl bg-[#210124] p-8 text-white">
                <x-bi-headset class="size-7 text-[#B3DEC1]" />
                <h2 class="mt-5 font-serif text-3xl font-semibold">Secretaria paroquial</h2>
                <div class="mt-6 space-y-4 text-sm leading-6 text-white/70">
                    <p><strong class="text-white">Atendimento</strong><br>Segunda a sexta, em horário comercial.</p>
                    <p><strong class="text-white">Contato</strong><br>Telefone e WhatsApp serão informados aqui.</p>
                    <p><strong class="text-white">Endereço</strong><br>Endereço oficial da igreja e secretaria.</p>
                </div>
            </aside>
        </div>
    </section>

    <section id="pastorais" class="scroll-mt-28 border-t border-slate-100 bg-[#fbfbfb] py-16 sm:py-20">
        <div class="mx-auto max-w-6xl px-5 sm:px-8">
            <h2 class="font-serif text-4xl font-semibold">Pastorais e movimentos</h2>
            <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">A estrutura visual já está preparada para apresentar cada serviço da comunidade e orientar novos participantes.</p>
            <div class="mt-10 grid gap-x-10 gap-y-0 border-y border-slate-200 md:grid-cols-2">
                @foreach(['Catequese', 'Pastoral da Liturgia', 'Pastoral Familiar', 'Ação Social', 'Ministros da Eucaristia', 'Grupos de Oração'] as $pastoral)
                    <div class="flex items-center gap-4 border-b border-slate-200 py-5 last:border-b-0 md:[&:nth-last-child(-n+2)]:border-b-0">
                        <x-bi-arrow-right-circle class="size-5 shrink-0 text-[#008f83]" /><span class="font-serif text-xl font-semibold">{{ $pastoral }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.public>
