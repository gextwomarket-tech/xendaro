@props(['name', 'overlay' => true, 'maxWidth' => 'md'])
@php
    $widths = ['sm' => 'max-w-sm', 'md' => 'max-w-md', 'lg' => 'max-w-lg', 'xl' => 'max-w-xl'];
@endphp
{{--
    Modale reutilisable (voir Instructions_de_base.design_ui_ux.popups) pour 95% des forms.
    Ouverture: window.dispatchEvent(new CustomEvent('open-modal', { detail: { name: '{{ $name }}' } }))
    ou depuis Alpine/Livewire: $dispatch('open-modal', { name: '{{ $name }}' })
    Fermeture: $dispatch('close-modal', { name: '{{ $name }}' })
    overlay=false => variante "floating panel" sans fond assombri (dialog de trade rapide type MT5).
--}}
<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail.name === '{{ $name }}') open = true"
    x-on:close-modal.window="if (!$event.detail || $event.detail.name === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center p-4']) }}
>
    @if($overlay)
        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-black/60" x-on:click="open = false"></div>
    @endif

    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-on:click.outside="open = false"
        class="relative w-full {{ $widths[$maxWidth] ?? $widths['md'] }} rounded-sm bg-fond-card border border-bordure-subtile shadow-2xl p-6"
    >
        <button type="button" class="absolute top-4 right-4 text-texte-secondaire hover:text-texte-principal" x-on:click="open = false" aria-label="{{ __('app.common.close') }}">
            &times;
        </button>

        {{ $slot }}
    </div>
</div>
