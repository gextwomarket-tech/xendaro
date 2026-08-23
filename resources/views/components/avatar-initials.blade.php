@props(['name', 'size' => 'w-14 h-14'])
@php
    $initials = collect(explode(' ', trim($name)))->map(fn ($w) => mb_substr($w, 0, 1))->take(2)->implode('');
    $initials = mb_strtoupper($initials ?: '?');
@endphp
{{-- Avatar genere en CSS (initiales), evite de detourner une photo de graphique trading en portrait --}}
<div {{ $attributes->merge(['class' => $size.' rounded-full border-4 border-fond-principal bg-gradient-to-br from-couleur-principale to-couleur-secondaire flex items-center justify-center text-fond-principal font-display font-bold shadow-lg shrink-0']) }}>
    {{ $initials }}
</div>
