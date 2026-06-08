<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à vos transactions.');
        }

        $transactions = $user->transactions()->paginate(15);
        return view('transactions.index', ['transactions' => $transactions]);
    }

    public function recent(Request $request)
    {
        $transactions = $request->user()->transactions()->latest()->limit(10)->get();
        return view('transactions.recent', ['transactions' => $transactions]);
    }

    public function showDeposit()
    {
        return view('transactions.deposit');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // TODO: Implement deposit logic
        return back()->with('success', 'Dépôt en cours de traitement.');
    }

    public function showWithdraw()
    {
        return view('transactions.withdraw');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method_id' => 'required|exists:payment_methods,id',
        ]);

        // TODO: Implement withdrawal logic
        return back()->with('success', 'Retrait en cours de traitement.');
    }

    public function exportCsv(Request $request)
    {
        // TODO: Implement CSV export
        return back();
    }
}
