@php
    // La "une" (banniere full-bleed) n'est affichee que sur la 1ere page sans filtre categorie,
    // pour eviter toute incoherence avec la pagination des resultats filtres.
    $featured = (!$categoryId && $articles->currentPage() === 1) ? $articles->first() : null;
@endphp
<x-layouts.public :title="__('app.news.title')">

    {{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
    <x-page-hero image="/images/trading/trading-16.jpg" :eyebrow="__('app.news.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.news.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.news.subtitle') }}</p>
    </x-page-hero>

    {{-- A la une : banniere full-bleed sur le dernier article publie --}}
    @if($featured)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <x-reveal direction="scale">
                <a href="{{ url('/actualites/'.$featured->slug) }}" class="group relative block overflow-hidden rounded-lg border border-bordure-subtile min-h-[320px] sm:min-h-[420px]">
                    <img
                        src="{{ $featured->image ? \Illuminate\Support\Facades\Storage::url($featured->image) : '/images/trading/trading-09.jpg' }}"
                        alt="{{ $featured->titre() }}"
                        class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                    >
                    <div class="absolute inset-0 bg-gradient-to-t from-fond-principal via-fond-principal/60 to-fond-principal/10"></div>
                    <div class="relative flex flex-col justify-end min-h-[320px] sm:min-h-[420px] p-6 sm:p-10 max-w-2xl">
                        <span class="inline-flex w-fit items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                            {{ __('app.news.featured_badge') }}
                        </span>
                        <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal group-hover:text-couleur-principale transition">{{ $featured->titre() }}</h2>
                        <p class="mt-3 text-texte-secondaire max-w-xl">{{ \Illuminate\Support\Str::limit(strip_tags($featured->contenu()), 160) }}</p>
                        <div class="mt-5 flex items-center gap-2 text-sm font-semibold text-couleur-principale">
                            {{ __('app.news.featured_cta') }}
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                        </div>
                    </div>
                </a>
            </x-reveal>
        </section>
    @endif

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <x-reveal>
            <form method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between rounded-sm bg-fond-card border border-bordure-subtile p-4">
                <p class="font-display font-semibold text-texte-principal">{{ __('app.news.latest_title') }}</p>
                <x-select-filter
                    name="categorie"
                    onchange="this.form.submit()"
                    :options="$categories"
                    :selected="$categoryId"
                    :placeholder="__('app.news.filter_all')"
                />
                <button type="submit" class="hidden">{{ __('app.common.filter') }}</button>
            </form>
        </x-reveal>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if($articles->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.common.no_results') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php $shownIndex = 0; @endphp
                @foreach($articles as $article)
                    @continue($featured && $article->id === $featured->id)
                    <x-reveal :delay="($shownIndex % 3) * 80">
                        <a href="{{ url('/actualites/'.$article->slug) }}" class="group block rounded-lg bg-fond-card border border-bordure-subtile overflow-hidden hover:border-couleur-principale/50 transition h-full">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img
                                    src="{{ $article->image ? \Illuminate\Support\Facades\Storage::url($article->image) : '/images/trading/trading-'.str_pad((($article->id % 20) + 1), 2, '0', STR_PAD_LEFT).'.jpg' }}"
                                    alt="{{ $article->titre() }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-fond-principal/60 via-transparent to-transparent"></div>
                                @if($article->category)
                                    <span class="absolute top-3 left-3 inline-block text-xs font-medium text-couleur-secondaire bg-fond-card/90 backdrop-blur rounded-full px-2.5 py-1">{{ $article->category->nom_fr }}</span>
                                @endif
                            </div>
                            <div class="p-5">
                                @if($article->publie_le)
                                    <span class="text-xs text-texte-secondaire">{{ $article->publie_le->format('d/m/Y') }}</span>
                                @endif
                                <p class="mt-2 font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $article->titre() }}</p>
                                <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($article->contenu()), 110) }}</p>
                            </div>
                        </a>
                    </x-reveal>
                    @php $shownIndex++; @endphp
                @endforeach
            </div>

            <div class="mt-8">{{ $articles->links() }}</div>
        @endif
    </section>

</x-layouts.public>
