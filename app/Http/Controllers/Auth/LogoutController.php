<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Action de deconnexion (page id 43 "logout"). Uniquement declenchee en POST
 * apres confirmation dans la modale <x-modal name="logout-confirm"> (voir
 * <x-user-menu-dropdown> et la sidebar dashboard).
 */
class LogoutController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
