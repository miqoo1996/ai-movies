<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use App\Models\Genre;
use App\Models\Page;
use App\Models\Show;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function home()
    {
        $sliderShows    = Show::orderBy('subscribers', 'desc')->take(9)->get();
        $sidebarShows      = Show::orderBy('subscribers', 'desc')->take(5)->get();
        $sidebarTopRated   = Show::where('status', 'Running')->orderBy('subscribers', 'desc')->take(5)->get();
        $top10Shows     = Show::orderBy('subscribers', 'desc')->take(10)->get();
        $recentlyAdded  = Show::orderBy('created_at', 'desc')->take(8)->get();
        $classicDramas  = Show::where('year', '<=', 2015)->orderBy('subscribers', 'desc')->take(8)->get();
        $diziNewcomers  = Show::where('year', '>', 2015)->orderBy('subscribers', 'desc')->take(8)->get();
        $periodDramas   = Show::whereHas('genres', fn($q) => $q->where('name', 'Period Drama'))->orderBy('subscribers', 'desc')->take(8)->get();
        $netflixShows   = Show::where('network', 'Netflix')->orderBy('subscribers', 'desc')->take(8)->get();
        $loveShows      = Show::whereHas('genres', fn($q) => $q->whereIn('name', ['Romance', 'Romantic Comedy']))->orderBy('subscribers', 'desc')->take(8)->get();
        $turkishRemakes = Show::where(fn($q) => $q->where('synopsis', 'like', '%remake%')->orWhere('synopsis', 'like', '%adapted%')->orWhere('synopsis', 'like', '%adaptation%')->orWhere('synopsis', 'like', '%based on%'))->orderBy('subscribers', 'desc')->take(8)->get();
        $impossibleLove = Show::where(fn($q) => $q->where('synopsis', 'like', '%forbidden%')->orWhere('synopsis', 'like', '%impossible love%')->orWhere('synopsis', 'like', '%star-crossed%')->orWhere('synopsis', 'like', '%secret love%')->orWhere('synopsis', 'like', '%torn between%')->orWhere('synopsis', 'like', '%unrequited%')->orWhere('synopsis', 'like', '%against all odds%'))->orderBy('subscribers', 'desc')->take(8)->get();
        $dailyDramas    = Show::where('runtime', '<=', 45)->orderBy('subscribers', 'desc')->take(8)->get();
        $enemiesToLovers = Show::where(fn($q) => $q->where('synopsis', 'like', '%enemies%')->orWhere('synopsis', 'like', '%rivals%')->orWhere('synopsis', 'like', '%nemesis%')->orWhere('synopsis', 'like', '%hatred%')->orWhere('synopsis', 'like', '%hate turns%'))->orderBy('subscribers', 'desc')->take(8)->get();
        $familyTree     = Show::whereHas('genres', fn($q) => $q->where('name', 'Family'))->orderBy('subscribers', 'desc')->take(8)->get();
        $bingeWorthy    = Show::where('status', 'Ended')->orderBy('subscribers', 'desc')->take(8)->get();
        $oneWeekend     = Show::where('runtime', '<', 60)->where('status', 'Ended')->orderBy('subscribers', 'desc')->take(8)->get();
        $goneTooSoon    = Show::where('status', 'Cancelled')->orderBy('subscribers', 'desc')->take(8)->get();

        $seoPage = Page::where('slug', 'home')->first();

        return view('home', compact(
            'sliderShows', 'top10Shows', 'recentlyAdded', 'classicDramas', 'diziNewcomers',
            'periodDramas', 'netflixShows', 'loveShows', 'turkishRemakes', 'impossibleLove',
            'dailyDramas', 'enemiesToLovers', 'familyTree', 'bingeWorthy', 'oneWeekend', 'goneTooSoon',
            'seoPage', 'sidebarShows', 'sidebarTopRated'
        ));
    }

    public function index(Request $request)
    {
        $q       = $request->input('q', '');
        $status  = $request->input('status', '');
        $genre   = $request->input('genre', '');
        $network = $request->input('network', '');
        $sort    = $request->input('sort', 'subscribers');
        $year    = $request->input('year', '');

        $query = Show::with('genres')->withCount('episodes');

        if ($q)       $query->where(fn($b) => $b->where('title', 'like', "%$q%")->orWhere('original_title', 'like', "%$q%"));
        if ($status)  $query->where('status', $status);
        if ($network) $query->where('network', $network);
        if ($genre)   $query->whereHas('genres', fn($b) => $b->where('slug', $genre));
        if ($year)    $query->where('year', $year);

        $query->orderBy(match($sort) {
            'rating'    => 'rating',
            'year_desc' => 'year',
            'year_asc'  => 'year',
            'newest'    => 'created_at',
            'title'     => 'title',
            default     => 'subscribers',
        }, in_array($sort, ['year_asc', 'title']) ? 'asc' : 'desc');

        $shows    = $query->paginate(40)->withQueryString();
        $genres   = Genre::withCount('shows')->orderByDesc('shows_count')->get();
        $networks = Show::distinct()->orderBy('network')->pluck('network')->filter()->values();
        $statuses = ['Running' => 'Airing Now', 'Returning Series' => 'Returning', 'Ended' => 'Ended', 'Cancelled' => 'Cancelled', 'Hiatus' => 'Hiatus'];
        $seoPage  = Page::where('slug', 'shows')->first();

        return view('shows.index', compact('shows', 'genres', 'networks', 'statuses', 'q', 'status', 'genre', 'network', 'sort', 'year', 'seoPage'));
    }

    public function show(string $slug)
    {
        $show = Show::with(['genres', 'images', 'episodes' => fn($q) => $q->orderBy('season_number')->orderBy('episode_number')])
            ->where('slug', $slug)
            ->firstOrFail();

        $seasons        = $show->episodes->groupBy('season_number')->sortKeys();
        $latestEpisodes = $show->episodes->sortByDesc('airs_on')->take(3)->values();

        $genreIds     = $show->genres->pluck('id');
        $relatedShows = Show::with('genres')
            ->whereHas('genres', fn($q) => $q->whereIn('genres.id', $genreIds))
            ->where('id', '!=', $show->id)
            ->orderBy('subscribers', 'desc')
            ->take(18)
            ->get();

        return view('shows.show', compact('show', 'seasons', 'latestEpisodes', 'relatedShows'));
    }

    public function calendar(Request $request)
    {
        $today     = Carbon::today();
        $dailyOnly = $request->boolean('daily');

        if ($request->has('week')) {
            $weekStart = Carbon::parse($request->input('week'))->startOfWeek(Carbon::MONDAY);
        } else {
            $latestEpDate = Episode::whereNotNull('airs_on')->max('airs_on');
            $weekStart = $latestEpDate
                ? Carbon::parse($latestEpDate)->startOfWeek(Carbon::MONDAY)
                : $today->copy()->startOfWeek(Carbon::MONDAY);
        }

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $episodesQuery = Episode::with(['show' => fn($q) => $q->select('id', 'title', 'slug', 'poster', 'poster_local', 'network', 'runtime')])
            ->whereNotNull('airs_on')
            ->whereBetween('airs_on', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->orderBy('airs_on')
            ->orderBy('episode_number');

        if ($dailyOnly) {
            $episodesQuery->whereHas('show', fn($q) => $q->where('runtime', '<=', 45));
        }

        $episodes = $episodesQuery->get()->groupBy(fn($e) => Carbon::parse($e->airs_on)->format('Y-m-d'));

        $prevWeek = $weekStart->copy()->subWeek()->toDateString();
        $nextWeek = $weekStart->copy()->addWeek()->toDateString();
        $days     = collect(range(0, 6))->map(fn($i) => $weekStart->copy()->addDays($i));

        $seoPage = Page::where('slug', 'calendar')->first();

        return view('calendar', compact('days', 'episodes', 'today', 'weekStart', 'prevWeek', 'nextWeek', 'dailyOnly', 'seoPage'));
    }
}
