@php
    $transactions = $this->getTransactions();

    $typeColors = [
        'deposit'    => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        'withdraw'   => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'withdrawal' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'transfer'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    ];

    $statusColors = [
        'pending'   => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
        'approved'  => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
        'rejected'  => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'failed'    => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
        'processing'=> 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    ];

    $typeLabels = [
        'deposit'    => 'Dépôt',
        'withdraw'   => 'Retrait',
        'withdrawal' => 'Retrait',
        'transfer'   => 'Transfert',
    ];

    $statusLabels = [
        'pending'    => 'En attente',
        'approved'   => 'Approuvé',
        'completed'  => 'Complété',
        'rejected'   => 'Rejeté',
        'failed'     => 'Échoué',
        'processing' => 'En cours',
    ];
@endphp

<x-filament::section>
    <x-slot name="heading">Transactions récentes</x-slot>

    @if($transactions->isEmpty())
        <div class="flex flex-col items-center justify-center py-10 text-gray-400 dark:text-gray-500">
            @svg('heroicon-o-inbox', 'w-10 h-10 mb-2')
            <p class="text-sm">Aucune transaction pour le moment</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
                        <th class="pb-3 pr-4 font-medium">Utilisateur</th>
                        <th class="pb-3 pr-4 font-medium">Type</th>
                        <th class="pb-3 pr-4 font-medium">Montant</th>
                        <th class="pb-3 pr-4 font-medium">Méthode</th>
                        <th class="pb-3 pr-4 font-medium">Statut</th>
                        <th class="pb-3 pr-4 font-medium">Référence</th>
                        <th class="pb-3 font-medium">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($transactions as $tx)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                            <td class="py-3 pr-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 uppercase flex-shrink-0">
                                        {{ substr($tx->user?->first_name ?? $tx->user?->name ?? '?', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-xs leading-tight">
                                            {{ $tx->user?->first_name ?? '' }} {{ $tx->user?->last_name ?? $tx->user?->name ?? 'Utilisateur supprimé' }}
                                        </p>
                                        <p class="text-gray-400 dark:text-gray-500 text-xs">{{ $tx->user?->email ?? '' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 pr-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $typeColors[$tx->type] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $typeLabels[$tx->type] ?? ucfirst($tx->type) }}
                                </span>
                            </td>
                            <td class="py-3 pr-4 font-semibold {{ in_array($tx->type, ['deposit']) ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ in_array($tx->type, ['deposit']) ? '+' : '-' }}${{ number_format($tx->amount, 2) }}
                            </td>
                            <td class="py-3 pr-4 text-gray-600 dark:text-gray-300 text-xs capitalize">
                                {{ str_replace('_', ' ', $tx->method ?? '—') }}
                            </td>
                            <td class="py-3 pr-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$tx->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ $statusLabels[$tx->status] ?? ucfirst($tx->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-4 text-gray-400 dark:text-gray-500 text-xs font-mono">
                                {{ $tx->reference ? '#' . substr($tx->reference, 0, 10) . '...' : '—' }}
                            </td>
                            <td class="py-3 text-gray-500 dark:text-gray-400 text-xs whitespace-nowrap">
                                {{ $tx->created_at?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 text-right">
            <a href="{{ route('filament.admin.resources.transactions.index') }}"
               class="text-xs text-primary-600 dark:text-primary-400 hover:underline font-medium">
                Voir toutes les transactions →
            </a>
        </div>
    @endif
</x-filament::section>
