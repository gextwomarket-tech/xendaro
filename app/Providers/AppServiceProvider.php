<?php

namespace App\Providers;

use App\Services\SiteIdentifierService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Partage site_identifier avec le layout public + le layout auth (panneau de branding) + le layout dashboard (titre onglet).
        // + toutes les pages vitrine.* (wildcard) qui consomment $siteIdentifier directement dans leur contenu
        // (le partage sur le layout seul ne se propage pas au scope de la vue appelante, voir docummentations.md).
        View::composer(['components.layouts.public', 'components.layouts.auth', 'components.layouts.dashboard', 'vitrine.*'], function ($view) {
            $view->with('siteIdentifier', SiteIdentifierService::current());
        });

        // La notification ResetPassword de Laravel genere son URL via route('password.reset', ...)
        // par convention - mais nos routes d'auth sont nommees en francais (reset-password, voir
        // routes/auth.php). Sans cette surcharge: RouteNotFoundException "Route [password.reset]
        // not defined", email jamais envoye (echec silencieux cote UI, exception seulement dans les logs).
        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
            // createUrlUsing() remplace ENTIEREMENT la construction de l'URL par Laravel, y compris
            // l'ajout du parametre ?email= (normalement automatique) - a la charge du callback ici.
            return url(route('reset-password', ['token' => $token], false))
                .'?email='.urlencode($notifiable->getEmailForPasswordReset());
        });
    }
}
