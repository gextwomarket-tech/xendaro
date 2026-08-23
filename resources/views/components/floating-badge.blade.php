@props(['position' => 'bottom-right'])
@php
    $positions = [
        'bottom-right' => '-bottom-6 -right-6 sm:-bottom-8 sm:-right-8',
        'bottom-left' => '-bottom-6 -left-6 sm:-bottom-8 sm:-left-8',
        'top-right' => '-top-6 -right-6 sm:-top-8 sm:-right-8',
        'top-left' => '-top-6 -left-6 sm:-top-8 sm:-left-8',
    ];
@endphp
{{--
    Card flottante superposee sur une <x-photo-card> (effet "pile" demande dans la DA).
    A placer dans un parent "relative" juste apres la photo-card qu'elle chevauche.
--}}
<div {{ $attributes->merge(['class' => 'absolute z-10 max-w-[75%] sm:max-w-[260px] rounded-sm bg-fond-card/95 backdrop-blur border border-bordure-subtile shadow-2xl p-4 hover:-translate-y-1 transition-transform duration-300 '.($positions[$position] ?? $positions['bottom-right'])]) }}>
    {{ $slot }}
</div>
