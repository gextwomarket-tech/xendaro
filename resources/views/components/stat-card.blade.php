@props(['label', 'value', 'trend' => null, 'compact' => false])
<div {{ $attributes->merge(['class' => 'rounded-sm bg-fond-card border border-bordure-subtile ' . ($compact ? 'p-3' : 'p-5')]) }}>
    <div class="flex items-center justify-between gap-2">
        <p class="text-texte-secondaire text-xs uppercase tracking-wide">{{ $label }}</p>
        @isset($icon)
            <span class="text-couleur-principale">{{ $icon }}</span>
        @endisset
    </div>
    <p class="mt-2 {{ $compact ? 'text-lg' : 'text-2xl' }} font-display font-semibold text-texte-principal tabular-nums">{{ $value }}</p>
    @if($trend !== null)
        <p class="mt-1 text-xs {{ $trend >= 0 ? 'text-succes' : 'text-danger' }}">
            {{ $trend >= 0 ? '+' : '' }}{{ $trend }}%
        </p>
    @endif
</div>
