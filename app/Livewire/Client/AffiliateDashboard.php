<?php

namespace App\Livewire\Client;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Page id 42 "affiliate-dashboard" - lien de parrainage, filleuls, commissions.
 */
#[Layout('components.layouts.dashboard')]
class AffiliateDashboard extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();

        return view('livewire.client.affiliate-dashboard', [
            'referralUrl' => url('/inscription?ref='.$user->referral_code),
            'totalReferrals' => User::where('parrain_id', $user->id)->count(),
            'totalCommissions' => $user->affiliateCommissionsGagnees()->where('statut', 'valide')->sum('montant'),
            'referrals' => User::where('parrain_id', $user->id)->latest()->paginate(15),
        ]);
    }
}
