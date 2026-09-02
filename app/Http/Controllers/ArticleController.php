<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()
            ->orderByDesc('published_at')
            ->orderByDesc('id')
            ->paginate(12);

        $seoPage = Page::where('slug', 'articles')->first();

        return view('articles.index', compact('articles', 'seoPage'));
    }

    public function show(Article $article)
    {
        abort_unless($article->is_published, 404);

        $related = Article::published()
            ->whereKeyNot($article->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }
}
