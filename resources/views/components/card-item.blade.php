@props(['href' => null, 'title', 'description' => null])
@php $tag = $href ? 'a' : 'div'; @endphp
<{{ $tag }} @if($href) href="{{ $href }}" @endif {{ $attributes->merge(['class' => 'block rounded-sm bg-fond-card border border-bordure-subtile p-5 hover:border-couleur-principale/50 transition group']) }}>
    @isset($icon)
        <div class="w-10 h-10 rounded-sm bg-couleur-principale/10 text-couleur-principale flex items-center justify-center mb-3">
            {{ $icon }}
        </div>
    @endisset
    <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $title }}</p>
    @if($description)
        <p class="mt-1 text-sm text-texte-secondaire">{{ $description }}</p>
    @endif
</{{ $tag }}>
