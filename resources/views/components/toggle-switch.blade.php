@props(['checked' => false])
{{-- wire:model doit etre passe par le composant appelant via $attributes (fonctionne comme un vrai checkbox). --}}
<label class="relative inline-flex items-center cursor-pointer">
    <input type="checkbox" @checked($checked) {{ $attributes->merge(['class' => 'sr-only peer']) }}>
    <div class="relative w-11 h-6 bg-fond-surface border border-bordure-subtile rounded-full peer peer-checked:bg-couleur-principale transition-colors">
        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-texte-principal rounded-full transition-transform peer-checked:translate-x-5"></div>
    </div>
</label>
