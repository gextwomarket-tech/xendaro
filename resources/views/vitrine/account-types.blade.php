@php
    $accountTypes = \App\Models\AccountType::where('est_actif', true)->orderBy('ordre')->paginate(10);
@endphp
<x-layouts.public :title="__('app.account_types.title')">

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-10 text-center">
        <h1 class="font-display text-3xl sm:text-4xl font-bold text-texte-principal">{{ __('app.account_types.title') }}</h1>
        <p class="mt-4 text-lg text-texte-secondaire max-w-2xl mx-auto">{{ __('app.account_types.subtitle') }}</p>
    </section>

    {{-- Tableau comparatif (desktop) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 hidden md:block">
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
    </section>

    {{-- Cards (mobile + vue alternative) --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:hidden">
        <div class="space-y-4">
            @foreach($accountTypes as $type)
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
            @endforeach
        </div>
    </section>

    <x-floating-button href="{{ url('/inscription') }}" aria-label="{{ __('app.floating.open_account') }}">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
    </x-floating-button>

</x-layouts.public>
