@php
    $statusMap = [
        'en_attente' => __('app.client.kyc.status_pending'),
        'valide' => __('app.client.kyc.status_valid'),
        'refuse' => __('app.client.kyc.status_refused'),
    ];
@endphp
<div class="space-y-6 max-w-3xl">
    <div>
        <h1 class="font-display text-2xl font-bold text-texte-principal">{{ __('app.client.kyc.title') }}</h1>
        <p class="mt-1 text-sm text-texte-secondaire">{{ __('app.client.kyc.intro') }}</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Piece d'identite --}}
        <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-texte-principal">{{ __('app.client.kyc.id_document') }}</p>
                @if($latestIdentite)
                    <x-status-badge :status="$latestIdentite->statut" :map="['en_attente' => ['label' => $statusMap['en_attente'], 'class' => 'bg-avertissement/10 text-avertissement'], 'valide' => ['label' => $statusMap['valide'], 'class' => 'bg-succes/10 text-succes'], 'refuse' => ['label' => $statusMap['refuse'], 'class' => 'bg-danger/10 text-danger']]" />
                @endif
            </div>
            <form wire:submit="uploadIdentite" class="space-y-3">
                <input type="file" wire:model="piece_identite" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-texte-secondaire file:mr-3 file:rounded-sm file:border-0 file:bg-fond-surface file:text-texte-principal file:px-3 file:py-2 file:text-xs">
                @error('piece_identite') <p class="text-xs text-danger">{{ $message }}</p> @enderror
                <button type="submit" wire:loading.attr="disabled" wire:target="uploadIdentite,piece_identite"
                    class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold py-2 hover:brightness-110 transition disabled:opacity-60">
                    {{ __('app.client.kyc.upload') }}
                </button>
            </form>
            @if($latestIdentite?->commentaire_admin)
                <p class="mt-3 text-xs text-texte-secondaire"><span class="font-medium">{{ __('app.client.kyc.admin_comment') }}:</span> {{ $latestIdentite->commentaire_admin }}</p>
            @endif
        </div>

        {{-- Justificatif de domicile --}}
        <div class="rounded-sm bg-fond-card border border-bordure-subtile p-5">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-medium text-texte-principal">{{ __('app.client.kyc.proof_address') }}</p>
                @if($latestDomicile)
                    <x-status-badge :status="$latestDomicile->statut" :map="['en_attente' => ['label' => $statusMap['en_attente'], 'class' => 'bg-avertissement/10 text-avertissement'], 'valide' => ['label' => $statusMap['valide'], 'class' => 'bg-succes/10 text-succes'], 'refuse' => ['label' => $statusMap['refuse'], 'class' => 'bg-danger/10 text-danger']]" />
                @endif
            </div>
            <form wire:submit="uploadDomicile" class="space-y-3">
                <input type="file" wire:model="justificatif_domicile" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-texte-secondaire file:mr-3 file:rounded-sm file:border-0 file:bg-fond-surface file:text-texte-principal file:px-3 file:py-2 file:text-xs">
                @error('justificatif_domicile') <p class="text-xs text-danger">{{ $message }}</p> @enderror
                <button type="submit" wire:loading.attr="disabled" wire:target="uploadDomicile,justificatif_domicile"
                    class="w-full inline-flex items-center justify-center rounded-sm bg-couleur-principale text-fond-principal text-sm font-semibold py-2 hover:brightness-110 transition disabled:opacity-60">
                    {{ __('app.client.kyc.upload') }}
                </button>
            </form>
            @if($latestDomicile?->commentaire_admin)
                <p class="mt-3 text-xs text-texte-secondaire"><span class="font-medium">{{ __('app.client.kyc.admin_comment') }}:</span> {{ $latestDomicile->commentaire_admin }}</p>
            @endif
        </div>
    </div>

    @if($documents->isEmpty())
        <p class="text-sm text-texte-secondaire">{{ __('app.client.kyc.no_documents') }}</p>
    @endif
</div>
