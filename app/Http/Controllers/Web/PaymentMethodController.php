<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à vos méthodes de paiement.');
        }

        $methods = $user->paymentMethods()->get();
        return view('payment-methods.index', ['methods' => $methods]);
    }

    public function create()
    {
        return view('payment-methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:card,bank,wallet',
            'name' => 'required|string',
            'details' => 'required|array',
        ]);

        // TODO: Create payment method
        return back()->with('success', 'Méthode de paiement ajoutée.');
    }

    public function destroy(Request $request, $id)
    {
        $request->user()->paymentMethods()->find($id)?->delete();
        return back()->with('success', 'Méthode de paiement supprimée.');
    }
}
