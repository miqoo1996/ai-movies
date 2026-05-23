@extends('layouts.app')

@php
    // Default from DB; filtered pages get dynamic copy + noindex
    $seoTitle = $seoPage?->seo_title ?: 'All Turkish TV Series & Dramas';
    $seoDesc  = $seoPage?->seo_description ?: 'Browse 500+ Turkish TV series and dramas on DiziBul. Filter by genre, network, year and status to find your next favourite dizi to watch with English subtitles.';
    $isFiltered = !empty($genre) || !empty($status) || !empty($year) || !empty($q) || !empty($network);

    if ($isFiltered) {
        $activeGenre = $genres->firstWhere('slug', $genre);
        if ($activeGenre) {
            $seoTitle = $activeGenre->name . ' Turkish Dramas';
            $seoDesc  = 'Browse the best ' . $activeGenre->name . ' Turkish TV series and dramas. Episode guides, cast info and where to stream with English subtitles.';
        } elseif (!empty($status)) {
            $seoTitle = ($statuses[$status] ?? $status) . ' Turkish TV Series';
            $seoDesc  = 'Discover ' . strtolower($statuses[$status] ?? $status) . ' Turkish TV series on DiziBul.';
        } elseif (!empty($year)) {
            $seoTitle = 'Turkish Series from ' . $year;
            $seoDesc  = 'Browse Turkish TV series and dramas released in ' . $year . '.';
        }
    }
@endphp
@section('seo_title', $seoTitle)
@section('meta_description', $seoDesc)
@if($isFiltered || $seoPage?->noindex)@section('noindex', '1')@endif

@section('content')

