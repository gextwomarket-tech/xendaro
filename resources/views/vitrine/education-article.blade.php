@php
    $heroImage = $resource->image
        ? \Illuminate\Support\Facades\Storage::url($resource->image)
        : '/images/trading/trading-01.jpg';
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="$resource->titre()">

    <x-page-hero :image="$heroImage" :eyebrow="__('app.education.hero_eyebrow')" align="left" size="sm">
        <a href="{{ url('/academie') }}" class="text-sm text-texte-secondaire hover:text-texte-principal transition inline-flex items-center gap-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            {{ __('app.education.back_to_academy') }}
        </a>
        <h1 class="font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ $resource->titre() }}</h1>
        @if($resource->category)
            <span class="mt-3 inline-block text-xs font-medium text-couleur-principale bg-couleur-principale/10 rounded-full px-2.5 py-1">
                {{ $resource->category->nom_fr }}
            </span>
        @endif
    </x-page-hero>

    {{-- Contenu de l'article --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <x-reveal>
            <div class="prose prose-invert max-w-none text-texte-secondaire leading-relaxed">
                @php $content = $resource->contenu(); @endphp
                @if(strip_tags($content) === $content)
                    <p>{!! nl2br(e($content)) !!}</p>
                @else
                    {!! $content !!}
                @endif
            </div>
        </x-reveal>
    </section>

    {{-- Points cles a retenir --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8 text-center">{{ __('app.education.takeaways_title') }}</h2>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <x-reveal :delay="0">
                <x-icon-feature :title="__('app.education.takeaway_1_title')" :description="__('app.education.takeaway_1_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="100">
                <x-icon-feature :title="__('app.education.takeaway_2_title')" :description="__('app.education.takeaway_2_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s4.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
            <x-reveal :delay="200">
                <x-icon-feature :title="__('app.education.takeaway_3_title')" :description="__('app.education.takeaway_3_desc')">
                    <x-slot:icon>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    </x-slot:icon>
                </x-icon-feature>
            </x-reveal>
        </div>
    </section>

    {{-- Ressources liees, restylees en photo-cards --}}
    @if($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <x-reveal>
                <h2 class="font-display text-xl font-semibold text-texte-principal mb-1">{{ __('app.education.related_title') }}</h2>
                <p class="text-sm text-texte-secondaire mb-6">{{ __('app.education.related_subtitle') }}</p>
            </x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($related as $i => $item)
                    <x-reveal :delay="($i % 3) * 100">
                        <a href="{{ url('/academie/'.$item->slug) }}" class="group block">
                            <x-photo-card
                                :src="$item->image ? \Illuminate\Support\Facades\Storage::url($item->image) : '/images/trading/trading-'.str_pad((($item->id % 20) + 1), 2, '0', STR_PAD_LEFT).'.jpg'"
                                :alt="$item->titre()"
                                ratio="aspect-[16/10]"
                            />
                            <div class="mt-3">
                                <p class="font-display font-semibold text-sm text-texte-principal group-hover:text-couleur-principale transition">{{ $item->titre() }}</p>
                                <p class="mt-1 text-xs text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($item->contenu()), 90) }}</p>
                            </div>
                        </a>
                    </x-reveal>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-18.jpg" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.education.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.education.banner_text') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.education.banner_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
