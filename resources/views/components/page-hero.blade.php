@props(['image' => null, 'eyebrow' => null, 'align' => 'center', 'size' => 'lg'])
@php
    $sizes = ['sm' => 'py-16 sm:py-20', 'lg' => 'py-24 sm:py-32'];
    $isCenter = $align === 'center';
@endphp
{{--
    Hero anime reutilisable pour l'ensemble des pages statiques (chaque page fournit sa propre
    image/eyebrow/titre pour rester unique, voir prompt DA "hero design et anime pour chaque page").
--}}
<section {{ $attributes->merge(['class' => 'relative overflow-hidden '.($sizes[$size] ?? $sizes['lg'])]) }}>
    @if($image)
        <div class="absolute inset-0 -z-10">
            <img src="{{ $image }}" alt="" class="w-full h-full object-cover opacity-25 animate-hero-kenburns">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/50 via-fond-principal/85 to-fond-principal"></div>
        </div>
    @else
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-fond-surface via-fond-principal to-fond-principal"></div>
    @endif

    {{-- glow decoratif --}}
    <div class="absolute -top-24 {{ $isCenter ? 'left-1/2 -translate-x-1/2' : '-left-24' }} w-96 h-96 rounded-full bg-couleur-principale/10 blur-3xl -z-10"></div>

    <div class="relative max-w-4xl {{ $isCenter ? 'mx-auto text-center' : 'text-left' }} px-4 sm:px-6 lg:px-8">
        @if($eyebrow)
            <x-reveal>
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ $eyebrow }}
                </p>
            </x-reveal>
        @endif
        <x-reveal :delay="100">
            {{ $slot }}
        </x-reveal>
    </div>
</section>
