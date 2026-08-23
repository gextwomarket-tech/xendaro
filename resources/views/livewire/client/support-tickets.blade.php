<div class="space-y-6">
    <div class="flex items-center justify-between gap-4">
        <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.support.title') }}</h1>
    </div>

    <x-data-table :headers="[__('app.client.support.subject'), __('app.client.support.status'), __('app.client.support.created_at'), '']">
        @forelse($tickets as $ticket)
            <tr>
                <td class="px-4 py-3">{{ $ticket->sujet }}</td>
                <td class="px-4 py-3">
                    <x-status-badge :status="$ticket->statut" :map="[
                        'ouvert' => ['label' => __('app.client.support.status_ouvert'), 'class' => 'bg-avertissement/10 text-avertissement'],
                        'en_cours' => ['label' => __('app.client.support.status_en_cours'), 'class' => 'bg-info/10 text-info'],
                        'ferme' => ['label' => __('app.client.support.status_ferme'), 'class' => 'bg-succes/10 text-succes'],
                    ]" />
                </td>
                <td class="px-4 py-3 text-texte-secondaire text-xs">{{ $ticket->created_at->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ route('client.ticket-detail', $ticket) }}" class="text-xs text-couleur-principale hover:underline">{{ __('app.common.view') }}</a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4" class="px-4 py-6 text-center text-texte-secondaire text-sm">{{ __('app.client.support.no_tickets') }}</td></tr>
        @endforelse

        <x-slot:pagination>{{ $tickets->links() }}</x-slot:pagination>
    </x-data-table>

    <x-floating-button href="#" x-on:click.prevent="$dispatch('open-modal', { name: 'new-ticket' })">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    </x-floating-button>

    <x-modal name="new-ticket" max-width="md">
        <livewire:client.new-ticket-form />
    </x-modal>
</div>
