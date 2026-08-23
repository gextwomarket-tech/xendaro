<div class="space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.wallet.title') }}</h1>
        <div class="flex items-center gap-3">
            <button type="button" x-on:click="$dispatch('open-modal', { name: 'withdraw' })"
                class="inline-flex items-center gap-2 rounded-sm border border-bordure-subtile text-texte-principal text-sm font-medium px-4 py-2.5 hover:border-couleur-principale/50 transition">
                {{ __('app.client.wallet.withdraw') }}
            </button>
            <button type="button" x-on:click="$dispatch('open-modal', { name: 'deposit' })"
                class="inline-flex items-center gap-2 rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold px-4 py-2.5 hover:brightness-110 transition">
                {{ __('app.client.wallet.deposit') }}
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <x-stat-card :label="__('app.client.wallet.balance_real')" :value="'$'.number_format($wallet->solde_reel ?? 0, 2)" />
        <div class="relative">
            <x-stat-card :label="__('app.client.wallet.balance_demo')" :value="'$'.number_format($wallet->solde_demo ?? 0, 2)" />
            <button type="button" x-on:click="$dispatch('open-modal', { name: 'topup-demo' })"
                class="absolute top-4 right-4 inline-flex items-center gap-1.5 rounded-sm bg-couleur-secondaire/10 text-couleur-secondaire text-xs font-medium px-2.5 py-1.5 hover:bg-couleur-secondaire/20 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                {{ __('app.client.wallet.topup_demo_button') }}
            </button>
        </div>
    </div>

    <div>
        <p class="text-sm font-medium text-texte-principal mb-3">{{ __('app.client.wallet.history_title') }}</p>
        <x-data-table :headers="[__('app.client.wallet.reference'), __('app.client.wallet.type'), __('app.client.wallet.payment_method'), __('app.client.wallet.amount'), __('app.client.wallet.status'), __('app.client.wallet.date')]">
            @forelse($transactions as $tx)
                <tr>
                    <td class="px-4 py-3 text-xs text-texte-secondaire">{{ $tx->reference }}</td>
                    <td class="px-4 py-3">{{ $tx->type === 'depot' ? __('app.client.wallet.type_deposit') : __('app.client.wallet.type_withdraw') }}</td>
                    <td class="px-4 py-3">{{ $tx->paymentMethod->nom ?? '—' }}</td>
                    <td class="px-4 py-3 tabular-nums">${{ number_format($tx->montant, 2) }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$tx->statut" /></td>
                    <td class="px-4 py-3 text-texte-secondaire text-xs">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.wallet.no_transactions') }}</td></tr>
            @endforelse

            <x-slot:pagination>{{ $transactions->links() }}</x-slot:pagination>
        </x-data-table>
    </div>

    <x-modal name="deposit" max-width="md">
        <livewire:client.deposit-form />
    </x-modal>

    <x-modal name="withdraw" max-width="sm">
        <livewire:client.withdraw-form />
    </x-modal>

    <x-modal name="topup-demo" max-width="sm">
        <livewire:client.top-up-demo-form />
    </x-modal>
</div>
