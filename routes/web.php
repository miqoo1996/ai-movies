<?php

use App\Models\Show;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $sliderShows = \App\Models\Show::orderBy('subscribers', 'desc')->take(9)->get();
    $top10Shows     = \App\Models\Show::orderBy('subscribers', 'desc')->take(10)->get();
    $recentlyAdded   = \App\Models\Show::orderBy('created_at', 'desc')->take(8)->get();
    $classicDramas   = \App\Models\Show::where('year', '<=', 2015)->orderBy('subscribers', 'desc')->take(8)->get();
    $diziNewcomers   = \App\Models\Show::where('year', '>', 2015)->orderBy('subscribers', 'desc')->take(8)->get();
    $periodDramas    = \App\Models\Show::whereHas('genres', fn($q) => $q->where('name', 'Period Drama'))->orderBy('subscribers', 'desc')->take(8)->get();
    $netflixShows    = \App\Models\Show::where('network', 'Netflix')->orderBy('subscribers', 'desc')->take(8)->get();
    $loveShows       = \App\Models\Show::whereHas('genres', fn($q) => $q->whereIn('name', ['Romance', 'Romantic Comedy']))->orderBy('subscribers', 'desc')->take(8)->get();
    $turkishRemakes  = \App\Models\Show::where(fn($q) => $q->where('synopsis', 'like', '%remake%')->orWhere('synopsis', 'like', '%adapted%')->orWhere('synopsis', 'like', '%adaptation%')->orWhere('synopsis', 'like', '%based on%'))->orderBy('subscribers', 'desc')->take(8)->get();
    $impossibleLove  = \App\Models\Show::where(fn($q) => $q->where('synopsis', 'like', '%forbidden%')->orWhere('synopsis', 'like', '%impossible love%')->orWhere('synopsis', 'like', '%star-crossed%')->orWhere('synopsis', 'like', '%secret love%')->orWhere('synopsis', 'like', '%torn between%')->orWhere('synopsis', 'like', '%unrequited%')->orWhere('synopsis', 'like', '%against all odds%'))->orderBy('subscribers', 'desc')->take(8)->get();
    $dailyDramas     = \App\Models\Show::where('runtime', '<=', 45)->orderBy('subscribers', 'desc')->take(8)->get();
    $enemiesToLovers = \App\Models\Show::where(fn($q) => $q->where('synopsis', 'like', '%enemies%')->orWhere('synopsis', 'like', '%rivals%')->orWhere('synopsis', 'like', '%nemesis%')->orWhere('synopsis', 'like', '%hatred%')->orWhere('synopsis', 'like', '%hate turns%'))->orderBy('subscribers', 'desc')->take(8)->get();
    $familyTree      = \App\Models\Show::whereHas('genres', fn($q) => $q->where('name', 'Family'))->orderBy('subscribers', 'desc')->take(8)->get();
    $bingeWorthy     = \App\Models\Show::where('status', 'Ended')->orderBy('subscribers', 'desc')->take(8)->get();
    $oneWeekend      = \App\Models\Show::where('runtime', '<', 60)->where('status', 'Ended')->orderBy('subscribers', 'desc')->take(8)->get();
    $goneTooSoon     = \App\Models\Show::where('status', 'Cancelled')->orderBy('subscribers', 'desc')->take(8)->get();
    return view('home', compact('sliderShows', 'top10Shows', 'recentlyAdded', 'classicDramas', 'diziNewcomers', 'periodDramas', 'netflixShows', 'loveShows', 'turkishRemakes', 'impossibleLove', 'dailyDramas', 'enemiesToLovers', 'familyTree', 'bingeWorthy', 'oneWeekend', 'goneTooSoon'));
});

