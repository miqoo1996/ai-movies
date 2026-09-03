<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Person;
use App\Models\Show;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        return response()
            ->view('sitemap', [
                'staticUrls' => [
                    ['loc' => url('/')],
                    ['loc' => route('shows.index')],
                    ['loc' => route('articles.index')],
                    ['loc' => route('best-series')],
                    ['loc' => route('calendar')],
                    ['loc' => route('faq')],
                    ['loc' => route('terms')],
                    ['loc' => route('privacy')],
                    ['loc' => route('contact')],
                ],
                'shows' => Show::query()->select(['slug', 'updated_at'])->get(),
                'people' => Person::query()->select(['id', 'updated_at'])->get(),
                'articles' => Article::published()->select(['slug', 'updated_at'])->get(),
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}