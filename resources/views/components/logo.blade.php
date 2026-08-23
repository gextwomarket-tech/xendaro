@props(['size' => 'md'])
@php
    $sizes = [
        'sm' => 'text-lg gap-1.5',
        'md' => 'text-2xl gap-2',
        'lg' => 'text-3xl gap-2.5',
    ];
@endphp
{{-- Logo "Xendaro Fox" construit en CSS/HTML (pas d'image bitmap), voir Instructions_de_base.design_ui_ux.logos --}}
<a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-flex items-center font-display font-bold tracking-tight text-texte-principal ' . ($sizes[$size] ?? $sizes['md'])]) }}>
    <span class="relative inline-flex items-center justify-center w-8 h-8 rounded-sm bg-couleur-principale text-fond-principal">
        <svg viewBox="0 0 24 24" class="w-5 h-5" fill="currentColor" aria-hidden="true">
            <path d="M12 2 4 7v6c0 5 3.5 8 8 9 4.5-1 8-4 8-9V7l-8-5Zm-3 8 3 2.2L15 10l1 1-4 6-4-6 1-1Z" />
        </svg>
    </span>
    <span>Xendaro<span class="text-couleur-principale">Fox</span></span>
</a>
