@extends('layouts.app')

@section('seo_title', $seoPage?->seo_title ?: 'Watch Turkish Series & Movies with English Subtitles Online')
@section('meta_description', $seoPage?->seo_description ?: 'Watch the latest Turkish series and movies with English subtitles in HD quality. Stream romantic dramas, action series, historical shows, and Turkish cinema online anytime.')
@section('keywords', 'Turkish series with English subtitles, Turkish dramas online, watch Turkish series online free, Turkish movies with English subtitles, Turkish TV shows English subtitles, best Turkish dramas with English subtitles, Turkish drama series online, Turkish romantic series, Turkish historical dramas, Turkish action movies subtitles, latest Turkish dramas online, Turkish streaming platform, watch Turkish episodes online, Turkish series 2026, Turkish entertainment')
@if($seoPage?->noindex)@section('noindex', '1')@endif
@section('json_ld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ setting('site_name', 'DiziCentral') }}",
  "url": "{{ url('/') }}",
  "description": "Watch the latest Turkish series and movies with English subtitles in HD. Stream romantic dramas, action series, historical shows and Turkish cinema online.",
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

{{-- ═══════════════════════════════════ 3D HERO SLIDER ═══════════════════════════ --}}
<style>
/* ── 3D Hero Slider ──────────────────────────────────────────────────────── */
#hero3d { background: #080810; }

.hero3d-bg {
    position: absolute; inset: 0; z-index: 0;
    background-size: cover; background-position: center;
    filter: blur(70px) brightness(0.2) saturate(1.6);
    transform: scale(1.18);
    transition: background-image 1.1s ease;
}

.hero3d-vignette {
    position: absolute; inset: 0; z-index: 1;
    background:
        linear-gradient(to bottom, #080810 0px, transparent 100px, transparent 58%, #080810 100%),
        radial-gradient(ellipse 90% 80% at 50% 55%, transparent 25%, #080810 85%);
}

.hero3d-inner {
    position: relative; z-index: 2;
    display: flex; flex-direction: column; align-items: center;
}

/* Viewport */
.hero3d-viewport {
    position: relative; width: 100%; height: 390px;
    display: flex; align-items: center; justify-content: center;
    cursor: grab; overflow: visible; touch-action: pan-y;
}
.hero3d-viewport.grabbing { cursor: grabbing; }

/* Track holds all absolutely-positioned cards */
.hero3d-track {
    position: relative; width: 100%; height: 100%;
    overflow: visible;
}

/* Cards: anchored to center of track, JS moves via transform */
.hero3d-card {
    position: absolute;
    width: 210px; height: 315px;
    top: 50%; left: 50%;
    margin-top: -157px; margin-left: -105px;
    border-radius: 14px; overflow: hidden;
    cursor: pointer;
    will-change: transform, opacity;
    transition:
        transform  0.68s cubic-bezier(0.25, 0.46, 0.45, 0.94),
        opacity    0.68s ease,
        box-shadow 0.68s ease;
    -webkit-backface-visibility: hidden;
    backface-visibility: hidden;
}

.hero3d-card img {
    width: 100%; height: 100%;
    object-fit: cover; display: block;
    pointer-events: none; user-select: none; -webkit-user-select: none;
}

/* Glare sheen on center */
.hero3d-card::before {
    content: '';
    position: absolute; inset: 0; z-index: 2;
    background: linear-gradient(135deg, rgba(255,255,255,0.10) 0%, rgba(255,255,255,0.01) 55%);
    opacity: 0; transition: opacity 0.5s; pointer-events: none;
    border-radius: inherit;
}
.hero3d-card.is-center::before { opacity: 1; }

/* Hover watch-overlay on center card */
.hero3d-card::after {
    content: '';
    position: absolute; inset: 0; z-index: 3;
    background: rgba(0,0,0,0.42);
    opacity: 0; transition: opacity 0.28s; pointer-events: none;
    border-radius: inherit;
}
.hero3d-card.is-center:hover::after { opacity: 1; }

/* Violet glow ring on center card */
.hero3d-card.is-center {
    box-shadow:
        0 0 0 2.5px rgba(139,92,246,0.7),
        0 28px 80px rgba(0,0,0,0.8),
        0 0 120px rgba(139,92,246,0.18);
}

/* Play button (inside card, above ::after overlay) */
.hero3d-play {
    position: absolute; inset: 0; z-index: 4;
    display: flex; align-items: center; justify-content: center;
    opacity: 0; transition: opacity 0.28s; pointer-events: none;
}
.hero3d-card.is-center:hover .hero3d-play { opacity: 1; }

.hero3d-play-btn {
    width: 60px; height: 60px; border-radius: 50%;
    background: rgba(124,58,237,0.88); backdrop-filter: blur(6px);
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 0 0 10px rgba(124,58,237,0.22), 0 8px 32px rgba(0,0,0,0.5);
    transition: transform 0.2s;
}
.hero3d-card.is-center:hover .hero3d-play-btn { transform: scale(1.1); }
.hero3d-play-btn svg { width: 26px; height: 26px; margin-left: 3px; }

/* Nav arrows */
.hero3d-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 46px; height: 46px; border-radius: 50%;
    background: rgba(10,10,22,0.72); backdrop-filter: blur(14px);
    border: 1px solid rgba(255,255,255,0.1); color: rgba(255,255,255,0.85);
    display: flex; align-items: center; justify-content: center;
    z-index: 30; cursor: pointer;
    transition: background 0.22s, border-color 0.22s, transform 0.22s;
    outline: none;
}
.hero3d-arrow svg { width: 20px; height: 20px; }
.hero3d-arrow:hover {
    background: rgba(124,58,237,0.55);
    border-color: rgba(139,92,246,0.55);
    transform: translateY(-50%) scale(1.1);
}
.hero3d-arrow--prev { left: 22px; }
.hero3d-arrow--next { right: 22px; }

/* Info panel */
.hero3d-info {
    width: 100%; max-width: 680px;
    padding: 16px 28px 8px; text-align: center;
}

.hero3d-meta {
    display: flex; align-items: center; justify-content: center;
    gap: 8px; margin-bottom: 10px; min-height: 24px;
    font-size: 12px; color: #64748b;
}
.hero3d-badge {
    display: inline-flex; padding: 2px 10px; border-radius: 999px;
    font-size: 11px; font-weight: 700; letter-spacing: 0.03em;
    background: rgba(139,92,246,0.14); color: #a78bfa;
    border: 1px solid rgba(139,92,246,0.28);
}
.hero3d-badge.green {
    background: rgba(52,211,153,0.12); color: #34d399;
    border-color: rgba(52,211,153,0.25);
}
.hero3d-sep { color: #1e293b; }

.hero3d-title {
    font-size: 24px; font-weight: 900; color: #fff;
    line-height: 1.2; margin: 0 0 8px;
    transition: opacity 0.22s ease;
}
.hero3d-synopsis {
    font-size: 13px; color: #94a3b8; line-height: 1.65;
    margin: 0 0 18px;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
    transition: opacity 0.22s ease;
}

.hero3d-cta {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 11px 28px; border-radius: 999px;
    background: linear-gradient(135deg, #7c3aed 0%, #5b21b6 100%);
    color: #fff; font-size: 14px; font-weight: 700;
    text-decoration: none; letter-spacing: 0.01em;
    box-shadow: 0 6px 28px rgba(124,58,237,0.45);
    transition: transform 0.2s, box-shadow 0.2s, background 0.2s;
}
.hero3d-cta:hover {
    transform: translateY(-2px) scale(1.05);
    box-shadow: 0 10px 40px rgba(124,58,237,0.62);
    background: linear-gradient(135deg, #8b5cf6 0%, #6d28d9 100%);
    color: #fff; text-decoration: none;
}
.hero3d-cta svg { width: 18px; height: 18px; flex-shrink: 0; }

/* Dot navigation */
.hero3d-dots {
    display: flex; align-items: center; gap: 6px;
    justify-content: center; padding: 6px 0 16px;
}
.hero3d-dot {
    width: 7px; height: 7px; border-radius: 999px; border: none;
    background: rgba(255,255,255,0.2); cursor: pointer;
    transition: width 0.32s ease, background 0.32s ease;
    padding: 0;
}
.hero3d-dot.active { width: 26px; background: #8b5cf6; }

/* Progress rail */
.hero3d-rail {
    position: absolute; bottom: 0; left: 0; right: 0;
    height: 3px; background: rgba(255,255,255,0.06); z-index: 5;
}
.hero3d-prog {
    height: 100%;
    background: linear-gradient(to right, #7c3aed, #a78bfa);
    width: 0%;
}

/* ── Responsive ─────────────────────────────────────────── */
@media (max-width: 640px) {
    .hero3d-card { width: 150px; height: 225px; margin-top: -112px; margin-left: -75px; }
    .hero3d-viewport { height: 285px; }
    .hero3d-arrow { width: 38px; height: 38px; }
    .hero3d-arrow svg { width: 17px; height: 17px; }
    .hero3d-arrow--prev { left: 8px; }
    .hero3d-arrow--next { right: 8px; }
    .hero3d-title { font-size: 18px; }
    .hero3d-synopsis { font-size: 12px; -webkit-line-clamp: 2; }
    .hero3d-cta { padding: 9px 20px; font-size: 13px; }
    .hero3d-info { padding: 12px 16px 6px; }
}
@media (min-width: 641px) and (max-width: 1024px) {
    .hero3d-card { width: 180px; height: 270px; margin-top: -135px; margin-left: -90px; }
    .hero3d-viewport { height: 340px; }
}
</style>

<section id="hero3d" class="hero3d relative overflow-hidden pt-[60px]">

    {{-- Cinematic ambient background --}}
    <div id="hero3d-bg" class="hero3d-bg"></div>
    <div class="hero3d-vignette"></div>

    <div class="hero3d-inner">

        {{-- ── 3D Viewport ──────────────────────────────────────────── --}}
        <div id="hero3d-viewport" class="hero3d-viewport">

            <div class="hero3d-track">
                @foreach($sliderShows as $i => $show)
                <article class="hero3d-card"
                         data-index="{{ $i }}"
                         data-href="{{ route('shows.show', $show->slug) }}"
                         data-title="{{ $show->title }}"
                         data-year="{{ $show->year }}"
                         data-status="{{ $show->status }}"
                         data-network="{{ $show->network ?? '' }}"
                         data-synopsis="{{ Str::limit(strip_tags($show->synopsis ?? ''), 200) }}"
                         data-poster="{{ $show->poster_url }}">
                    <img src="{{ $show->poster_url }}" alt="{{ $show->title }}">
                    <div class="hero3d-play">
                        <div class="hero3d-play-btn">
                            <svg viewBox="0 0 24 24" fill="white"><path d="M8 5v14l11-7z"/></svg>
                        </div>
                    </div>
                </article>
                @endforeach
            </div>

            {{-- Arrows --}}
            <button id="hero3d-prev" class="hero3d-arrow hero3d-arrow--prev" aria-label="Previous">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <button id="hero3d-next" class="hero3d-arrow hero3d-arrow--next" aria-label="Next">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 6 15 12 9 18"/>
                </svg>
            </button>
        </div>

        {{-- ── Info Panel ───────────────────────────────────────────── --}}
        <div class="hero3d-info">
            <div id="hero3d-meta" class="hero3d-meta"></div>
            <h2 id="hero3d-title" class="hero3d-title"></h2>
            <p id="hero3d-synopsis" class="hero3d-synopsis"></p>
            <a id="hero3d-cta" class="hero3d-cta" href="#">
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                Watch Now
            </a>
        </div>

        {{-- ── Dots ─────────────────────────────────────────────────── --}}
        <div id="hero3d-dots" class="hero3d-dots">
            @foreach($sliderShows as $i => $show)
            <button class="hero3d-dot{{ $i === 0 ? ' active' : '' }}"
                    data-goto="{{ $i }}" aria-label="Go to slide {{ $i + 1 }}"></button>
            @endforeach
        </div>

    </div>

    {{-- Progress rail --}}
    <div class="hero3d-rail">
        <div id="hero3d-prog" class="hero3d-prog"></div>
    </div>
</section>
{{-- ═══════════════════════════════════════════════════════════════════════════ --}}

{{-- ══════════════════════ TWO-COLUMN WRAPPER STARTS ══════════════════════ --}}
<div class="bg-[#080810]">
<div class="max-w-[1600px] mx-auto px-4 sm:px-6 grid grid-cols-12 gap-x-6 items-start">

{{-- ─────────────────────── MAIN CONTENT (left) ─────────────────────── --}}
<div class="col-span-12 xl:col-span-9 min-w-0">

{{-- ═══════════════════════════════════ TOP 10 TODAY ═══════════ --}}
<section class="py-6">

        {{-- Section heading --}}
        <div class="mb-4">
            <h2 class="section-title">
                Top 10 Today
            </h2>
        </div>

        {{-- Swiper carousel --}}
        <div class="swiper top10-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($top10Shows as $rank => $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">

                        {{-- Poster --}}
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}"
                                 alt="{{ $show->title }}"
                                 class="w-full h-full object-cover">
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
            <h2 class="section-title">
                Recently Added
            </h2>
        </div>
        <div class="swiper recently-added-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($recentlyAdded as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Classic Turkish Dramas</h2>
        </div>
        <div class="swiper classic-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($classicDramas as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">For Dizi Newcomers</h2>
        </div>
        <div class="swiper dizi-newcomers-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($diziNewcomers as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Period Dramas</h2>
        </div>
        <div class="swiper period-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($periodDramas as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Streaming on Netflix</h2>
        </div>
        <div class="swiper netflix-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($netflixShows as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Love Is In The Air</h2>
        </div>
        <div class="swiper love-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($loveShows as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Turkish Remakes</h2>
        </div>
        <div class="swiper turkish-remakes-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($turkishRemakes as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Impossible Love Stories</h2>
        </div>
        <div class="swiper impossible-love-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($impossibleLove as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Turkish Daily Dramas</h2>
        </div>
        <div class="swiper daily-dramas-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($dailyDramas as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Enemies To Lovers</h2>
        </div>
        <div class="swiper enemies-lovers-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($enemiesToLovers as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Family Tree</h2>
        </div>
        <div class="swiper family-tree-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($familyTree as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Binge-Worthy</h2>
        </div>
        <div class="swiper binge-worthy-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($bingeWorthy as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Watch In One Weekend</h2>
        </div>
        <div class="swiper one-weekend-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($oneWeekend as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
            <h2 class="section-title">Gone Too Soon</h2>
        </div>
        <div class="swiper gone-too-soon-swiper pb-8">
            <div class="swiper-wrapper">
                @foreach($goneTooSoon as $show)
                <div class="swiper-slide !w-[155px] sm:!w-[185px]">
                    <a href="{{ route('shows.show', $show->slug) }}" class="relative block group">
                        <div class="poster-wrap aspect-[2/3]">
                            <img src="{{ $show->poster_url }}" alt="{{ $show->title }}" class="w-full h-full object-cover">
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
<aside class="hidden xl:block xl:col-span-3 py-6 sticky top-[76px] max-h-[calc(100vh-80px)] overflow-y-auto">

        {{-- Most Popular --}}
        <div class="bg-[#0d0d18] rounded-xl overflow-hidden border border-white/5">
            <div class="px-4 py-3 border-b border-white/5 flex items-center gap-2">
                <span class="w-1 h-4 bg-[#e63946] rounded-full inline-block"></span>
                <h3 class="text-white text-xs font-black uppercase tracking-widest">Most Popular</h3>
            </div>
            <div class="divide-y divide-white/5">
                @foreach($sidebarShows as $i => $show)
                <a href="{{ route('shows.show', $show->slug) }}"
                   class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/[0.04] transition-colors group">
                    <span class="text-slate-600 text-xs font-bold w-5 shrink-0 text-center group-hover:text-slate-400 transition-colors">
                        {{ $i + 1 }}
                    </span>
                    <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-[#13131f]">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover">
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
                <a href="{{ route('shows.index') }}"
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
                <a href="{{ route('shows.show', $show->slug) }}"
                   class="flex items-center gap-3 px-3 py-2.5 hover:bg-white/[0.04] transition-colors group">
                    <span class="text-slate-600 text-xs font-bold w-5 shrink-0 text-center group-hover:text-slate-400 transition-colors">
                        {{ $i + 1 }}
                    </span>
                    <div class="w-10 h-14 shrink-0 rounded overflow-hidden bg-[#13131f]">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover">
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
                <a href="{{ route('shows.index', ['status' => 'Running']) }}"
                   class="block text-center text-xs font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">
                    See All Airing Shows →
                </a>
            </div>
        </div>

</aside>

</div>{{-- END grid row --}}
</div>{{-- END bg wrapper --}}

@endsection

@push('scripts')
<script>
/* ── DiziCentral 3D Hero Slider ─────────────────────────────────────────────── */
(function () {
    'use strict';

    const cards    = [...document.querySelectorAll('.hero3d-card')];
    const dots     = [...document.querySelectorAll('.hero3d-dot')];
    const bgEl     = document.getElementById('hero3d-bg');
    const titleEl  = document.getElementById('hero3d-title');
    const synEl    = document.getElementById('hero3d-synopsis');
    const metaEl   = document.getElementById('hero3d-meta');
    const ctaEl    = document.getElementById('hero3d-cta');
    const viewport = document.getElementById('hero3d-viewport');
    const progEl   = document.getElementById('hero3d-prog');

    const N        = cards.length;
    const AUTOPLAY = 5200;
    const VISIBLE  = 2;
    let   current  = 0;
    let   autoTimer;

    /* ── helpers ─────────────────────────────────────────── */
    function mod(n, m) { return ((n % m) + m) % m; }

    function offset(i) {
        let d = i - current;
        const half = Math.floor(N / 2);
        if (d >  half) d -= N;
        if (d < -half) d += N;
        return d;
    }

    function cfg() {
        const w = window.innerWidth;
        if (w < 641)  return { gap: 188, rot: 36, scales: [1, 0.76, 0.55] };
        if (w < 1025) return { gap: 232, rot: 38, scales: [1, 0.78, 0.57] };
        return               { gap: 272, rot: 40, scales: [1, 0.78, 0.57] };
    }

    /* ── render card positions ───────────────────────────── */
    function render() {
        const { gap, rot, scales } = cfg();
        cards.forEach((card, i) => {
            const p   = offset(i);
            const abs = Math.abs(p);

            if (abs > VISIBLE) {
                card.style.opacity       = '0';
                card.style.pointerEvents = 'none';
                card.style.zIndex        = '0';
                card.classList.remove('is-center');
                return;
            }

            const tx = p * gap;
            const ry = -p * rot;
            const sc = scales[abs] ?? 0.38;
            const op = abs === 0 ? 1 : abs === 1 ? 0.70 : 0.40;
            const zi = 10 - abs * 3;

            card.style.transform      = `perspective(1600px) translateX(${tx}px) rotateY(${ry}deg) scale(${sc})`;
            card.style.opacity        = String(op);
            card.style.zIndex         = String(zi);
            card.style.pointerEvents  = p === 0 ? 'auto' : 'none';
            card.classList.toggle('is-center', p === 0);
        });
    }

    /* ── update info panel ───────────────────────────────── */
    function updateInfo(instant) {
        const d = cards[current].dataset;

        if (!instant) {
            titleEl.style.opacity = '0';
            synEl.style.opacity   = '0';
        }

        bgEl.style.backgroundImage = `url('${d.poster}')`;

        const delay = instant ? 0 : 230;
        setTimeout(() => {
            titleEl.textContent = d.title;
            synEl.textContent   = d.synopsis;
            ctaEl.href          = d.href;

            const parts = [];
            if (d.year)    parts.push(`<span>${d.year}</span>`);
            if (d.status) {
                const label = d.status === 'Running' ? 'Airing' : d.status;
                const cls   = d.status === 'Running' ? 'green' : '';
                parts.push(`<span class="hero3d-badge ${cls}">${label}</span>`);
            }
            if (d.network) parts.push(`<span>${d.network}</span>`);
            metaEl.innerHTML = parts.join('<span class="hero3d-sep"> · </span>');

            if (!instant) {
                titleEl.style.opacity = '1';
                synEl.style.opacity   = '1';
            }
        }, delay);

        dots.forEach((dot, i) => dot.classList.toggle('active', i === current));
    }

    /* ── navigation ──────────────────────────────────────── */
    function goTo(n) {
        current = mod(n, N);
        render();
        updateInfo(false);
        resetAutoplay();
    }

    /* ── autoplay + progress bar ─────────────────────────── */
    function resetAutoplay() {
        clearInterval(autoTimer);

        if (progEl) {
            progEl.style.transition = 'none';
            progEl.style.width      = '0%';
            requestAnimationFrame(() => requestAnimationFrame(() => {
                progEl.style.transition = `width ${AUTOPLAY}ms linear`;
                progEl.style.width      = '100%';
            }));
        }

        autoTimer = setInterval(() => goTo(current + 1), AUTOPLAY);
    }

    function pauseAutoplay() {
        clearInterval(autoTimer);
        if (progEl) {
            progEl.style.transition = 'none';
        }
    }

    /* ── pointer drag / touch swipe ─────────────────────── */
    const drag = { on: false, x0: 0, dx: 0 };

    viewport.addEventListener('pointerdown', e => {
        if (e.target.closest('.hero3d-arrow')) return;
        drag.on = true; drag.x0 = e.clientX; drag.dx = 0;
        viewport.setPointerCapture(e.pointerId);
        viewport.classList.add('grabbing');
        pauseAutoplay();
    });
    viewport.addEventListener('pointermove', e => {
        if (drag.on) drag.dx = e.clientX - drag.x0;
    });
    function endDrag() {
        if (!drag.on) return;
        drag.on = false;
        viewport.classList.remove('grabbing');
        if (Math.abs(drag.dx) > 50) {
            goTo(drag.dx < 0 ? current + 1 : current - 1);
        } else {
            resetAutoplay();
        }
    }
    viewport.addEventListener('pointerup',     endDrag);
    viewport.addEventListener('pointercancel', endDrag);

    /* ── click on side card → navigate ──────────────────── */
    cards.forEach((card, i) => {
        card.addEventListener('click', e => {
            if (offset(i) === 0) return;   // center: let href navigate
            e.preventDefault();
            goTo(i);
        });
    });

    /* ── dot / arrow / keyboard ──────────────────────────── */
    dots.forEach(dot => dot.addEventListener('click', () => goTo(+dot.dataset.goto)));

    document.getElementById('hero3d-prev')
        ?.addEventListener('click', () => goTo(current - 1));
    document.getElementById('hero3d-next')
        ?.addEventListener('click', () => goTo(current + 1));

    document.addEventListener('keydown', e => {
        if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
        if (e.key === 'ArrowLeft')  goTo(current - 1);
        if (e.key === 'ArrowRight') goTo(current + 1);
    });

    /* ── pause on hover ──────────────────────────────────── */
    viewport.addEventListener('mouseenter', pauseAutoplay);
    viewport.addEventListener('mouseleave', resetAutoplay);

    /* ── resize ──────────────────────────────────────────── */
    window.addEventListener('resize', render, { passive: true });

    /* ── init ────────────────────────────────────────────── */
    render();
    updateInfo(true);
    resetAutoplay();
})();
</script>
@endpush
