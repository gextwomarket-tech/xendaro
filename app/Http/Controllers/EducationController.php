<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\EducationResource;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Academie de trading (id 11 "education") et detail d'un article/cours (id 12 "education-article").
 */
class EducationController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('search');
        $categoryId = $request->query('categorie');

        $resources = EducationResource::where('est_actif', true)
            ->when($search, fn ($q) => $q->where('titre_fr', 'like', "%{$search}%"))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $categories = Category::ofType('education')->pluck('nom_fr', 'id');

        return view('vitrine.education', [
            'resources' => $resources,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
        ]);
    }

    public function show(EducationResource $resource): View
    {
        $related = EducationResource::where('category_id', $resource->category_id)
            ->where('id', '!=', $resource->id)
            ->where('est_actif', true)
            ->limit(3)
            ->get();

        return view('vitrine.education-article', [
            'resource' => $resource,
            'related' => $related,
        ]);
    }
}
