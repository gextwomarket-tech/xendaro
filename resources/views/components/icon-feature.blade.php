@props(['title', 'description' => null])
<div {{ $attributes->merge(['class' => 'flex gap-4']) }}>
    <div class="shrink-0 w-11 h-11 rounded-sm bg-couleur-secondaire/10 text-couleur-secondaire flex items-center justify-center">
        {{ $icon ?? '' }}
    </div>
    <div>
        <p class="font-semibold text-texte-principal">{{ $title }}</p>
        @if($description)
            <p class="mt-1 text-sm text-texte-secondaire">{{ $description }}</p>
        @endif
    </div>
</div>
