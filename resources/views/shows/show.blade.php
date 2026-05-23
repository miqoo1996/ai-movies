@extends('layouts.app')

@section('seo_title', $show->seo_title ?: $show->title)
@section('meta_description', $show->seo_description ?: Str::limit(strip_tags($show->synopsis ?? ''), 160))
@section('og_image', $show->poster_url)
@section('canonical', route('shows.show', $show->slug))
@if($show->noindex)@section('noindex', '1')@endif

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- ══════════════════════════════════ HERO ══════════════════════ --}}
    <div class="relative overflow-hidden">

        {{-- Backdrop: full-bleed blurred poster --}}
        <div class="absolute inset-0">
            <img src="{{ $show->poster_url }}" alt="" aria-hidden="true"
                 class="w-full h-full object-cover scale-110 blur-3xl brightness-[0.45] saturate-150">
            {{-- left-to-right dark veil so left content stays readable --}}
            <div class="absolute inset-0 bg-gradient-to-r from-[#080810]/90 via-[#080810]/60 to-transparent"></div>
            {{-- bottom fade --}}
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#080810] to-transparent"></div>
        </div>

        <div class="relative z-10 max-w-[1400px] mx-auto px-6 py-14">
            <div class="flex gap-9 items-end">

                {{-- Poster --}}
                <div class="shrink-0 w-[160px] sm:w-[200px] rounded-2xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.8)] ring-1 ring-white/10">
                    <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-auto object-cover">
                </div>

                {{-- Details --}}
                <div class="flex-1 min-w-0 pb-2">

                    {{-- Title --}}
                    <h1 class="text-white text-3xl sm:text-[2.6rem] font-black leading-tight tracking-tight mb-2 drop-shadow-xl">
                        {{ $show->title }}
                    </h1>

                    @if($show->original_title && $show->original_title !== $show->title)
                        <p class="text-slate-400 text-sm mb-3 -mt-1">{{ $show->original_title }}</p>
                    @endif

                    {{-- Meta chips --}}
                    <div class="flex flex-wrap items-center gap-2 mb-5">
                        @if($show->year)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">{{ $show->year }}</span>
                        @endif
                        @if($show->network)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">{{ $show->network }}</span>
                        @endif
                        @if($show->runtime)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">{{ $show->runtime }} min</span>
                        @endif
                        @foreach($show->genres as $genre)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-violet-500/20 text-violet-300 border border-violet-500/20">{{ $genre->name }}</span>
                        @endforeach
                    </div>

                    {{-- Status + social --}}
                    <div class="flex items-center flex-wrap gap-3 mb-6">
                        @php
                            [$sbg, $stxt] = match($show->status) {
                                'Running', 'Returning Series' => ['bg-emerald-500', 'text-white'],
                                'Cancelled'                   => ['bg-red-500',     'text-white'],
                                'Hiatus'                      => ['bg-amber-500',   'text-white'],
                                default                       => ['bg-slate-600',   'text-white'],
                            };
                        @endphp
                        @if($show->status)
                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full {{ $sbg }} {{ $stxt }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-white/60 inline-block"></span>
                                {{ $show->status }}
                            </span>
                        @endif

                        <div class="flex items-center gap-2 ml-1">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#1877F2] flex items-center justify-center transition-all duration-200 hover:scale-110">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($show->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#000] flex items-center justify-center transition-all duration-200 hover:scale-110">
                                <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="https://www.youtube.com/results?search_query={{ urlencode($show->title) }}" target="_blank" rel="noopener"
                               class="w-8 h-8 rounded-full bg-white/10 hover:bg-[#FF0000] flex items-center justify-center transition-all duration-200 hover:scale-110">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.95C5.12 20 12 20 12 20s6.88 0 8.59-.47a2.78 2.78 0 0 0 1.95-1.95A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/>
                                    <polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02" fill="white"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    {{-- Synopsis --}}
                    @if($show->synopsis)
                        <div class="text-slate-200 text-[15px] leading-7 max-w-2xl line-clamp-3 prose prose-invert prose-sm max-w-none">
                            {!! $show->synopsis !!}
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════ TAB BAR ═══════════════════════ --}}
    <div class="border-b border-white/8 bg-[#080810] sticky top-[60px] z-40">
        <div class="max-w-[1400px] mx-auto px-6">
            <nav class="flex items-center overflow-x-auto scrollbar-hide" id="show-tabs">
{{--                ['Overview', 'Episodes', 'Cast & Crew', 'Reviews', 'Lists', 'News', 'Related']--}}
                @foreach(['Overview', 'Episodes', 'Related'] as $tab)
                <button data-tab="{{ Str::slug($tab) }}"
                        class="show-tab shrink-0 px-5 py-[14px] text-[13px] font-bold uppercase tracking-wider transition-all duration-200 border-b-2
                               {{ $loop->first
                                   ? 'text-white border-[#e63946]'
                                   : 'text-slate-400 border-transparent hover:text-slate-100 hover:border-white/30' }}">
                    {{ $tab }}
                </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- ══════════════════════════════ TAB PANELS ════════════════════ --}}
    <div class="max-w-[1400px] mx-auto px-6 py-10">

        {{-- ── OVERVIEW ───────────────────────────────────────────── --}}
        <div id="panel-overview" class="show-panel space-y-12">

            {{-- Seasons --}}
            @if($seasons->isNotEmpty())
            <div>
                <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5 mb-5">
                    {{ $seasons->count() }} {{ Str::plural('Season', $seasons->count()) }}
                </h2>
                <div class="flex flex-col sm:flex-row flex-wrap gap-3">
                    @foreach($seasons as $seasonNum => $episodes)
                    <div class="flex items-center gap-4 bg-[#0d0d18] hover:bg-[#111122] border border-white/5 hover:border-white/15 rounded-2xl overflow-hidden transition-all duration-200 sm:w-72 cursor-pointer group">
                        <div class="w-20 h-20 shrink-0 overflow-hidden">
                            @php $sThumb = $episodes->whereNotNull('thumb')->first()?->thumb_url ?? $show->poster_url; @endphp
                            <img src="{{ $sThumb }}" alt="Season {{ $seasonNum }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        </div>
                        <div class="py-3 pr-4">
                            <p class="text-white font-bold text-sm mb-0.5">Season {{ $seasonNum }}</p>
                            <p class="text-slate-400 text-xs">{{ $episodes->count() }} {{ Str::plural('Episode', $episodes->count()) }}</p>
                            @if($episodes->first()?->airs_on && $episodes->last()?->airs_on)
                                <p class="text-slate-600 text-[11px] mt-1">
                                    {{ $episodes->first()->airs_on->format('M Y') }} – {{ $episodes->last()->airs_on->format('M Y') }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Latest Episodes --}}
            @if($latestEpisodes->isNotEmpty())
            <div>
                <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5 mb-5">
                    Latest Episodes
                </h2>
                <div class="flex gap-4 overflow-x-auto scrollbar-hide pb-2">
                    @foreach($latestEpisodes as $ep)
                    <div class="shrink-0 w-[230px] bg-[#0d0d18] border border-white/5 hover:border-white/15 rounded-2xl overflow-hidden transition-all duration-200 group cursor-pointer">
                        {{-- Thumbnail --}}
                        <div class="relative aspect-video overflow-hidden">
                            @if($ep->thumb_url)
                                <img src="{{ $ep->thumb_url }}" alt="{{ $ep->shortcode }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ $show->poster_url }}" alt=""
                                     class="absolute inset-0 w-full h-full object-cover scale-110 blur-md brightness-50 saturate-150">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-1">
                                    <span class="text-white/90 text-sm font-black tracking-widest drop-shadow">{{ $ep->shortcode }}</span>
                                    <span class="text-white/40 text-[10px] font-medium uppercase tracking-wider">No Preview</span>
                                </div>
                            @endif
                            {{-- Overlay + play --}}
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            {{-- Episode badge --}}
                            <div class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                {{ $ep->shortcode }}
                            </div>
                        </div>
                        <div class="px-4 py-3">
                            @if($ep->airs_on)
                                <p class="text-slate-400 text-xs">{{ $ep->airs_on->format('M d, Y') }}</p>
                            @endif
                            @if($ep->season_finale)
                                <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-amber-400">Season Finale</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        {{-- Photos --}}
        @if($show->images->isNotEmpty())
        @php $photos = $show->images->take(12); @endphp
        <div>
            <div class="flex items-baseline gap-3 mb-5">
                <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">
                    Photos
                </h2>
                <span class="text-slate-500 text-xs">{{ $show->images->count() }} {{ Str::plural('photo', $show->images->count()) }}</span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                @foreach($photos as $i => $img)
                <div class="relative overflow-hidden rounded-lg aspect-video bg-[#0d0d18] group cursor-pointer
                            {{ $i === 0 ? 'col-span-2 row-span-2' : '' }}">
                    <img src="{{ $img->local_path ? asset($img->local_path) : $img->url }}" alt="Photo {{ $loop->iteration }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                </div>
                @endforeach
                @if($show->images->count() > 12)
                <div class="relative overflow-hidden rounded-lg aspect-video bg-[#0d0d18] flex items-center justify-center cursor-pointer group border border-white/10 hover:border-white/20 transition-colors">
                    <div class="text-center">
                        <p class="text-white font-bold text-lg">+{{ $show->images->count() - 12 }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">more photos</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        </div>

        {{-- ── EPISODES ────────────────────────────────────────────── --}}
        <div id="panel-episodes" class="show-panel hidden">
            @if($seasons->isNotEmpty())

            {{-- Season Selector --}}
            <div class="flex items-center gap-2 flex-wrap mb-8">
                @foreach($seasons as $seasonNum => $episodes)
                <button data-season="{{ $seasonNum }}"
                        class="season-btn px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider border transition-all duration-200
                               {{ $loop->first
                                   ? 'bg-[#e63946] border-[#e63946] text-white'
                                   : 'bg-transparent border-white/20 text-slate-400 hover:border-white/40 hover:text-slate-200' }}">
                    Season {{ $seasonNum }}
                    <span class="ml-1 opacity-60">{{ $episodes->count() }}</span>
                </button>
                @endforeach
            </div>

            {{-- Season Panels --}}
            @foreach($seasons as $seasonNum => $episodes)
            <div id="season-{{ $seasonNum }}" class="season-panel {{ $loop->first ? '' : 'hidden' }}">

                {{-- Episode count header --}}
                <p class="text-slate-500 text-xs uppercase tracking-widest font-semibold mb-5">
                    {{ $episodes->count() }} {{ Str::plural('Episode', $episodes->count()) }}
                    @php
                        $first = $episodes->first();
                        $last  = $episodes->last();
                    @endphp
                    @if($first?->airs_on && $last?->airs_on)
                        &nbsp;·&nbsp; {{ $first->airs_on->format('M Y') }} – {{ $last->airs_on->format('M Y') }}
                    @endif
                </p>

                <div class="flex flex-wrap gap-4">
                    @foreach($episodes->sortBy('episode_number') as $ep)
                    <div class="shrink-0 w-[230px] bg-[#0d0d18] border border-white/5 hover:border-white/15 rounded-2xl overflow-hidden transition-all duration-200 group cursor-pointer">
                        {{-- Thumbnail --}}
                        <div class="relative aspect-video overflow-hidden">
                            @if($ep->thumb_url)
                                <img src="{{ $ep->thumb_url }}" alt="{{ $ep->shortcode }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <img src="{{ $show->poster_url }}" alt=""
                                     class="absolute inset-0 w-full h-full object-cover scale-110 blur-md brightness-50 saturate-150">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                                <div class="absolute inset-0 flex flex-col items-center justify-center gap-1">
                                    <span class="text-white/90 text-sm font-black tracking-widest drop-shadow">{{ $ep->shortcode }}</span>
                                    <span class="text-white/40 text-[10px] font-medium uppercase tracking-wider">No Preview</span>
                                </div>
                            @endif
                            {{-- Overlay + play --}}
                            <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-black/50 backdrop-blur-sm border border-white/20 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                    <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>
                            {{-- Episode badge --}}
                            <div class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[10px] font-bold px-2 py-0.5 rounded-md">
                                {{ $ep->shortcode }}
                            </div>
                        </div>
                        <div class="px-4 py-3">
                            @if($ep->airs_on)
                                <p class="text-slate-400 text-xs">{{ $ep->airs_on->format('M d, Y') }}</p>
                            @endif
                            @if($ep->season_finale)
                                <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-amber-400">Season Finale</span>
                            @endif
                            @if(! $ep->has_aired)
                                <span class="inline-block mt-1 text-[10px] font-bold uppercase tracking-wider text-slate-500">Upcoming</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach

            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <svg class="w-12 h-12 text-slate-700 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
                </svg>
                <p class="text-slate-500 text-sm">No episodes available yet.</p>
            </div>
            @endif
        </div>

        {{-- ── RELATED ─────────────────────────────────────────────── --}}
        <div id="panel-related" class="show-panel hidden">
            @if($relatedShows->isNotEmpty())
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">
                    Related Shows
                </h2>
                <span class="text-slate-500 text-xs">{{ $relatedShows->count() }} shows</span>
            </div>
            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                @foreach($relatedShows as $rel)
                <a href="/shows/{{ $rel->slug }}" class="block group">
                    <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18] mb-1.5">
                        <img src="{{ $rel->poster_url }}" alt="{{ $rel->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @if($rel->network)
                        <span class="absolute bottom-2 left-2 text-[9px] font-bold bg-black/75 backdrop-blur-sm text-white px-1.5 py-0.5 rounded">
                            {{ $rel->network }}
                        </span>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/25 transition-colors"></div>
                    </div>
                    <p class="text-slate-300 text-[11px] font-semibold leading-snug line-clamp-2 group-hover:text-white transition-colors">
                        {{ $rel->title }}
                    </p>
                    @if($rel->year)
                    <p class="text-slate-600 text-[10px] mt-0.5">{{ $rel->year }}</p>
                    @endif
                </a>
                @endforeach
            </div>
            @else
            <div class="flex flex-col items-center justify-center py-20 text-center">
                <p class="text-slate-500 text-sm">No related shows found.</p>
            </div>
            @endif
        </div>

        {{-- Other panels --}}
        @foreach(['cast-crew', 'reviews', 'lists', 'news'] as $panel)
        <div id="panel-{{ $panel }}" class="show-panel hidden"></div>
        @endforeach

    </div>
