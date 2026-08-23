<div class="flex flex-col h-full min-h-0">
    <p class="px-3 py-2 text-xs font-medium uppercase tracking-wide text-texte-secondaire shrink-0 border-b border-bordure-subtile">
        {{ __('app.trade.history_title') }}
    </p>

    <div class="flex-1 min-h-0 overflow-y-auto">
        @if($trades->isEmpty())
            <p class="p-4 text-sm text-texte-secondaire text-center">{{ __('app.trade.no_history') }}</p>
        @else
            <x-data-table :headers="[
                __('app.trade.watchlist_title'),
                __('app.common.buy') . '/' . __('app.common.sell'),
                __('app.trade.volume_label'),
                'P/L',
            ]" class="border-0 rounded-none">
                @foreach($trades as $trade)
                    <tr wire:key="history-row-{{ $trade->id }}" class="text-xs">
                        <td class="px-4 py-2 whitespace-nowrap">
                            <p class="font-medium text-texte-principal">{{ $trade->instrument->symbole_interne ?? '—' }}</p>
                            <p class="text-texte-secondaire">{{ $trade->cloture_le?->format('d/m H:i') }}</p>
                        </td>
                        <td class="px-4 py-2 whitespace-nowrap">
                            <span class="{{ $trade->sens === 'buy' ? 'text-succes' : 'text-danger' }} font-medium uppercase">
                                {{ $trade->sens === 'buy' ? __('app.common.buy') : __('app.common.sell') }}
                            </span>
                        </td>
                        <td class="px-4 py-2 tabular-nums whitespace-nowrap">{{ number_format((float) $trade->volume, 2) }}</td>
                        <td class="px-4 py-2 tabular-nums whitespace-nowrap {{ (float) $trade->profit_perte >= 0 ? 'text-succes' : 'text-danger' }}">
                            {{ (float) $trade->profit_perte >= 0 ? '+' : '' }}{{ number_format((float) $trade->profit_perte, 2) }} $
                        </td>
                    </tr>
                @endforeach

                <x-slot:pagination>{{ $trades->links() }}</x-slot:pagination>
            </x-data-table>
        @endif
    </div>
</div>
