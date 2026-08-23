@php
    $typeLabels = [
        'cours' => __('app.education.type_cours'),
        'glossaire' => __('app.education.type_glossaire'),
        'webinaire' => __('app.education.type_webinaire'),
    ];
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="__('app.education.title')">

    <x-page-hero image="/images/trading/trading-15.jpg" :eyebrow="__('app.education.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.education.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.education.subtitle') }}</p>
    </x-page-hero>

    {{-- Banniere full-bleed ressource mise en avant --}}
    <section class="relative overflow-hidden py-16">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-19.jpg" alt="" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <span class="inline-block text-xs font-medium text-couleur-principale bg-couleur-principale/10 rounded-full px-2.5 py-1 mb-4">{{ __('app.education.featured_badge') }}</span>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.education.hero_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.education.subtitle') }}</p>
                <a href="#ressources" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.education.featured_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

    {{-- Bande de statistiques --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <p class="text-center text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-8">{{ __('app.education.stats_title') }}</p>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <x-reveal :delay="0">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="$resources->total()" suffix="+" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.education.stat_resources_label') }}</p>
            </x-reveal>
            <x-reveal :delay="100">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="$categories->count()" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.education.stat_categories_label') }}</p>
            </x-reveal>
            <x-reveal :delay="200">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="100" suffix="%" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.education.stat_free_label') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- Filtres + grille de ressources (donnees reelles paginees) --}}
    <section id="ressources" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 scroll-mt-24">
        <x-reveal>
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                <div class="w-full sm:max-w-xs">
                    <x-search-input name="search" value="{{ $search }}" :placeholder="__('app.education.search_placeholder')" />
                </div>
                <x-select-filter
                    name="categorie"
                    onchange="this.form.submit()"
                    :options="$categories"
                    :selected="$categoryId"
                    :placeholder="__('app.education.filter_all')"
                />
            </form>
        </x-reveal>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8">{{ __('app.education.grid_title') }}</h2>
        </x-reveal>
        @if($resources->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.common.no_results') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($resources as $i => $resource)
                    <x-reveal :delay="($i % 3) * 100">
                        <a href="{{ url('/academie/'.$resource->slug) }}" class="group block">
                            <div class="relative">
                                <x-photo-card
                                    :src="$resource->image ? \Illuminate\Support\Facades\Storage::url($resource->image) : '/images/trading/trading-'.str_pad((($resource->id % 20) + 1), 2, '0', STR_PAD_LEFT).'.jpg'"
                                    :alt="$resource->titre()"
                                    ratio="aspect-[16/10]"
                                />
                                <x-floating-badge position="top-left">
                                    <p class="text-xs font-medium text-couleur-principale">{{ $typeLabels[$resource->type] ?? $resource->type }}</p>
                                </x-floating-badge>
                            </div>
                            <div class="mt-4">
                                <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $resource->titre() }}</p>
                                <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($resource->contenu()), 110) }}</p>
                            </div>
                        </a>
                    </x-reveal>
                @endforeach
            </div>

            <div class="mt-10">{{ $resources->links() }}</div>
        @endif
    </section>

    {{-- Temoignage --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal direction="scale">
            <div class="relative rounded-sm bg-fond-card border border-bordure-subtile p-8 sm:p-10 text-center">
                <p class="font-display text-xl sm:text-2xl italic text-texte-principal leading-relaxed">&laquo; {{ __('app.education.testimonial_quote') }} &raquo;</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <x-avatar-initials :name="__('app.education.testimonial_name')" size="w-10 h-10" class="border border-bordure-subtile" />
                    <p class="text-sm text-texte-secondaire">{{ __('app.education.testimonial_name') }}</p>
                </div>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
