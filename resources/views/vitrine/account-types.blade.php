{{-- TODO: remplacer par photographie sous licence Xendaro Fox avant production --}}
@php
    $accountTypes = \App\Models\AccountType::where('est_actif', true)->orderBy('ordre')->paginate(10);
@endphp
<x-layouts.public :title="__('app.account_types.title')">

    <x-page-hero image="/images/trading/trading-05.jpg" :eyebrow="__('app.account_types.hero_eyebrow')">
        <h1 class="font-display text-3xl sm:text-5xl font-bold text-texte-principal">{{ __('app.account_types.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.account_types.subtitle') }}</p>
    </x-page-hero>

    {{-- 1. Intro split image/text (pattern A) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <x-reveal direction="left">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.account_types.intro_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.account_types.intro_title') }}</h2>
                <p class="mt-4 text-texte-secondaire leading-relaxed">{{ __('app.account_types.intro_body') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-6 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.account_types.cta') }}
                </a>
            </x-reveal>
            <x-reveal direction="right" :delay="100">
                <div class="relative">
                    <x-photo-card src="/images/trading/trading-10.jpg" :alt="__('app.account_types.title')" :rotate="-2" />
                    <div class="relative">
                        <x-floating-badge position="bottom-right">
                            <p class="text-xs text-texte-secondaire">{{ __('app.account_types.table_swap') }}</p>
                            <p class="font-display text-lg font-bold text-couleur-principale">{{ __('app.common.yes') }}</p>
                        </x-floating-badge>
                    </div>
                </div>
            </x-reveal>
        </div>
    </section>

    {{-- 2. Comparatif (pattern E - table) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <x-reveal>
            <div class="text-center max-w-2xl mx-auto mb-8">
                <p class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-couleur-principale mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-couleur-principale animate-pulse"></span>
                    {{ __('app.account_types.table_section_eyebrow') }}
                </p>
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.account_types.table_section_title') }}</h2>
            </div>
        </x-reveal>

        {{-- Tableau comparatif (desktop) --}}
        <x-reveal class="hidden md:block">
            <x-data-table :headers="[
                __('app.account_types.table_name'),
                __('app.account_types.table_deposit'),
                __('app.account_types.table_spread'),
                __('app.account_types.table_leverage'),
                __('app.account_types.table_swap'),
                '',
            ]">
                @forelse($accountTypes as $type)
                    <tr>
                        <td class="px-4 py-3 font-semibold text-texte-principal">{{ $type->nom }}</td>
                        <td class="px-4 py-3 tabular-nums">${{ number_format($type->depot_min, 0, ',', ' ') }}</td>
                        <td class="px-4 py-3 tabular-nums">{{ rtrim(rtrim(number_format($type->spread_min, 5, '.', ''), '0'), '.') }} pip</td>
                        <td class="px-4 py-3 tabular-nums">1:{{ $type->levier_max }}</td>
                        <td class="px-4 py-3">
                            @if($type->swap_free)
                                <x-status-badge status="valide" :map="['valide' => ['label' => __('app.common.yes'), 'class' => 'bg-succes/10 text-succes']]" />
                            @else
                                <x-status-badge status="cloture" :map="['cloture' => ['label' => __('app.common.no'), 'class' => 'bg-texte-secondaire/10 text-texte-secondaire']]" />
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ url('/inscription') }}" class="text-couleur-principale hover:underline text-sm font-medium">{{ __('app.account_types.cta') }}</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-texte-secondaire">{{ __('app.common.no_results') }}</td></tr>
                @endforelse
                <x-slot:pagination>{{ $accountTypes->links() }}</x-slot:pagination>
            </x-data-table>
        </x-reveal>

        {{-- Cards (mobile + vue alternative) --}}
        <div class="md:hidden space-y-4">
            @foreach($accountTypes as $i => $type)
                <x-reveal :delay="$i * 60">
                    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-display font-semibold text-texte-principal">{{ $type->nom }}</p>
                            @if($type->swap_free)
                                <x-status-badge status="valide" :map="['valide' => ['label' => __('app.common.yes'), 'class' => 'bg-succes/10 text-succes']]" />
                            @endif
                        </div>
                        @if($type->description)
                            <p class="text-sm text-texte-secondaire mb-3">{{ $type->description }}</p>
                        @endif
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <div>
                                <p class="text-texte-secondaire text-xs">{{ __('app.account_types.table_deposit') }}</p>
                                <p class="tabular-nums text-texte-principal">${{ number_format($type->depot_min, 0, ',', ' ') }}</p>
                            </div>
                            <div>
                                <p class="text-texte-secondaire text-xs">{{ __('app.account_types.table_spread') }}</p>
                                <p class="tabular-nums text-texte-principal">{{ rtrim(rtrim(number_format($type->spread_min, 5, '.', ''), '0'), '.') }}</p>
                            </div>
                            <div>
                                <p class="text-texte-secondaire text-xs">{{ __('app.account_types.table_leverage') }}</p>
                                <p class="tabular-nums text-texte-principal">1:{{ $type->levier_max }}</p>
                            </div>
                        </div>
                        <a href="{{ url('/inscription') }}" class="mt-4 inline-flex items-center justify-center w-full rounded-sm bg-couleur-principale text-fond-principal font-semibold px-4 py-2 text-sm hover:brightness-110 transition">
                            {{ __('app.account_types.cta') }}
                        </a>
                    </div>
                </x-reveal>
            @endforeach
        </div>
    </section>

    {{-- 3. CTA banner (pattern D) --}}
    <section class="relative overflow-hidden py-20 mt-4">
        <div class="absolute inset-0 -z-10">
            <img src="/images/trading/trading-17.jpg" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-b from-fond-principal via-fond-principal/85 to-fond-principal"></div>
        </div>
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <x-reveal direction="scale">
                <h2 class="font-display text-2xl sm:text-3xl font-bold text-texte-principal">{{ __('app.account_types.cta_banner_title') }}</h2>
                <p class="mt-3 text-texte-secondaire max-w-xl mx-auto">{{ __('app.account_types.cta_banner_body') }}</p>
                <a href="{{ url('/inscription') }}" class="mt-8 inline-flex items-center rounded-sm bg-couleur-principale text-fond-principal font-semibold px-6 py-3 hover:brightness-110 transition">
                    {{ __('app.account_types.cta') }}
                </a>
            </x-reveal>
        </div>
    </section>

    <x-floating-button href="{{ url('/inscription') }}" aria-label="{{ __('app.floating.open_account') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
    </x-floating-button>

</x-layouts.public>
