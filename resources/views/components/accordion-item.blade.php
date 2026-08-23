@props(['title', 'open' => false])
<div x-data="{ open: @js($open) }" class="p-0">
    <button
        type="button"
        x-on:click="open = !open"
        class="w-full flex items-center justify-between gap-4 text-left px-5 py-4 hover:bg-fond-surface/60 transition"
    >
        <span class="font-medium text-texte-principal">{{ $title }}</span>
        <svg class="w-4 h-4 shrink-0 text-texte-secondaire transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="px-5 pb-4 text-sm text-texte-secondaire leading-relaxed"
    >
        {{ $slot }}
    </div>
</div>
