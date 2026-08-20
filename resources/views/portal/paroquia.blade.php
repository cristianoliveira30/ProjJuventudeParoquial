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
            <p class="mt-4 max-w-3xl text-lg leading-8 text-slate-600">Conheça os serviços da comunidade e encontre um espaço para participar da missão paroquial.</p>
            @forelse($groups as $group)
                <a href="{{ route('portal.pastorais.show', $group) }}" class="group grid gap-3 border-b border-slate-200 py-6 first:mt-8 sm:grid-cols-[10rem_minmax(0,1fr)_auto] sm:items-center">
                    <span class="text-sm font-semibold text-[#008f83]">{{ $group->typeLabel() }}</span>
                    <span><span class="block font-serif text-2xl font-semibold transition group-hover:text-[#750D37]">{{ $group->name }}</span><span class="mt-2 block leading-7 text-slate-600">{{ $group->summary }}</span></span>
                    <x-bi-arrow-right-circle class="size-5 text-[#008f83] transition group-hover:translate-x-1" />
                </a>
            @empty
                <div class="mt-10 rounded-2xl border border-dashed border-slate-300 px-6 py-12 text-center"><x-bi-person-hearts class="mx-auto size-9 text-[#008f83]" /><p class="mt-4 text-slate-600">As pastorais e movimentos serão apresentados aqui em breve.</p></div>
            @endforelse
        </div>
    </section>
</x-layouts.public>
