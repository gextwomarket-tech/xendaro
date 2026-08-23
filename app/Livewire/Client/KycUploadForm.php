<?php

namespace App\Livewire\Client;

use App\Models\KycDocument;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

/**
 * Page id 38 "kyc-verification" - televersement piece d'identite + justificatif de domicile.
 * Stockage sur disk prive 'local' (storage/app/private/kyc), non expose publiquement.
 */
#[Layout('components.layouts.dashboard')]
class KycUploadForm extends Component
{
    use WithFileUploads;

    public $piece_identite;

    public $justificatif_domicile;

    protected function rules(): array
    {
        return [
            'piece_identite' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'justificatif_domicile' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function uploadIdentite(): void
    {
        $this->validate(['piece_identite' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']]);
        $this->storeDocument('piece_identite', $this->piece_identite);
        $this->reset('piece_identite');
    }

    public function uploadDomicile(): void
    {
        $this->validate(['justificatif_domicile' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120']]);
        $this->storeDocument('justificatif_domicile', $this->justificatif_domicile);
        $this->reset('justificatif_domicile');
    }

    private function storeDocument(string $type, $file): void
    {
        $path = $file->store('kyc/'.Auth::id(), 'local');

        KycDocument::create([
            'user_id' => Auth::id(),
            'type_document' => $type,
            'fichier_path' => $path,
            'statut' => 'en_attente',
        ]);

        $this->dispatch('toast', type: 'success', message: __('app.client.kyc.upload_success'));
    }

    public function render()
    {
        $documents = KycDocument::where('user_id', Auth::id())->latest()->get();

        return view('livewire.client.kyc-upload-form', [
            'documents' => $documents,
            'latestIdentite' => $documents->firstWhere('type_document', 'piece_identite'),
            'latestDomicile' => $documents->firstWhere('type_document', 'justificatif_domicile'),
        ]);
    }
}
