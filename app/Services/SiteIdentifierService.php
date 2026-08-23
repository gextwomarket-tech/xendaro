<?php

namespace App\Services;

use App\Models\SiteIdentifier;
use Illuminate\Support\Facades\Cache;

/**
 * Acces centralise et mis en cache au singleton site_identifier,
 * consomme par toutes les pages vitrines + panneau de branding auth (voir xendaro-fox-plan.json).
 */
class SiteIdentifierService
{
    private const CACHE_KEY = 'site_identifier.current';

    public static function current(): SiteIdentifier
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            return SiteIdentifier::first() ?? SiteIdentifier::create([]);
        });
    }

    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