</div>


@endsection

@push('scripts')
<script>
(function () {
    const tabs   = document.querySelectorAll('.show-tab');
    const panels = document.querySelectorAll('.show-panel');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => {
                t.classList.remove('text-white', 'border-[#e63946]');
                t.classList.add('text-slate-400', 'border-transparent');
            });
            tab.classList.add('text-white', 'border-[#e63946]');
            tab.classList.remove('text-slate-400', 'border-transparent');

            const target = 'panel-' + tab.dataset.tab;
            panels.forEach(p => p.classList.toggle('hidden', p.id !== target));
        });
    });

    // Season switcher inside the Episodes panel
    document.querySelectorAll('.season-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.season-btn').forEach(b => {
                b.classList.remove('bg-[#e63946]', 'border-[#e63946]', 'text-white');
                b.classList.add('bg-transparent', 'border-white/20', 'text-slate-400');
            });
            btn.classList.add('bg-[#e63946]', 'border-[#e63946]', 'text-white');
            btn.classList.remove('bg-transparent', 'border-white/20', 'text-slate-400');

            const season = btn.dataset.season;
            document.querySelectorAll('.season-panel').forEach(p => {
                p.classList.toggle('hidden', p.id !== 'season-' + season);
            });
        });
    });
})();
</script>
@endpush
