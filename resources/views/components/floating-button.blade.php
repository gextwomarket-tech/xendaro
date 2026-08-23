@props(['href' => '#'])
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'fixed bottom-6 right-6 z-30 inline-flex items-center justify-center w-14 h-14 rounded-full bg-couleur-principale text-fond-principal shadow-lg hover:brightness-110 transition']) }}>
    {{ $slot }}
</a>
