<?php

namespace App\Http\Controllers\Web;

use App\Models\User;
use App\Models\KycDocument;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KycController extends Controller
{
    /**
     * Show the KYC verification form or pending status
     */
    public function show(Request $request)
    {
        $user = $request->user()->fresh(); // Rafraîchir les données utilisateur
        
        // Check if KYC documents have been submitted
        $kycDocuments = KycDocument::where('user_id', $user->id)->get();
        $hasSubmittedDocuments = $kycDocuments->count() > 0;
        
        // Règle : afficher le formulaire si aucun document n'a encore été soumis,
        // quel que soit le kyc_status stocké (évite le faux "en attente").
        $showForm    = !$hasSubmittedDocuments;
        $isPending   = $hasSubmittedDocuments && $user->kyc_status === 'pending';
        $isVerified  = $user->kyc_status === 'verified';
        $isRejected  = $hasSubmittedDocuments && $user->kyc_status === 'rejected';
        
        return view('profile.kyc-verify', [
            'user' => $user,
            'kycDocuments' => $kycDocuments,
            'isPending' => $isPending,
            'isVerified' => $isVerified,
            'isRejected' => $isRejected,
            'showForm' => $showForm,
        ]);
    }

    /**
     * Store KYC documents
     */
    public function store(Request $request)
    {
        $user = $request->user();

        // Validate inputs
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'country' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'identity_document' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'selfie_with_identity' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        try {
            // Update user personal information
            $user->update([
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'date_of_birth' => $validated['date_of_birth'],
                'country' => $validated['country'],
                'city' => $validated['city'],
                'address' => $validated['address'],
            ]);

            // Store identity document
            if ($request->hasFile('identity_document')) {
                $file = $request->file('identity_document');
                $path = Storage::disk('public')->put('kyc-documents', $file);
                
                KycDocument::create([
                    'user_id' => $user->id,
                    'type' => 'identity',
                    'file_url' => $path,
                    'status' => 'pending',
                ]);
            }

            // Store selfie document
            if ($request->hasFile('selfie_with_identity')) {
                $file = $request->file('selfie_with_identity');
                $path = Storage::disk('public')->put('kyc-documents', $file);
                
                KycDocument::create([
                    'user_id' => $user->id,
                    'type' => 'selfie',
                    'file_url' => $path,
                    'status' => 'pending',
                ]);
            }

            // Mettre à jour le statut uniquement après enregistrement des docs
            $user->update(['kyc_status' => 'pending']);

            return redirect()->route('kyc.show')
                ->with('kyc_submitted', true)
                ->with('success', 'Documents soumis avec succès ! Notre équipe les examinera sous 1 à 24 heures.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la soumission. Veuillez réessayer.')
                ->withInput();
        }
    }
}
