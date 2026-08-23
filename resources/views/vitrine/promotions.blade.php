@php
    $promotions = \App\Models\Promotion::actives()->latest('date_debut')->paginate(9);
    // TODO: remplacer par photographie sous licence Xendaro Fox avant production
@endphp
<x-layouts.public :title="__('app.promotions.title')">

    <x-page-hero image="/images/trading/trading-15.jpg" :eyebrow="__('app.promotions.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.promotions.hero_title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.promotions.subtitle') }}</p>
    </x-page-hero>

    {{-- Bande de statistiques --}}
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <x-reveal>
            <p class="text-center text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-8">{{ __('app.promotions.stats_title') }}</p>
        </x-reveal>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
            <x-reveal :delay="0">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="$promotions->total()" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.promotions.stat_active_label') }}</p>
            </x-reveal>
            <x-reveal :delay="100">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="100" suffix="%" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.promotions.stat_bonus_label') }}</p>
            </x-reveal>
            <x-reveal :delay="200">
                <p class="font-display text-4xl font-bold text-texte-principal"><x-animated-counter :value="24" suffix="h" /></p>
                <p class="mt-2 text-sm text-texte-secondaire">{{ __('app.promotions.stat_activation_label') }}</p>
            </x-reveal>
        </div>
    </section>

    {{-- Grille des promotions, restylee en cards image + badge flottant --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal>
            <h2 class="font-display text-2xl font-semibold text-texte-principal mb-8">{{ __('app.promotions.grid_title') }}</h2>
        </x-reveal>

        @if($promotions->isEmpty())
            <p class="text-center text-texte-secondaire py-12">{{ __('app.promotions.no_active') }}</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($promotions as $i => $promotion)
                    <x-reveal :delay="($i % 3) * 100">
                        <div class="relative pb-6">
                            <x-photo-card
                                :src="$promotion->image ? \Illuminate\Support\Facades\Storage::url($promotion->image) : '/images/trading/trading-'.str_pad((($promotion->id % 20) + 1), 2, '0', STR_PAD_LEFT).'.jpg'"
                                :alt="$promotion->titre"
                                :rotate="$i % 2 === 0 ? -1 : 1"
                            />
                            <x-floating-badge position="bottom-left">
                                <p class="text-xs text-texte-secondaire uppercase tracking-wide">{{ __('app.promotions.badge_label') }}</p>
                                @if($promotion->date_fin)
                                    <p class="text-sm font-display font-semibold text-couleur-principale">{{ __('app.promotions.valid_until', ['date' => $promotion->date_fin->format('d/m/Y')]) }}</p>
                                @else
                                    <p class="text-sm font-display font-semibold text-couleur-principale">{{ $promotion->titre }}</p>
                                @endif
                            </x-floating-badge>
                        </div>
                        <div class="mt-4">
                            <p class="font-display font-semibold text-lg text-texte-principal">{{ $promotion->titre }}</p>
                            @if($promotion->description)
                                <p class="mt-2 text-sm text-texte-secondaire">{{ \Illuminate\Support\Str::limit($promotion->description, 130) }}</p>
                            @endif
                            <button
                                type="button"
                                class="mt-4 text-sm font-medium text-couleur-principale hover:underline"
                                x-data
                                x-on:click="$dispatch('open-modal', { name: 'promo-{{ $promotion->id }}' })"
                            >
                                {{ __('app.promotions.see_detail') }}
                            </button>
                        </div>
                    </x-reveal>

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
            </div>

            <div class="mt-10">{{ $promotions->links() }}</div>
        @endif
    </section>

    {{-- Temoignage --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <x-reveal direction="scale">
            <div class="relative rounded-sm bg-fond-card border border-bordure-subtile p-8 sm:p-10 text-center">
                <p class="font-display text-xl sm:text-2xl italic text-texte-principal leading-relaxed">&laquo; {{ __('app.promotions.testimonial_quote') }} &raquo;</p>
                <div class="mt-6 flex items-center justify-center gap-3">
                    <x-avatar-initials :name="__('app.promotions.testimonial_name')" size="w-10 h-10" class="border border-bordure-subtile" />
                    <p class="text-sm text-texte-secondaire">{{ __('app.promotions.testimonial_name') }}</p>
                </div>
            </div>
        </x-reveal>
    </section>

    {{-- Banniere full-bleed CTA --}}
    <section class="relative overflow-hidden py-20">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-11.jpg" alt="" class="w-full h-full object-cover opacity-25">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal/70 via-fond-principal/90 to-fond-principal"></div>
        </div>
        <x-reveal direction="scale">
            <div class="max-w-3xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.promotions.banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire">{{ __('app.promotions.banner_text') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.promotions.banner_cta') }}
                </a>
            </div>
        </x-reveal>
    </section>

</x-layouts.public>
