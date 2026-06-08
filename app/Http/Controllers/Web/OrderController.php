<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Defense in depth: Verify KYC status (middleware already does this, but safety check)
        if ($user->kyc_status !== 'verified') {
            return redirect()->route('kyc.show')
                ->with('info', 'Veuillez compléter votre vérification KYC pour accéder à vos commandes.');
        }

        $orders = $user->orders()->paginate(15);
        return view('orders.index', ['orders' => $orders]);
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'symbol' => 'required|string',
            'type' => 'required|in:buy,sell',
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
        ]);

        // TODO: Create order
        return back()->with('success', 'Commande créée.');
    }

    public function edit(Request $request, $id)
    {
        $order = $request->user()->orders()->findOrFail($id);
        return view('orders.edit', ['order' => $order]);
    }

    public function update(Request $request, $id)
    {
        $order = $request->user()->orders()->findOrFail($id);
        
        $validated = $request->validate([
            'quantity' => 'required|numeric|min:0.01',
            'price' => 'required|numeric|min:0.01',
        ]);

        $order->update($validated);
        return back()->with('success', 'Commande mise à jour.');
    }

    public function destroy(Request $request, $id)
    {
        $order = $request->user()->orders()->findOrFail($id);
        $order->delete();
        return back()->with('success', 'Commande supprimée.');
    }
}
