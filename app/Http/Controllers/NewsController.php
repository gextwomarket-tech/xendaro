<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Actualites & analyses de marche (id 13 "market-news") et detail (id 14 "news-detail").
 */
class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $categoryId = $request->query('categorie');

        $articles = NewsArticle::with(['category', 'instrument'])
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->latest('publie_le')
            ->paginate(9)
            ->withQueryString();

        $categories = Category::ofType('news')->pluck('nom_fr', 'id');

        return view('vitrine.market-news', [
            'articles' => $articles,
            'categories' => $categories,
            'categoryId' => $categoryId,
        ]);
    }

    public function show(NewsArticle $article): View
    {
        $related = NewsArticle::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->latest('publie_le')
            ->limit(3)
            ->get();

        return view('vitrine.news-detail', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
