<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return view('profile.show', ['user' => $request->user()]);
    }

    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'phone'      => 'nullable|string|max:50',
            'country'    => 'nullable|string|max:100',
            'date_of_birth' => 'nullable|date',
        ]);

        $user = $request->user();
        $user->update([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            ...$data
        ]);

        return back()->with('success', 'Profil mis à jour avec succès.');
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate(['avatar' => 'required|image|max:5120']);
        
        $user = $request->user();
        if ($request->file('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $path]);
        }

        return back()->with('success', 'Avatar mis à jour.');
    }

    public function kycStatus(Request $request)
    {
        $user = $request->user();
        
        // Charger les documents KYC
        $kycDocuments = \App\Models\KycDocument::where('user_id', $user->id)->get();
        
        // Déterminer l'état selon le statut KYC
        $isVerified = $user->kyc_status === 'verified';
        $isPending = $user->kyc_status === 'pending';
        $isRejected = $user->kyc_status === 'rejected';
        $showForm = $user->kyc_status === null || $user->kyc_status === 'unverified';
        
        return view('profile.kyc-verify', [
            'user' => $user,
            'kycDocuments' => $kycDocuments,
            'isPending' => $isPending,
            'isVerified' => $isVerified,
            'isRejected' => $isRejected,
            'showForm' => $showForm,
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if (!Hash::check($request->password, $request->user()->password)) {
            return back()->withErrors(['password' => 'Mot de passe incorrect.']);
        }

        $request->user()->delete();
        return redirect('/')->with('success', 'Compte supprimé.');
    }

    public function security(Request $request)
    {
        return view('profile.security', ['user' => $request->user()]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Mot de passe actuel incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Mot de passe changé avec succès.');
    }
}
