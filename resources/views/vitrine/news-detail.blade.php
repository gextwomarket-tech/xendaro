@php
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
    $heroImage = $article->image
        ? \Illuminate\Support\Facades\Storage::url($article->image)
        : 'https://picsum.photos/seed/xendaro-news-detail-generic/1600/900';
@endphp
<x-layouts.public :title="$article->titre()">

    <x-page-hero :image="$heroImage" :eyebrow="$article->category?->nom_fr ?? __('app.news.title')" align="left" size="sm">
        <a href="{{ url('/actualites') }}" class="text-sm text-texte-secondaire hover:text-couleur-principale transition inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            {{ __('app.news.back_to_news') }}
        </a>
        <h1 class="mt-4 font-display text-2xl sm:text-4xl font-bold text-texte-principal">{{ $article->titre() }}</h1>
        <div class="mt-4 flex flex-wrap items-center gap-2">
            @if($article->instrument)
                <a href="{{ url('/marches/'.$article->instrument->symbole_interne) }}" class="inline-block text-xs font-medium text-couleur-principale bg-couleur-principale/10 rounded-full px-2.5 py-1 hover:brightness-110 transition">
                    {{ $article->instrument->nom }}
                </a>
            @endif
            @if($article->publie_le)
                <span class="text-xs text-texte-secondaire">{{ __('app.news.published_on', ['date' => $article->publie_le->format('d/m/Y')]) }}</span>
            @endif
        </div>
    </x-page-hero>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal>
            <div class="prose prose-invert max-w-none text-texte-secondaire leading-relaxed">
                @php $content = $article->contenu(); @endphp
                @if(strip_tags($content) === $content)
                    <p>{!! nl2br(e($content)) !!}</p>
                @else
                    {!! $content !!}
                @endif
            </div>
        </x-reveal>
    </section>

    @if($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <x-reveal>
                <h2 class="font-display text-xl font-semibold text-texte-principal mb-6">{{ __('app.news.related_title') }}</h2>
            </x-reveal>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($related as $i => $item)
                    <x-reveal :delay="$i * 80">
                        <a href="{{ url('/actualites/'.$item->slug) }}" class="group block rounded-lg bg-fond-card border border-bordure-subtile overflow-hidden hover:border-couleur-principale/50 transition h-full">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                <img
                                    src="{{ $item->image ? \Illuminate\Support\Facades\Storage::url($item->image) : 'https://picsum.photos/seed/xendaro-news-related-'.$item->id.'/600/450' }}"
                                    alt="{{ $item->titre() }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105"
                                >
                            </div>
                            <div class="p-5">
                                <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $item->titre() }}</p>
                                <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($item->contenu()), 90) }}</p>
                            </div>
                        </a>
                    </x-reveal>
                @endforeach
            </div>
        </section>
    @endif

</x-layouts.public>