<div class="pt-[60px] bg-[#080810]">
<div class="max-w-[1400px] mx-auto px-4 sm:px-6" style="display:flex; align-items:flex-start; gap:2rem;">

    {{-- ════════════════════ LEFT SIDEBAR ════════════════════ --}}
    <aside style="width:220px; flex-shrink:0; border-right:1px solid rgba(255,255,255,0.07); padding:2rem 1.5rem 2rem 0; position:sticky; top:60px; overflow-y:auto; max-height:calc(100vh - 60px); align-self:flex-start; z-index:1;">

        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
            <h2 style="color:#fff; font-size:0.75rem; font-weight:900; text-transform:uppercase; letter-spacing:0.15em;">Filters</h2>
            @if($q || $status || $genre || $network || $year)
            <a href="/shows" style="font-size:0.7rem; color:#94a3b8; text-decoration:none;" onmouseover="this.style.color='#e63946'" onmouseout="this.style.color='#94a3b8'">Clear all</a>
            @endif
        </div>

        <form id="filter-form" method="GET" action="/shows">
            <input type="hidden" name="sort" value="{{ $sort }}">

            {{-- Keyword --}}
            <div style="margin-bottom:1.75rem;">
                <p class="sidebar-label">Keywords</p>
                <div style="position:relative;">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search series…"
                           style="width:100%; background:#111122; border:1px solid rgba(255,255,255,0.1); border-radius:0.5rem; padding:0.625rem 0.75rem 0.625rem 2.25rem; font-size:0.8125rem; color:#fff; outline:none; box-sizing:border-box;"
                           onfocus="this.style.borderColor='rgba(139,92,246,0.6)'" onblur="this.style.borderColor='rgba(255,255,255,0.1)'"
                           class="placeholder-shown:text-slate-600">
                    <svg style="position:absolute; left:0.625rem; top:50%; transform:translateY(-50%); width:14px; height:14px; color:#64748b;" fill="none" stroke="#64748b" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            {{-- Status --}}
            <div style="margin-bottom:1.75rem;">
                <p class="sidebar-label">Filter by Status</p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    <label class="filter-row">
                        <input type="radio" name="status" value="" {{ $status === '' ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                        <span class="filter-label {{ $status === '' ? 'active' : '' }}">All</span>
                    </label>
                    @foreach($statuses as $val => $label)
                    <label class="filter-row">
                        <input type="radio" name="status" value="{{ $val }}" {{ $status === $val ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                        <span class="filter-label {{ $status === $val ? 'active' : '' }}">{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Genre --}}
            <div style="margin-bottom:1.75rem;">
                <p class="sidebar-label">Filter by Genre</p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($genres->take(15) as $g)
                    <label class="filter-row" style="justify-content:space-between;">
                        <div style="display:flex; align-items:center; gap:0.625rem;">
                            <input type="radio" name="genre" value="{{ $g->slug }}" {{ $genre === $g->slug ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                            <span class="filter-label {{ $genre === $g->slug ? 'active' : '' }}">{{ $g->name }}</span>
                        </div>
                        <span style="font-size:0.7rem; color:#475569; flex-shrink:0;">{{ $g->shows_count }}</span>
                    </label>
                    @endforeach

                    @if($genres->count() > 15)
                    <button type="button" id="more-genres-btn" style="font-size:0.75rem; color:#64748b; background:none; border:none; cursor:pointer; text-align:left; padding:0; margin-top:0.25rem;">
                        + {{ $genres->count() - 15 }} more
                    </button>
                    <div id="more-genres" style="display:none; flex-direction:column; gap:0.5rem; margin-top:0.25rem;">
                        @foreach($genres->skip(15) as $g)
                        <label class="filter-row" style="justify-content:space-between;">
                            <div style="display:flex; align-items:center; gap:0.625rem;">
                                <input type="radio" name="genre" value="{{ $g->slug }}" {{ $genre === $g->slug ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                                <span class="filter-label {{ $genre === $g->slug ? 'active' : '' }}">{{ $g->name }}</span>
                            </div>
                            <span style="font-size:0.7rem; color:#475569; flex-shrink:0;">{{ $g->shows_count }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Network --}}
            <div style="margin-bottom:1.75rem;">
                <p class="sidebar-label">Filter by Network</p>
                <div style="display:flex; flex-direction:column; gap:0.5rem;">
                    @foreach($networks->take(12) as $net)
                    <label class="filter-row">
                        <input type="radio" name="network" value="{{ $net }}" {{ $network === $net ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                        <span class="filter-label {{ $network === $net ? 'active' : '' }}">{{ $net }}</span>
                    </label>
                    @endforeach

                    @if($networks->count() > 12)
                    <button type="button" id="more-networks-btn" style="font-size:0.75rem; color:#64748b; background:none; border:none; cursor:pointer; text-align:left; padding:0; margin-top:0.25rem;">
                        + {{ $networks->count() - 12 }} more
                    </button>
                    <div id="more-networks" style="display:none; flex-direction:column; gap:0.5rem; margin-top:0.25rem;">
                        @foreach($networks->skip(12) as $net)
                        <label class="filter-row">
                            <input type="radio" name="network" value="{{ $net }}" {{ $network === $net ? 'checked' : '' }} class="cf-radio" onchange="this.form.submit()">
                            <span class="filter-label {{ $network === $net ? 'active' : '' }}">{{ $net }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <button type="submit" style="width:100%; padding:0.625rem; background:#7c3aed; border:none; border-radius:0.5rem; color:#fff; font-size:0.75rem; font-weight:700; letter-spacing:0.08em; text-transform:uppercase; cursor:pointer;"
                    onmouseover="this.style.background='#6d28d9'" onmouseout="this.style.background='#7c3aed'">
                Apply Filters
            </button>
        </form>
    </aside>

    {{-- ════════════════════ MAIN CONTENT ════════════════════ --}}
    <div style="flex:1; min-width:0; padding:2rem 0 4rem 0;">

        {{-- Header --}}
        <div style="display:flex; align-items:flex-end; justify-content:space-between; gap:1rem; margin-bottom:2rem; flex-wrap:wrap;">
            <div>
                <h1 class="text-white font-black tracking-tight" style="font-size:1.75rem; margin:0 0 0.25rem;">Explore Turkish Series</h1>
                <p style="color:#64748b; font-size:0.8125rem; margin:0;">{{ number_format($shows->total()) }} series found</p>
            </div>
            <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
                @foreach([
                    ['subscribers', 'Popular'],
                    ['rating',      'Top Rated'],
                    ['year_desc',   'Newest'],
                    ['year_asc',    'Oldest'],
                    ['title',       'A–Z'],
                ] as [$val, $label])
                <a href="{{ request()->fullUrlWithQuery(['sort' => $val]) }}"
                   style="padding:0.375rem 0.875rem; border-radius:0.375rem; font-size:0.75rem; font-weight:600; text-decoration:none; transition:all 0.15s;
                          {{ $sort === $val ? 'background:#e63946; color:#fff;' : 'background:rgba(255,255,255,0.06); color:#94a3b8;' }}"
                   onmouseover="{{ $sort !== $val ? "this.style.background='rgba(255,255,255,0.1)'; this.style.color='#fff'" : '' }}"
                   onmouseout="{{ $sort !== $val ? "this.style.background='rgba(255,255,255,0.06)'; this.style.color='#94a3b8'" : '' }}">
                    {{ $label }}
                </a>
                @endforeach
            </div>
        </div>

        {{-- Active filter chips --}}
        @if($status || $genre || $network || $q)
        <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap; margin-bottom:1.5rem;">
            <span style="font-size:0.7rem; color:#475569; text-transform:uppercase; letter-spacing:0.1em;">Active:</span>
            @if($status)
            <a href="{{ request()->fullUrlWithQuery(['status' => null]) }}" class="filter-chip red">{{ $statuses[$status] ?? $status }} &times;</a>
            @endif
            @if($genre)
            <a href="{{ request()->fullUrlWithQuery(['genre' => null]) }}" class="filter-chip violet">{{ $genres->firstWhere('slug', $genre)?->name ?? $genre }} &times;</a>
            @endif
            @if($network)
            <a href="{{ request()->fullUrlWithQuery(['network' => null]) }}" class="filter-chip violet">{{ $network }} &times;</a>
            @endif
            @if($q)
            <a href="{{ request()->fullUrlWithQuery(['q' => null]) }}" class="filter-chip violet">"{{ $q }}" &times;</a>
            @endif
        </div>
        @endif

        {{-- Shows grid — 6 columns via inline style to guarantee rendering --}}
        @if($shows->count())
        <div style="display:grid; grid-template-columns:repeat(6,minmax(0,1fr)); gap:1rem;">
            @foreach($shows as $show)
            <a href="/shows/{{ $show->slug }}" class="show-card" style="position:relative; display:block; border-radius:0.75rem; overflow:hidden; background:#111122; aspect-ratio:2/3; text-decoration:none;">

                {{-- Poster --}}
                @if($show->poster_url)
                <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" loading="lazy"
                     style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover; transition:transform 0.5s;">
                @else
                <div style="position:absolute; inset:0; background:linear-gradient(135deg,#1a1a2e,#0d0d18); display:flex; align-items:center; justify-content:center;">
                    <svg width="32" height="32" fill="#334155" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/></svg>
                </div>
                @endif

                {{-- Gradient overlay --}}
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.3) 45%, transparent 100%);"></div>

                {{-- Live badge --}}
                @if($show->status === 'Running' || $show->status === 'Returning Series')
                <div style="position:absolute; top:0.5rem; left:0.5rem; display:flex; align-items:center; gap:0.25rem; padding:0.2rem 0.45rem; border-radius:0.25rem; background:rgba(16,185,129,0.9);">
                    <span style="width:5px; height:5px; border-radius:50%; background:#fff;"></span>
                    <span style="font-size:0.55rem; font-weight:900; text-transform:uppercase; letter-spacing:0.08em; color:#fff;">Live</span>
                </div>
                @endif

                {{-- Year --}}
                @if($show->year)
                <div style="position:absolute; top:0.5rem; right:0.5rem; padding:0.15rem 0.4rem; border-radius:0.25rem; background:rgba(0,0,0,0.65);">
                    <span style="font-size:0.65rem; font-weight:700; color:rgba(255,255,255,0.75);">{{ $show->year }}</span>
                </div>
                @endif

                {{-- Title block --}}
                <div style="position:absolute; bottom:0; left:0; right:0; padding:1.5rem 0.625rem 0.625rem;">
                    <h3 style="color:#fff; font-size:0.75rem; font-weight:700; line-height:1.3; margin:0 0 0.2rem; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">{{ $show->title }}</h3>
                    @if($show->network)
                    <p style="color:rgba(255,255,255,0.45); font-size:0.625rem; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ $show->network }}</p>
                    @endif
                </div>

            </a>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($shows->hasPages())
        <div style="margin-top:3rem; display:flex; align-items:center; justify-content:center; gap:0.25rem; flex-wrap:wrap;">
            @if($shows->onFirstPage())
            <span class="page-btn disabled">‹ Prev</span>
            @else
            <a href="{{ $shows->previousPageUrl() }}" class="page-btn">‹ Prev</a>
            @endif

            @foreach($shows->getUrlRange(max(1, $shows->currentPage()-2), min($shows->lastPage(), $shows->currentPage()+2)) as $page => $url)
            @if($page == $shows->currentPage())
            <span class="page-btn active">{{ $page }}</span>
            @else
            <a href="{{ $url }}" class="page-btn">{{ $page }}</a>
            @endif
            @endforeach

            @if($shows->hasMorePages())
            <a href="{{ $shows->nextPageUrl() }}" class="page-btn">Next ›</a>
            @else
            <span class="page-btn disabled">Next ›</span>
            @endif
        </div>
        <p style="text-align:center; color:#475569; font-size:0.75rem; margin-top:0.75rem;">
            Page {{ $shows->currentPage() }} of {{ $shows->lastPage() }} &middot; {{ number_format($shows->total()) }} total
        </p>
        @endif

        @else
        <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; padding:8rem 1rem; text-align:center;">
            <div style="width:4rem; height:4rem; border-radius:1rem; background:rgba(255,255,255,0.04); display:flex; align-items:center; justify-content:center; margin-bottom:1.25rem;">
                <svg width="32" height="32" fill="none" stroke="#334155" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 style="color:#fff; font-size:1.125rem; font-weight:700; margin:0 0 0.5rem;">No series found</h3>
            <p style="color:#64748b; font-size:0.875rem; margin:0 0 1.5rem;">Try adjusting your filters or search terms.</p>
            <a href="/shows" style="padding:0.625rem 1.25rem; background:#e63946; color:#fff; border-radius:0.5rem; font-size:0.875rem; font-weight:700; text-decoration:none;">Clear Filters</a>
        </div>
        @endif

    </div>
</div>
</div>

<style>
/* Sidebar labels */
.sidebar-label {
    font-size: 0.65rem;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #94a3b8;
    margin: 0 0 0.75rem;
}

/* Filter rows */
.filter-row {
    display: flex;
    align-items: center;
    gap: 0.625rem;
    cursor: pointer;
}
.filter-row:hover .filter-label:not(.active) {
    color: #cbd5e1;
}

/* Filter labels */
.filter-label {
    font-size: 0.8125rem;
    color: #64748b;
    transition: color 0.15s;
}
.filter-label.active {
    color: #fff;
    font-weight: 600;
}

/* Custom radio */
.cf-radio {
    -webkit-appearance: none;
    appearance: none;
    width: 15px;
    height: 15px;
    border-radius: 50%;
    border: 1.5px solid rgba(255,255,255,0.22);
    background: transparent;
    cursor: pointer;
    flex-shrink: 0;
    transition: border-color 0.15s;
}
.cf-radio:checked {
    border-color: #e63946;
    background: #e63946;
    box-shadow: inset 0 0 0 3px #09090f;
}
.cf-radio:hover:not(:checked) {
    border-color: rgba(255,255,255,0.45);
}

/* Filter chips */
.filter-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.2rem 0.625rem;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: opacity 0.15s;
}
.filter-chip:hover { opacity: 0.8; }
.filter-chip.red {
    background: rgba(230,57,70,0.15);
    border: 1px solid rgba(230,57,70,0.3);
    color: #e63946;
}
.filter-chip.violet {
    background: rgba(139,92,246,0.15);
    border: 1px solid rgba(139,92,246,0.3);
    color: #a78bfa;
}

/* Show card hover */
.show-card img { transition: transform 0.45s ease; }
.show-card:hover img { transform: scale(1.06); }
.show-card::after {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 0.75rem;
    box-shadow: inset 0 0 0 0 rgba(230,57,70,0);
    transition: box-shadow 0.25s;
}
.show-card:hover::after {
    box-shadow: inset 0 0 0 2px rgba(230,57,70,0.7);
}

/* Pagination */
.page-btn {
    display: inline-flex;
    align-items: center;
    padding: 0.5rem 0.875rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    color: #94a3b8;
    transition: all 0.15s;
}
.page-btn:not(.active):not(.disabled):hover {
    background: rgba(255,255,255,0.07);
    color: #fff;
}
.page-btn.active {
    background: #e63946;
    color: #fff;
    font-weight: 700;
}
.page-btn.disabled {
    color: #334155;
    cursor: not-allowed;
}
</style>

@endsection

@push('scripts')
<script>
document.getElementById('more-genres-btn')?.addEventListener('click', function() {
    const el = document.getElementById('more-genres');
    const open = el.style.display === 'flex';
    el.style.display = open ? 'none' : 'flex';
    this.textContent = open ? '+ {{ $genres->count() - 15 }} more' : '− Show less';
});

document.getElementById('more-networks-btn')?.addEventListener('click', function() {
    const el = document.getElementById('more-networks');
    const open = el.style.display === 'flex';
    el.style.display = open ? 'none' : 'flex';
    this.textContent = open ? '+ {{ $networks->count() - 12 }} more' : '− Show less';
});

@if($genre && $genres->search(fn($g) => $g->slug === $genre) >= 15)
const mg = document.getElementById('more-genres');
if (mg) { mg.style.display = 'flex'; document.getElementById('more-genres-btn').textContent = '− Show less'; }
@endif
@if($network && $networks->search(fn($n) => $n === $network) >= 12)
const mn = document.getElementById('more-networks');
if (mn) { mn.style.display = 'flex'; document.getElementById('more-networks-btn').textContent = '− Show less'; }
@endif
</script>
@endpush
