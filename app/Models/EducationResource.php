<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EducationResource extends Model
{
    protected $fillable = [
        'titre_fr',
        'titre_en',
        'slug',
        'contenu_fr',
        'contenu_en',
        'category_id',
        'image',
        'type',
        'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'est_actif' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function titre(): string
    {
        return app()->getLocale() === 'en' && $this->titre_en ? $this->titre_en : $this->titre_fr;
    }

    public function contenu(): string
    {
        return app()->getLocale() === 'en' && $this->contenu_en ? $this->contenu_en : $this->contenu_fr;
    }
}
