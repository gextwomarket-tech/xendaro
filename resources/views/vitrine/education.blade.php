@php
    $typeLabels = [
        'cours' => __('app.education.type_cours'),
        'glossaire' => __('app.education.type_glossaire'),
        'webinaire' => __('app.education.type_webinaire'),
    ];
@endphp
<x-layouts.public :title="__('app.education.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.education.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.education.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
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
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if($resources->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.common.no_results') }}</p>
        @else
            <x-card-grid cols="3">
                @foreach($resources as $resource)
                    <a href="{{ url('/academie/'.$resource->slug) }}" class="block rounded-sm bg-fond-card border border-bordure-subtile overflow-hidden hover:border-couleur-principale/50 transition group">
                        @if($resource->image)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($resource->image) }}" alt="{{ $resource->titre() }}" class="w-full h-36 object-cover">
                        @endif
                        <div class="p-5">
                            <span class="inline-block text-xs font-medium text-couleur-principale bg-couleur-principale/10 rounded-full px-2.5 py-1 mb-2">
                                {{ $typeLabels[$resource->type] ?? $resource->type }}
                            </span>
                            <p class="font-display font-semibold text-texte-principal group-hover:text-couleur-principale transition">{{ $resource->titre() }}</p>
                            <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit(strip_tags($resource->contenu()), 110) }}</p>
                        </div>
                    </a>
                @endforeach
            </x-card-grid>

            <div class="mt-8">{{ $resources->links() }}</div>
        @endif
    </section>

</x-layouts.public>
