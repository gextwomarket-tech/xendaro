@props(['src', 'alt' => '', 'ratio' => 'aspect-[4/3]', 'rotate' => null])
{{--
    Card image de base (coins arrondis, ombre, leger tilt optionnel) servant de brique pour
    composer des visuels superposes (voir <x-floating-badge> pour l'element flottant par-dessus).
    'rotate' attend un nombre de degres (ex: -2, 3) applique en style inline (classes Tailwind
    dynamiques non fiables avec le scan JIT sur des chaines composees en PHP).
--}}
<div
    {{ $attributes->merge(['class' => 'group relative overflow-hidden rounded-lg border border-bordure-subtile shadow-2xl '.$ratio]) }}
    @if($rotate) style="transform: rotate({{ (float) $rotate }}deg)" @endif
>
    <img src="{{ $src }}" alt="{{ $alt }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
    <div class="absolute inset-0 bg-gradient-to-t from-fond-principal/50 via-transparent to-transparent"></div>
</div>
