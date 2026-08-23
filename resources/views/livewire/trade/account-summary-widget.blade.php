<div class="px-3 py-2.5 border-b border-bordure-subtile shrink-0 grid grid-cols-2 gap-x-3 gap-y-1.5 text-xs" wire:poll.3s>
    <div class="flex items-center justify-between col-span-2">
        <span class="text-texte-secondaire">{{ __('app.trade.account_summary.balance') }}</span>
        <span class="tabular-nums font-medium text-texte-principal">{{ number_format($solde, 2) }} $</span>
    </div>
    <div class="flex items-center justify-between col-span-2">
        <span class="text-texte-secondaire">{{ __('app.trade.account_summary.equity') }}</span>
        <span class="tabular-nums font-medium {{ $pnlFlottant >= 0 ? 'text-succes' : 'text-danger' }}">{{ number_format($equite, 2) }} $</span>
    </div>
    <div class="flex items-center justify-between col-span-2">
        <span class="text-texte-secondaire">{{ __('app.trade.account_summary.margin_used') }}</span>
        <span class="tabular-nums font-medium text-texte-principal">{{ number_format($margeUtilisee, 2) }} $</span>
    </div>
    <div class="flex items-center justify-between col-span-2">
        <span class="text-texte-secondaire">{{ __('app.trade.account_summary.margin_free') }}</span>
        <span class="tabular-nums font-medium text-texte-principal">{{ number_format($margeLibre, 2) }} $</span>
    </div>
    <div class="flex items-center justify-between col-span-2">
        <span class="text-texte-secondaire">{{ __('app.trade.account_summary.margin_level') }}</span>
        <span class="tabular-nums font-medium text-texte-principal">{{ $niveauMarge !== null ? number_format($niveauMarge, 2).'%' : '—' }}</span>
    </div>
</div>
