@props(['delay' => 0, 'direction' => 'up'])
@php
    $hidden = match ($direction) {
        'left' => '-translate-x-8',
        'right' => 'translate-x-8',
        'scale' => 'scale-95',
        default => 'translate-y-8',
    };
@endphp
{{--
    Wrapper d'animation au scroll (IntersectionObserver, sans dependance au plugin @alpinejs/intersect).
    Utilise sur 95% des sections des pages statiques pour l'apparition progressive (voir prompt DA).
--}}
<div
    x-data="{ shown: false }"
    x-init="
        const el = $el;
        const io = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    setTimeout(() => { shown = true }, {{ (int) $delay }});
                    io.unobserve(el);
                }
            });
        }, { threshold: 0.15 });
        io.observe(el);
    "
    :class="shown ? 'opacity-100 translate-x-0 translate-y-0 scale-100' : 'opacity-0 {{ $hidden }}'"
    {{ $attributes->merge(['class' => 'transition-all duration-700 ease-out']) }}
>
    {{ $slot }}
</div>
