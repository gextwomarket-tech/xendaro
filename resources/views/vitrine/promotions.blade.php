@php
    $promotions = \App\Models\Promotion::actives()->latest('date_debut')->paginate(9);
@endphp
<x-layouts.public :title="__('app.promotions.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.promotions.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.promotions.subtitle') }}</p>
    </section>

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        @if($promotions->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.promotions.no_active') }}</p>
        @else
            <x-card-grid cols="3">
                @foreach($promotions as $promotion)
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5 flex flex-col">
                        <p class="font-display font-semibold text-texte-principal">{{ $promotion->titre }}</p>
                        @if($promotion->description)
                            <p class="mt-2 text-sm text-texte-secondaire flex-1">{{ \Illuminate\Support\Str::limit($promotion->description, 140) }}</p>
                        @endif
                        @if($promotion->date_fin)
                            <p class="mt-3 text-xs text-texte-secondaire">{{ __('app.promotions.valid_until', ['date' => $promotion->date_fin->format('d/m/Y')]) }}</p>
                        @endif
                        <button
                            type="button"
                            class="mt-4 text-sm font-medium text-couleur-principale hover:underline text-left"
                            x-data
                            x-on:click="$dispatch('open-modal', { name: 'promo-{{ $promotion->id }}' })"
                        >
                            {{ __('app.promotions.see_detail') }}
                        </button>
                    </div>

                    <x-modal name="promo-{{ $promotion->id }}" max-width="lg">
                        <h3 class="font-display text-xl font-semibold text-texte-principal mb-3">{{ $promotion->titre }}</h3>
                        <p class="text-sm text-texte-secondaire leading-relaxed whitespace-pre-line">{{ $promotion->description }}</p>
                        @if($promotion->date_fin)
                            <p class="mt-4 text-xs text-texte-secondaire">{{ __('app.promotions.valid_until', ['date' => $promotion->date_fin->format('d/m/Y')]) }}</p>
                        @endif
                        <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center justify-center w-full rounded-sm bg-couleur-principale text-fond-principal font-semibold px-5 py-2.5 hover:brightness-110 transition">
                            {{ __('app.promotions.cta') }}
                        </a>
                    </x-modal>
                @endforeach
            </x-card-grid>

            <div class="mt-8">{{ $promotions->links() }}</div>
        @endif
    </section>

</x-layouts.public>
