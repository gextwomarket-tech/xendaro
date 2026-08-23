<?php

namespace App\Providers;

use App\Services\SiteIdentifierService;
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
    }
}
