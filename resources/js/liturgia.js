const skeleton = document.getElementById('liturgia-skeleton');
const content = document.getElementById('liturgia-content');

const escapeHtml = (value) => String(value ?? '')
    .replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;')
    .replaceAll('"', '&quot;').replaceAll("'", '&#039;');

const reading = (title, value) => {
    if (typeof value === 'string') return `<section class="border-b border-slate-200 py-8 last:border-b-0"><h2 class="font-serif text-4xl font-semibold">${title}</h2><p class="mt-4 text-lg leading-8 text-slate-600">${escapeHtml(value)}</p></section>`;
    return `<section class="border-b border-slate-200 py-8 last:border-b-0"><h2 class="font-serif text-4xl font-semibold">${title}</h2><h3 class="mt-4 text-xl leading-8 text-slate-600 font-bold">${escapeHtml(value?.titulo ?? value?.referencia)}</h3><p class="font-serif text-lg font-semibold">${escapeHtml(value?.referencia ?? value?.refrao)}</p><p class="mt-4 text-lg leading-8 text-slate-600">${escapeHtml(value?.texto ?? value?.comunhao)}</p></section>`;
};

const render = (data) => {
    content.innerHTML = `<div class="mx-auto grid max-w-6xl gap-10 px-5 sm:px-8 lg:grid-cols-[.75fr_1.25fr]"><aside class="h-fit rounded-2xl border border-[#B3DEC1] bg-[#E5FCF5]/55 p-7"><p class="text-sm font-semibold text-[#008f83]">Liturgia de hoje</p><section class="border-b border-slate-200 py-8"><h2 class="mt-3 font-serif text-3xl font-semibold">Oferendas</h2><p class="mt-4 leading-7 text-slate-600">${escapeHtml(data.oferendas)}</p></section><section class="border-b border-slate-200 py-8"><h2 class="mt-3 font-serif text-3xl font-semibold">Comunhão</h2><p class="mt-4 leading-7 text-slate-600">${escapeHtml(data.comunhao)}</p></section><p class="mt-6 rounded-xl bg-white p-4 text-sm leading-6 text-slate-500">${escapeHtml(data.dia)}</p></aside><article class="max-w-3xl"><div class="border-b border-slate-200 pb-8"><p class="text-sm font-semibold uppercase tracking-[.16em] text-[#750D37]">Leituras do dia</p><h2 class="mt-4 font-serif text-4xl font-semibold">Escutar, meditar e viver</h2></div>${reading('Primeira Leitura', data.primeiraLeitura)}${reading('Salmo', data.salmo)}${reading('Segunda Leitura', data.segundaLeitura)}${reading('Evangelho', data.evangelho)}${reading('Antífonas', data.antifonas)}</article></div>`;
};

const date = new Date();
const endpoint = `https://liturgia.up.railway.app/${String(date.getDate()).padStart(2, '0')}-${String(date.getMonth() + 1).padStart(2, '0')}`;

fetch(endpoint, { headers: { Accept: 'application/json' } })
    .then((response) => { if (!response.ok) throw new Error('Falha na API'); return response.json(); })
    .then((data) => { render(data); skeleton?.remove(); content?.classList.remove('hidden'); })
    .catch(() => { skeleton?.remove(); content?.classList.remove('hidden'); });
