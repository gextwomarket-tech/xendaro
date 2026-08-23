@props(['tabs' => [], 'active' => null])
{{--
    Onglets reutilisables (Alpine.js). $tabs = [key => label].
    Le contenu de chaque panneau est fourni par l'appelant via des <div x-show="activeTab === 'key'" x-cloak>.
    Usage:
        <x-tabs :tabs="['pip' => 'Pip', 'margin' => 'Marge']">
            <div x-show="activeTab === 'pip'" x-cloak>...</div>
            <div x-show="activeTab === 'margin'" x-cloak>...</div>
        </x-tabs>
--}}
<div x-data="{ activeTab: @js($active ?? array_key_first($tabs)) }" {{ $attributes }}>
    <div class="flex gap-1 border-b border-bordure-subtile mb-6 overflow-x-auto">
        @foreach($tabs as $key => $label)
            <button
                type="button"
                x-on:click="activeTab = '{{ $key }}'"
                :class="activeTab === '{{ $key }}' ? 'border-couleur-principale text-texte-principal' : 'border-transparent text-texte-secondaire hover:text-texte-principal'"
                class="px-4 py-2.5 text-sm font-medium border-b-2 whitespace-nowrap transition"
            >{{ $label }}</button>
        @endforeach
    </div>
    <div>
        {{ $slot }}
    </div>
</div>
