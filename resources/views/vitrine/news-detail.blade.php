<x-layouts.public :title="$article->titre()">

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
        <a href="{{ url('/actualites') }}" class="text-sm text-texte-secondaire hover:text-texte-principal transition inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            {{ __('app.news.back_to_news') }}
        </a>
        <h1 class="mt-4 font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ $article->titre() }}</h1>
        <div class="mt-3 flex flex-wrap items-center gap-2">
            @if($article->category)
                <span class="inline-block text-xs font-medium text-couleur-secondaire bg-couleur-secondaire/10 rounded-full px-2.5 py-1">{{ $article->category->nom_fr }}</span>
            @endif
            @if($article->instrument)
                <a href="{{ url('/marches/'.$article->instrument->symbole_interne) }}" class="inline-block text-xs font-medium text-couleur-principale bg-couleur-principale/10 rounded-full px-2.5 py-1 hover:brightness-110 transition">
                    {{ $article->instrument->nom }}
                </a>
            @endif
            @if($article->publie_le)
                <span class="text-xs text-texte-secondaire">{{ __('app.news.published_on', ['date' => $article->publie_le->format('d/m/Y')]) }}</span>
            @endif
        </div>
    </section>

    @if($article->image)
        <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
            <img src="{{ \Illuminate\Support\Facades\Storage::url($article->image) }}" alt="{{ $article->titre() }}" class="w-full rounded-sm border border-bordure-subtile">
        </section>
    @endif

    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="prose prose-invert max-w-none text-texte-secondaire leading-relaxed">
            @php $content = $article->contenu(); @endphp
            @if(strip_tags($content) === $content)
                <p>{!! nl2br(e($content)) !!}</p>
            @else
                {!! $content !!}
            @endif
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            <h2 class="font-display text-xl font-semibold text-texte-principal mb-6">{{ __('app.news.title') }}</h2>
            <x-card-grid cols="3">
                @foreach($related as $item)
                    <x-card-item href="{{ url('/actualites/'.$item->slug) }}" :title="$item->titre()" :description="\Illuminate\Support\Str::limit(strip_tags($item->contenu()), 90)" />
                @endforeach
            </x-card-grid>
        </section>
    @endif

</x-layouts.public>
