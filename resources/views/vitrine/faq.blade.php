@php
    $search = request()->query('search');
    $categoryId = request()->query('categorie');
    $categories = \App\Models\Category::ofType('faq')->pluck('nom_fr', 'id');
    $faqs = \App\Models\FaqContent::with('categorie')
        ->where('est_actif', true)
        ->when($search, fn ($q) => $q->where('question_fr', 'like', "%{$search}%")->orWhere('question_en', 'like', "%{$search}%"))
        ->when($categoryId, fn ($q) => $q->where('categorie_id', $categoryId))
        ->orderBy('ordre')
        ->paginate(10)
        ->withQueryString();
@endphp
<x-layouts.public :title="__('app.faq.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.faq.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.faq.subtitle') }}</p>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <x-search-input name="search" value="{{ $search }}" :placeholder="__('app.faq.search_placeholder')" />
            </div>
            <x-select-filter
                name="categorie"
                onchange="this.form.submit()"
                :options="$categories"
                :selected="$categoryId"
                :placeholder="__('app.faq.filter_all')"
            />
            <button type="submit" class="rounded-sm bg-fond-surface border border-bordure-subtile px-4 py-2 text-sm text-texte-principal hover:border-couleur-principale/50 transition">
                {{ __('app.common.filter') }}
            </button>
        </form>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        @if($faqs->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.faq.no_results') }}</p>
        @else
            <x-accordion>
                @foreach($faqs as $faq)
                    <x-accordion-item :title="app()->getLocale() === 'en' && $faq->question_en ? $faq->question_en : $faq->question_fr">
                        {{ app()->getLocale() === 'en' && $faq->reponse_en ? $faq->reponse_en : $faq->reponse_fr }}
                    </x-accordion-item>
                @endforeach
            </x-accordion>

            <div class="mt-8">{{ $faqs->links() }}</div>
        @endif
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
