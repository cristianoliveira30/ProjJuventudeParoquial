@props(['title', 'description', 'icon' => 'heart'])

<section class="border-b border-[#B3DEC1]/70 bg-[#E5FCF5]/70">
    <div class="mx-auto flex max-w-[1440px] flex-col gap-7 px-5 py-14 sm:px-8 md:flex-row md:items-center md:justify-between md:py-18 xl:px-12">
        <div class="max-w-4xl">
            <h1 class="font-serif text-4xl font-semibold tracking-[-.03em] text-[#210124] sm:text-5xl lg:text-6xl">{{ $title }}</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">{{ $description }}</p>
        </div>
        <span class="flex size-20 shrink-0 items-center justify-center rounded-full border border-[#B3DEC1] bg-white text-[#750D37] shadow-sm sm:size-24">
            <x-dynamic-component :component="'bi-' . $icon" class="size-9 sm:size-10" />
        </span>
    </div>
</section>
