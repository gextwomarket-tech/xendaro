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

    // Regroupement par categorie pour un rendu accordeon segmente (voir DA section G).
    $grouped = $faqs->getCollection()->groupBy(fn ($f) => $f->categorie?->nom_fr ?? __('app.faq.uncategorized'));
@endphp
<x-layouts.public :title="__('app.faq.title')">

    {{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
    <x-page-hero image="https://picsum.photos/seed/xendaro-faq-support/1600/900" :eyebrow="__('app.faq.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.faq.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.faq.subtitle') }}</p>
    </x-page-hero>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <x-reveal>
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
        </x-reveal>
    </section>

    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        @if($faqs->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.faq.no_results') }}</p>
        @else
            <div class="space-y-10">
                @foreach($grouped as $categoryName => $items)
                    <x-reveal>
                        <h2 class="font-display text-lg font-semibold text-texte-principal mb-3">{{ $categoryName }}</h2>
                        <x-accordion>
                            @foreach($items as $faq)
                                <x-accordion-item :title="app()->getLocale() === 'en' && $faq->question_en ? $faq->question_en : $faq->question_fr">
                                    {{ app()->getLocale() === 'en' && $faq->reponse_en ? $faq->reponse_en : $faq->reponse_fr }}
                                </x-accordion-item>
                            @endforeach
                        </x-accordion>
                    </x-reveal>
                @endforeach
            </div>

            <div class="mt-8">{{ $faqs->links() }}</div>
        @endif
    </section>

    {{-- Toujours besoin d'aide : CTA split image/texte avant le footer --}}
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-24">
        <x-reveal>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center rounded-lg bg-fond-card border border-bordure-subtile p-6 sm:p-10">
                <div class="relative">
                    {{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
                    <x-photo-card src="https://picsum.photos/seed/xendaro-faq-contact-cta/700/560" :alt="__('app.faq.cta_title')" ratio="aspect-[4/3]" :rotate="-2" />
                    <x-floating-badge position="bottom-right">
                        <p class="text-sm font-semibold text-texte-principal">{{ __('app.faq.cta_badge_title') }}</p>
                        <p class="text-xs text-texte-secondaire mt-0.5">{{ __('app.faq.cta_badge_text') }}</p>
                    </x-floating-badge>
                </div>
                <div>
                    <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.faq.cta_title') }}</h2>
                    <p class="mt-4 text-texte-secondaire">{{ __('app.faq.cta_text') }}</p>
                    <a href="{{ url('/contact') }}" class="mt-6 inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 hover:shadow-[0_0_24px_rgba(245,166,35,0.35)] transition">
                        {{ __('app.faq.cta_button') }}
                    </a>
                </div>
            </div>
        </x-reveal>
    </section>

    <x-floating-button href="{{ url('/contact') }}" aria-label="{{ __('app.floating.support') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    </x-floating-button>

</x-layouts.public>
