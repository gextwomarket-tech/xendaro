<x-layouts.public :title="__('app.news.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.news.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.news.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <form method="GET" class="flex justify-end">
            <x-select-filter
                name="categorie"
                onchange="this.form.submit()"
                :options="$categories"
                :selected="$categoryId"
                :placeholder="__('app.news.filter_all')"
            />
        </form>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if($articles->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.common.no_results') }}</p>
        @else
            <x-card-grid cols="3">
                @foreach($articles as $article)
                    <a href="{{ url('/actualites/'.$article->slug) }}" class="block rounded-sm bg-fond-card border border-bordure-subtile overflow-hidden hover:border-couleur-principale/50 transition group">
                        @if($article->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($article->image) }}" alt="{{ $article->titre() }}" class="w-full h-36 object-cover">
                        @endif
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-2">
                                @if($article->category)
                                    <span class="inline-block text-xs font-medium text-couleur-secondaire bg-couleur-secondaire/10 rounded-full px-2.5 py-1">{{ $article->category->nom_fr }}</span>
                                @endif
                                @if($article->publie_le)
                                    <span class="text-xs text-texte-secondaire">{{ $article->publie_le->format('d/m/Y') }}</span>
                                @endif
                            </div>
                            <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $article->titre() }}</p>
                            <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($article->contenu()), 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </x-card-grid>

            <div class="mt-8">{{ $articles->links() }}</div>
        @endif
    </section>

</x-layouts.public>
