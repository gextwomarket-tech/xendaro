<div x-data="{
        copy() {
            navigator.clipboard.writeText('{{ $referralUrl }}');
            $dispatch('toast', { type: 'success', message: '{{ __('app.client.affiliate.link_copied') }}' });
        }
    }"
    class="space-y-6"
>
    <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.affiliate.title') }}</h1>

    <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
        <p class="text-sm font-medium text-texte-principal mb-2">{{ __('app.client.affiliate.your_link') }}</p>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
            <input type="text" readonly value="{{ $referralUrl }}" class="flex-1 rounded-sm bg-fond-surface border border-bordure-subtile px-3 py-2.5 text-sm text-texte-secondaire">
            <button type="button" x-on:click="copy()" class="inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2.5 hover:brightness-110 transition shrink-0">
                {{ __('app.client.affiliate.copy_link') }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-stat-card :label="__('app.client.affiliate.total_referrals')" :value="$totalReferrals" />
        <x-stat-card :label="__('app.client.affiliate.total_commissions')" :value="'$'.number_format($totalCommissions, 2)" />
    </div>

    <div>
        <p class="text-sm font-medium text-texte-principal mb-3">{{ __('app.client.affiliate.referrals_title') }}</p>
        <x-data-table :headers="[__('app.client.affiliate.referral_name'), __('app.client.affiliate.referral_date')]">
            @forelse($referrals as $referral)
                <tr>
                    <td class="px-4 py-3">{{ $referral->name }}</td>
                    <td class="px-4 py-3 text-texte-secondaire text-xs">{{ $referral->created_at->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr><td colspan="2" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.affiliate.no_referrals') }}</td></tr>
            @endforelse

            <x-slot:pagination>{{ $referrals->links() }}</x-slot:pagination>
        </x-data-table>
    </div>
</div>
