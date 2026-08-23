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
        // Partage site_identifier avec le layout public + le layout auth (panneau de branding).
        View::composer(['components.layouts.public', 'components.layouts.auth'], function ($view) {
            $view->with('siteIdentifier', SiteIdentifierService::current());
        });
    }
}