Route::get('/shows', function () {
    $q       = request('q', '');
    $status  = request('status', '');
    $genre   = request('genre', '');
    $network = request('network', '');
    $sort    = request('sort', 'subscribers');
    $year    = request('year', '');

    $query = \App\Models\Show::with('genres')->withCount('episodes');

    if ($q)       $query->where(fn($b) => $b->where('title', 'like', "%$q%")->orWhere('original_title', 'like', "%$q%"));
    if ($status)  $query->where('status', $status);
    if ($network) $query->where('network', $network);
    if ($genre)   $query->whereHas('genres', fn($b) => $b->where('slug', $genre));
    if ($year)    $query->where('year', $year);

    $query->orderBy(match($sort) {
        'rating'      => 'rating',
        'year_desc'   => 'year',
        'year_asc'    => 'year',
        'newest'      => 'created_at',
        'title'       => 'title',
        default       => 'subscribers',
    }, in_array($sort, ['year_asc', 'title']) ? 'asc' : 'desc');

    $shows    = $query->paginate(40)->withQueryString();
    $genres   = \App\Models\Genre::withCount('shows')->orderByDesc('shows_count')->get();
    $networks = \App\Models\Show::distinct()->orderBy('network')->pluck('network')->filter()->values();
    $statuses = ['Running' => 'Airing Now', 'Returning Series' => 'Returning', 'Ended' => 'Ended', 'Cancelled' => 'Cancelled', 'Hiatus' => 'Hiatus'];

    return view('shows.index', compact('shows', 'genres', 'networks', 'statuses', 'q', 'status', 'genre', 'network', 'sort', 'year'));
});

Route::get('/calendar', function () {
    $today      = \Carbon\Carbon::today();
    $dailyOnly  = request()->boolean('daily');

    // If no week param, default to most recent week that has episode data
    if (request()->has('week')) {
        $weekStart = \Carbon\Carbon::parse(request('week'))->startOfWeek(\Carbon\Carbon::MONDAY);
    } else {
        $latestEpDate = \App\Models\Episode::whereNotNull('airs_on')->max('airs_on');
        $weekStart = $latestEpDate
            ? \Carbon\Carbon::parse($latestEpDate)->startOfWeek(\Carbon\Carbon::MONDAY)
            : $today->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
    }

    $weekEnd = $weekStart->copy()->endOfWeek(\Carbon\Carbon::SUNDAY);

    $episodesQuery = \App\Models\Episode::with(['show' => fn($q) => $q->select('id','title','slug','poster','poster_local','network','runtime')])
        ->whereNotNull('airs_on')
        ->whereBetween('airs_on', [$weekStart->toDateString(), $weekEnd->toDateString()])
        ->orderBy('airs_on')
        ->orderBy('episode_number');

    if ($dailyOnly) {
        $episodesQuery->whereHas('show', fn($q) => $q->where('runtime', '<=', 45));
    }

    $episodes = $episodesQuery->get()->groupBy(fn($e) => \Carbon\Carbon::parse($e->airs_on)->format('Y-m-d'));

    $prevWeek = $weekStart->copy()->subWeek()->toDateString();
    $nextWeek = $weekStart->copy()->addWeek()->toDateString();

    // Build the 7-day array
    $days = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

    return view('calendar', compact('days', 'episodes', 'today', 'weekStart', 'prevWeek', 'nextWeek', 'dailyOnly'));
});

Route::get('/faq', fn() => view('faq'));
Route::get('/terms', fn() => view('terms'));
Route::get('/privacy', fn() => view('privacy'));
Route::get('/contact', fn() => view('contact'));

Route::get('/shows/{slug}', function (string $slug) {
    $show = Show::with(['genres', 'images', 'episodes' => fn($q) => $q->orderBy('season_number')->orderBy('episode_number')])
                ->where('slug', $slug)
                ->firstOrFail();

    $seasons        = $show->episodes->groupBy('season_number')->sortKeys();
    $latestEpisodes = $show->episodes->sortByDesc('airs_on')->take(3)->values();

    $genreIds    = $show->genres->pluck('id');
    $relatedShows = Show::with('genres')
        ->whereHas('genres', fn($q) => $q->whereIn('genres.id', $genreIds))
        ->where('id', '!=', $show->id)
        ->orderBy('subscribers', 'desc')
        ->take(18)
        ->get();

    return view('shows.show', compact('show', 'seasons', 'latestEpisodes', 'relatedShows'));
});
