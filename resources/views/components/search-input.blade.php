@props(['placeholder' => null])
{{-- wire:model.live.debounce.400ms doit etre passe par le composant appelant via $attributes --}}
<div class="relative">
    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-texte-secondaire" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
    </svg>
    <input
        type="search"
        placeholder="{{ $placeholder ?? __('app.common.search') }}"
        {{ $attributes->merge(['class' => 'w-full rounded-sm bg-fond-surface border border-bordure-subtile pl-9 pr-3 py-2 text-sm text-texte-principal placeholder:text-texte-secondaire focus:outline-none focus:ring-1 focus:ring-couleur-principale']) }}
    >
</div>
