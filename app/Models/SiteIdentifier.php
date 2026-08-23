<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteIdentifier extends Model
{
    protected $fillable = [
        'nom_plateforme',
        'slogan',
        'langue_par_defaut',
        'couleur_principale',
        'couleur_secondaire',
        'about_us',
        'path_light_logo',
        'path_dark_logo',
        'path_favicon_png',
        'phone_contact_1',
        'phone_contact_2',
        'email_pro_1',
        'email_pro_2',
        'location_adresse',
        'cvg',
        'policies',
        'cookies',
        'nos_services',
        'contact',
        'reseaux_sociaux',
    ];

    protected function casts(): array
    {
        return [
            'reseaux_sociaux' => 'array',
        ];
    }
}
