@extends('layouts.app')

@section('seo_title', $seoPage?->seo_title ?: 'Watch Turkish Dramas & Series Online')
@section('meta_description', $seoPage?->seo_description ?: 'Discover 500+ Turkish TV series and dramas on DiziBul. Find episode guides, cast info, ratings, and where to watch your favourite dizi with English subtitles.')
@if($seoPage?->noindex)@section('noindex', '1')@endif
@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "DiziBul",
  "url": "{{ url('/') }}",
  "description": "The ultimate guide to Turkish TV series and dramas — episode guides, cast info and streaming links.",
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{ url('/shows') }}?q={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>
@endsection

@section('content')

{{-- ═══════════════════════════════════ FEATURED SLIDER ═══ --}}
<section class="pt-[60px] bg-[#080810]">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-4">
        <div class="flex gap-3" style="height:420px">

            {{-- ── Left: auto-sliding carousel ───────────────────── --}}
            <div class="relative flex-1 overflow-hidden rounded-xl group">

                {{-- Slides --}}
                @foreach($sliderShows->take(5) as $idx => $show)
                <div class="slider-slide absolute inset-0 transition-opacity duration-700 {{ $idx === 0 ? 'opacity-100 z-10' : 'opacity-0 z-0' }}"
                     data-index="{{ $idx }}"
                     data-slug="{{ $show->slug }}">

                    {{-- Clickable link covers the entire slide (below nav buttons) --}}
                    <a href="/shows/{{ $show->slug }}" class="absolute inset-0 z-10 block overflow-hidden">
                        {{-- Blurred background fills the frame --}}
                        <img src="{{ $show->poster_url }}"
                             alt=""
                             aria-hidden="true"
                             class="absolute inset-0 w-full h-full object-cover scale-110 blur-2xl brightness-[0.35]">
                        {{-- Full portrait poster, fully visible, centered --}}
                        <div class="absolute inset-0 flex items-center justify-center">
                            <img src="{{ $show->poster_url }}"
                                 alt="{{ $show->title }}"
                                 class="h-full w-auto max-w-none object-contain drop-shadow-2xl">
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-6 pb-8">
                            <h2 class="text-white text-xl sm:text-2xl font-bold leading-snug line-clamp-2 mb-2 drop-shadow">
                                {{ $show->title }}
                            </h2>
                            <p class="text-slate-300 text-sm line-clamp-2 leading-relaxed drop-shadow max-w-2xl">
                                {{ Str::limit(strip_tags($show->synopsis ?? ''), 160) }}
                            </p>
                        </div>
                    </a>
                </div>
                @endforeach

                {{-- Prev / Next — z-20 so they sit above the link overlay --}}
                <button id="slider-prev"
                        class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-black/50 hover:bg-black/80 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                <button id="slider-next"
                        class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-9 h-9 rounded-full bg-black/50 hover:bg-black/80 flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                {{-- Dots — z-20 --}}
                <div class="absolute bottom-3 right-5 z-20 flex gap-1.5">
                    @foreach($sliderShows->take(5) as $idx => $show)
                    <button class="slider-dot rounded-full transition-all duration-300 {{ $idx === 0 ? 'w-5 h-2 bg-white' : 'w-2 h-2 bg-white/40 hover:bg-white/70' }}"
                            data-index="{{ $idx }}"></button>
                    @endforeach
                </div>
            </div>

            {{-- ── Right: sidebar cards (same pool as slider, indices 1–4) --}}
            <div class="hidden lg:flex w-[290px] flex-col gap-2">
                @foreach($sliderShows->take(5)->skip(1) as $show)
                <div class="sidebar-card flex gap-3 bg-[#0d0d18] rounded-lg overflow-hidden hover:bg-[#13131f] transition-all cursor-pointer flex-1 min-h-0 ring-0 ring-violet-500/0"
                     data-slide-index="{{ $loop->index + 1 }}">
                    <div class="w-[88px] shrink-0 overflow-hidden">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="flex flex-col justify-center py-3 pr-3 min-w-0">
                        <span class="text-[10px] font-bold text-violet-400 uppercase tracking-wider mb-1">Trending</span>
                        <h3 class="text-white text-sm font-semibold line-clamp-2 leading-snug hover:text-violet-300 transition-colors">
                            {{ $show->title }}
                        </h3>
                        <p class="text-slate-500 text-xs mt-1.5 line-clamp-2 leading-relaxed">
                            {{ Str::limit(strip_tags($show->synopsis ?? ''), 80) }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
{{-- ═══════════════════════════════════════════════════════════════ --}}

{{-- ══════════════════════ TWO-COLUMN WRAPPER STARTS ══════════════════════ --}}
<div class="bg-[#080810]">
<div class="max-w-[1400px] mx-auto px-4 sm:px-6 flex gap-6 items-start">

{{-- ─────────────────────── MAIN CONTENT (left) ─────────────────────── --}}
<div class="flex-1 min-w-0">

{{-- ═══════════════════════════════════ TOP 10 TODAY ═══════════ --}}
<section class="py-6">

        {{-- Section heading --}}
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">
                Top 10 Today
            </h2>
        </div>

        {{-- Swiper carousel --}}
        <div class="swiper top10-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($top10Shows as $rank => $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">

                        {{-- Poster --}}
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}"
                                 alt="{{ $show->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>

                        {{-- Rank number --}}
                        <div class="absolute -bottom-3 -left-2 text-[72px] font-black leading-none select-none pointer-events-none"
                             style="color: transparent; -webkit-text-stroke: 2px rgba(255,255,255,0.18); font-family: Arial Black, sans-serif;">
                            {{ $rank + 1 }}
                        </div>

                    </a>
                </div>
                @endforeach
            </div>
        </div>

</section>
{{-- ═══════════════════════════════════════════════════════════════ --}}

{{-- ═══════════════════════════════════ RECENTLY ADDED ═══════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">
                Recently Added
            </h2>
        </div>
        <div class="swiper recently-added-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($recentlyAdded as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ CLASSIC TURKISH DRAMAS ════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Classic Turkish Dramas</h2>
        </div>
        <div class="swiper classic-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($classicDramas as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ FOR DIZI NEWCOMERS ════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">For Dizi Newcomers</h2>
        </div>
        <div class="swiper dizi-newcomers-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($diziNewcomers as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ PERIOD DRAMAS ═════════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Period Dramas</h2>
        </div>
        <div class="swiper period-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($periodDramas as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ STREAMING ON NETFLIX ══════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Streaming on Netflix</h2>
        </div>
        <div class="swiper netflix-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($netflixShows as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ LOVE IS IN THE AIR ════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Love Is In The Air</h2>
        </div>
        <div class="swiper love-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($loveShows as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ TURKISH REMAKES ═══════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Turkish Remakes</h2>
        </div>
        <div class="swiper turkish-remakes-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($turkishRemakes as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ IMPOSSIBLE LOVE STORIES ═══════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Impossible Love Stories</h2>
        </div>
        <div class="swiper impossible-love-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($impossibleLove as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ TURKISH DAILY DRAMAS ══════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Turkish Daily Dramas</h2>
        </div>
        <div class="swiper daily-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($dailyDramas as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ ENEMIES TO LOVERS ═════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Enemies To Lovers</h2>
        </div>
        <div class="swiper enemies-lovers-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($enemiesToLovers as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ FAMILY TREE ═══════════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Family Tree</h2>
        </div>
        <div class="swiper family-tree-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($familyTree as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ BINGE-WORTHY ══════════════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Binge-Worthy</h2>
        </div>
        <div class="swiper binge-worthy-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($bingeWorthy as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ WATCH IN ONE WEEKEND ══════════════ --}}
<section class="py-6">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Watch In One Weekend</h2>
        </div>
        <div class="swiper one-weekend-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($oneWeekend as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

{{-- ══════════════════════════ GONE TOO SOON ═════════════════════ --}}
<section class="py-6 pb-10">
        <div class="mb-4">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">Gone Too Soon</h2>
        </div>
        <div class="swiper gone-too-soon-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($goneTooSoon as $show)
                <div class="swiper-slide !w-[130px] sm:!w-[150px]">
                    <a href="/shows/{{ $show->slug }}" class="relative block group">
                        <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/70 to-transparent"></div>
                        </div>
                        <p class="mt-2 text-white text-xs font-medium line-clamp-1 leading-snug px-0.5">{{ $show->title }}</p>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
</section>

</div>{{-- END main content --}}

{{-- ─────────────────────── STICKY SIDEBAR (right) ─────────────────────── --}}
<aside class="hidden xl:block w-[268px] shrink-0 py-6">
    <div class="sticky top-[76px]">

        {{-- Most Popular --}}
        <div class="bg-[#0d0d18] rounded-xl overflow-hidden border border-white/5">
            <div class="px-4 py-3 border-b border-white/5 flex items-center gap-2">
                <span class="w-1 h-4 bg-[#e63946] rounded-full inline-block"></span>
                <h3 class="text-white text-xs font-black uppercase tracking-widest">Most Popular</h3>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($sidebarShows as $i => $show)
                <a href="/shows/{{ $show->slug }}"
                   class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/[0.04] transition-colors group">
                    <span class="text-slate-600 text-xs font-bold w-5 shrink-0 text-center group-hover:text-slate-400 transition-colors">
                        {{ $i + 1 }}
                    </span>
                    <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-[#13131f]">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white text-xs font-semibold line-clamp-2 leading-snug group-hover:text-violet-300 transition-colors">
                            {{ $show->title }}
                        </p>
                        @if($show->year || $show->status)
                        <p class="text-slate-500 text-[10px] mt-0.5">
                            {{ $show->year }}{{ $show->year && $show->status ? ' · ' : '' }}{{ $show->status === 'Running' ? 'Airing' : ($show->status === 'Ended' ? 'Ended' : $show->status) }}
                        </p>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t border-white/5">
                <a href="{{ url('/shows') }}"
                   class="block text-center text-xs font-semibold text-violet-400 hover:text-violet-300 transition-colors">
                    Browse All Shows →
                </a>
            </div>
        </div>

        {{-- Airing Now --}}
        <div class="bg-[#0d0d18] rounded-xl overflow-hidden border border-white/5 mt-4">
            <div class="px-4 py-3 border-b border-white/5 flex items-center gap-2">
                <span class="w-1 h-4 bg-emerald-400 rounded-full inline-block"></span>
                <h3 class="text-white text-xs font-black uppercase tracking-widest">Airing Now</h3>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($sidebarTopRated as $i => $show)
                <a href="/shows/{{ $show->slug }}"
                   class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/[0.04] transition-colors group">
                    <span class="text-slate-600 text-xs font-bold w-5 shrink-0 text-center group-hover:text-slate-400 transition-colors">
                        {{ $i + 1 }}
                    </span>
                    <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-[#13131f]">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-white text-xs font-semibold line-clamp-2 leading-snug group-hover:text-emerald-300 transition-colors">
                            {{ $show->title }}
                        </p>
                        <div class="flex items-center gap-1 mt-0.5">
                            <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-400 shrink-0"></span>
                            <span class="text-emerald-400 text-[10px] font-semibold">Airing</span>
                            @if($show->network)
                            <span class="text-slate-600 text-[10px]">· {{ $show->network }}</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="px-4 py-3 border-t border-white/5">
                <a href="{{ url('/shows?status=Running') }}"
                   class="block text-center text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                    See All Airing Shows →
                </a>
            </div>
        </div>

    </div>
</aside>

</div>{{-- END flex row --}}
</div>{{-- END bg wrapper --}}

@endsection

@push('scripts')
<script>
(function () {
    const slides       = document.querySelectorAll('.slider-slide');
    const dots         = document.querySelectorAll('.slider-dot');
    const sidebarCards = document.querySelectorAll('.sidebar-card');
    let current = 0;
    let timer;

    function goTo(n) {
        // Deactivate current slide
        slides[current].classList.replace('opacity-100', 'opacity-0');
        slides[current].classList.replace('z-10', 'z-0');
        dots[current].classList.remove('w-5', 'h-2', 'bg-white');
        dots[current].classList.add('w-2', 'h-2', 'bg-white/40');

        current = ((n % slides.length) + slides.length) % slides.length;

        // Activate new slide
        slides[current].classList.replace('opacity-0', 'opacity-100');
        slides[current].classList.replace('z-0', 'z-10');
        dots[current].classList.remove('w-2', 'bg-white/40');
        dots[current].classList.add('w-5', 'h-2', 'bg-white');

        // Highlight the matching sidebar card
        sidebarCards.forEach(card => {
            const isActive = +card.dataset.slideIndex === current;
            card.classList.toggle('ring-2',           isActive);
            card.classList.toggle('ring-violet-500',  isActive);
            card.classList.toggle('bg-[#1a1a2e]',     isActive);
        });
    }

    function start() { timer = setInterval(() => goTo(current + 1), 5000); }
    function reset() { clearInterval(timer); start(); }

    // Prev / Next buttons
    document.getElementById('slider-prev')?.addEventListener('click', e => { e.preventDefault(); goTo(current - 1); reset(); });
    document.getElementById('slider-next')?.addEventListener('click', e => { e.preventDefault(); goTo(current + 1); reset(); });

    // Dots
    dots.forEach(d => d.addEventListener('click', e => { e.preventDefault(); goTo(+d.dataset.index); reset(); }));

    // Sidebar cards → change slide (no page navigation)
    sidebarCards.forEach(card => {
        card.addEventListener('click', () => { goTo(+card.dataset.slideIndex); reset(); });
    });

    start();
})();
</script>
@endpush
