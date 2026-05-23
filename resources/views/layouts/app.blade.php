<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName    = setting('site_name', 'DiziBul');
        $titleFormat = setting('seo_title_format', '{title} — ' . $siteName);

        // @section() HTML-encodes values; decode before use to avoid double-escaping via {{ }}
        $dec = fn($s) => html_entity_decode(trim((string) $s), ENT_QUOTES, 'UTF-8');

        // Views yield 'seo_title' with just the raw name → format applied
        // Views yield 'title' with a fully-formatted string → used directly
        $rawSeoTitle = $dec(View::yieldContent('seo_title'));
        $finalTitle  = $rawSeoTitle
            ? str_replace('{title}', $rawSeoTitle, $titleFormat)
            : ($dec(View::yieldContent('title')) ?: $siteName . ' — Turkish Drama Hub');

        $defaultDesc = setting('seo_default_description', 'Discover the best Turkish TV series and dramas.');
        $metaDesc    = $dec(View::yieldContent('meta_description') ?: View::yieldContent('description')) ?: $defaultDesc;

        $canonical   = $dec(View::yieldContent('canonical')) ?: url()->current();
        $globalNoindex = setting('robots_noindex', 'index') === 'noindex';
        $pageNoindex   = trim(View::yieldContent('noindex')) === '1';
        $robotsValue   = ($globalNoindex || $pageNoindex) ? 'noindex, follow' : 'index, follow';
    @endphp

    <title>{{ $finalTitle }}</title>
    <meta name="description" content="{{ $metaDesc }}">
    <meta name="robots" content="{{ $robotsValue }}">
    <link rel="canonical" href="{{ $canonical }}">

    {{-- Open Graph --}}
    <meta property="og:type"        content="website">
    <meta property="og:title"       content="{{ $finalTitle }}">
    <meta property="og:description" content="{{ $metaDesc }}">
    <meta property="og:url"         content="{{ $canonical }}">
    <meta property="og:site_name"   content="{{ $siteName }}">
    @hasSection('og_image')
        <meta property="og:image" content="@yield('og_image')">
    @elseif(setting('og_image'))
        <meta property="og:image" content="{{ asset('storage/' . setting('og_image')) }}">
    @endif

    {{-- Twitter Card --}}
    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $finalTitle }}">
    <meta name="twitter:description" content="{{ $metaDesc }}">

    @if(setting('search_console_verify'))
    <meta name="google-site-verification" content="{{ setting('search_console_verify') }}">
    @endif

    @if(setting('favicon'))
    <link rel="icon" href="{{ asset('storage/' . setting('favicon')) }}">
    @endif

    @if(setting('google_tag_manager_id'))
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','{{ setting('google_tag_manager_id') }}');</script>
    @endif

    @if(setting('google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ setting('google_analytics_id') }}"></script>
    <script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js',new Date());gtag('config','{{ setting('google_analytics_id') }}');</script>
    @endif

    @yield('json_ld')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080810] text-white antialiased">

@if(setting('google_tag_manager_id'))
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('google_tag_manager_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif

{{-- ═══════════════════════════════════════════ NAVBAR ═══ --}}
<nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-[#0d0d18] border-b border-white/5">

    {{-- Main bar --}}
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6">
        <div class="flex items-center h-[60px] gap-8">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 shrink-0 mr-2">
                @if(setting('logo'))
                    <img src="{{ asset('storage/' . setting('logo')) }}"
                         alt="{{ setting('site_name', 'DiziBul') }}"
                         class="h-8 w-auto object-contain">
                @else
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                    </div>
                    <span class="text-[15px] font-bold tracking-tight gradient-text">{{ setting('site_name', 'DiziBul') }}</span>
                @endif
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">

                <a href="{{ url('/') }}" class="nav-link px-3 py-2 rounded-md">Home</a>

                {{-- Browse trigger --}}
                <button id="browse-trigger"
                        class="nav-link px-3 py-2 rounded-md flex items-center gap-1.5 group"
                        aria-haspopup="true" aria-expanded="false">
                    Browse
                    <svg id="browse-chevron" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <a href="{{ route('shows.index', ['sort' => 'newest']) }}" class="nav-link px-3 py-2 rounded-md">New Episodes</a>
                <a href="{{ route('shows.index', ['sort' => 'rating']) }}" class="nav-link px-3 py-2 rounded-md">Top Rated</a>

            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2 ml-auto">
                {{-- Search --}}
                <a href="{{ route('shows.index') }}" class="p-2 text-slate-400 hover:text-white transition-colors rounded-md hover:bg-white/5" aria-label="Search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Sign In --}}
                <a href="{{ setting('signin_url', '#') }}" target="_blank" class="hidden md:inline-flex items-center gap-2 px-4 py-1.5 rounded-md border border-violet-500/60 text-violet-300 text-sm font-semibold hover:bg-violet-500/10 hover:border-violet-400 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Sign In
                </a>

                {{-- Mobile hamburger --}}
                <button id="mobile-menu-btn" class="md:hidden p-2 text-slate-400 hover:text-white transition-colors" aria-label="Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════ MEGA MENU ════════════════════ --}}
    <div id="mega-menu"
         class="mega-menu-panel absolute top-full inset-x-0 border-t border-white/5 hidden">
        <div class="bg-[#0d0d18] shadow-2xl shadow-black/60" style="border-top:1px solid rgba(255,255,255,0.05);">
            <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-8">
                <div class="grid grid-cols-12 gap-8">

                    {{-- Column 1 · TV Shows --}}
                    <div class="col-span-12 md:col-span-3 border-r border-white/5 pr-8">
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-4">TV Shows</p>
                        <ul class="space-y-1">
                            @foreach([
                                ['label' => 'Trending TV Shows',    'href' => route('shows.index', ['sort' => 'subscribers'])],
                                ['label' => 'Most Popular',          'href' => route('shows.index', ['sort' => 'rating'])],
                                ['label' => 'Top Airing Now',        'href' => route('shows.index', ['status' => 'Running'])],
                                ['label' => 'Latest Dizi News',      'href' => route('shows.index', ['sort' => 'newest'])],
                                ['label' => 'New Episodes',          'href' => route('shows.index', ['sort' => 'newest'])],
                                ['label' => 'Upcoming Shows',        'href' => route('shows.index')],
                                ['label' => 'Cancelled Shows',       'href' => route('shows.index', ['status' => 'Cancelled'])],
                                ['label' => 'Activity Feed',         'href' => route('shows.index')],
                            ] as $item)
                            <li>
                                <a href="{{ $item['href'] }}"
                                   class="flex items-center gap-2 py-1.5 text-sm text-slate-400 hover:text-white transition-colors group">
                                    <span class="w-1 h-1 rounded-full bg-violet-500/0 group-hover:bg-violet-500 transition-all"></span>
                                    {{ $item['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        <a href="{{ route('shows.index') }}"
                           class="mt-6 inline-flex items-center gap-2 px-5 py-2 rounded-md bg-violet-600 hover:bg-violet-500 text-white text-xs font-bold uppercase tracking-wider transition-all duration-200 hover:shadow-lg hover:shadow-violet-500/30">
                            Explore Shows
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Column 2 · Genres --}}
                    <div class="col-span-12 md:col-span-6 border-r border-white/5 pr-8">
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-4">Genres</p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-1">
                            @foreach($navGenres as $genre)
                            <a href="{{ route('shows.index', ['genre' => $genre->slug]) }}"
                               class="flex items-center gap-2 py-1.5 text-sm text-slate-400 hover:text-white transition-colors group truncate">
                                <span class="w-1 h-1 rounded-full bg-violet-500/0 group-hover:bg-violet-500 transition-all shrink-0"></span>
                                {{ $genre->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Column 3 · Quick Links + Social --}}
                    <div class="col-span-12 md:col-span-3">
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-4">Quick Links</p>
                        <ul class="space-y-1 mb-8">
                            @foreach([
                                ['label' => 'New Episodes',    'href' => route('shows.index', ['sort' => 'newest'])],
                                ['label' => 'Top Rated',       'href' => route('shows.index', ['sort' => 'rating'])],
                                ['label' => 'Airing Now',      'href' => route('shows.index', ['status' => 'Running'])],
                                ['label' => 'Contact Us',      'href' => route('contact')],
                            ] as $link)
                            <li>
                                <a href="{{ $link['href'] }}"
                                   class="flex items-center gap-2 py-1.5 text-sm text-slate-400 hover:text-white transition-colors group">
                                    <span class="w-1 h-1 rounded-full bg-violet-500/0 group-hover:bg-violet-500 transition-all"></span>
                                    {{ $link['label'] }}
                                </a>
                            </li>
                            @endforeach
                        </ul>

                        {{-- Social icons --}}
                        @php $navSocialLinks = \App\Models\SocialLink::active()->ordered()->get(); @endphp
                        @if($navSocialLinks->isNotEmpty())
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-3">Follow Us</p>
                        <div class="flex gap-2.5">
                            @foreach($navSocialLinks as $sl)
                            <a href="{{ $sl->url }}" target="_blank" rel="noopener" aria-label="{{ $sl->label }}"
                               class="w-8 h-8 rounded-full flex items-center justify-center bg-white/10 hover:bg-white/20 transition-all duration-200 text-slate-300 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">{!! $sl->svgIcon() !!}</svg>
                            </a>
                            @endforeach
                        </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden bg-[#0d0d18] border-t border-white/5 overflow-y-auto" style="max-height: calc(100dvh - 60px)">
        <div class="px-4 py-4 space-y-1">
            <a href="{{ url('/') }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Home</a>
            <a href="{{ route('shows.index') }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Browse Shows</a>
            <a href="{{ route('shows.index', ['sort' => 'newest']) }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">New Episodes</a>
            <a href="{{ route('shows.index', ['sort' => 'rating']) }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Top Rated</a>
            <a href="{{ route('shows.index', ['status' => 'Running']) }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Airing Now</a>
            <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Contact Us</a>
            <div class="pt-2 border-t border-white/5 mt-2">
                <p class="px-3 py-1 text-[10px] font-bold tracking-widest text-violet-400 uppercase">Genres</p>
                @foreach($navGenres->take(12) as $genre)
                <a href="{{ route('shows.index', ['genre' => $genre->slug]) }}" class="block px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 text-sm transition-all">{{ $genre->name }}</a>
                @endforeach
            </div>
        </div>
    </div>

</nav>
{{-- ════════════════════════════════════════════════════════════════ --}}

{{-- Main content --}}
<main>
    @yield('content')
</main>

{{-- ═══════════════════════════════════════════ FOOTER ═══ --}}
<footer class="border-t border-white/5 mt-24 bg-[#0a0a15]">

    {{-- Main grid --}}
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            {{-- Col 1 · Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                {{-- Logo --}}
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2.5 mb-3">
                    @if(setting('logo'))
                        <img src="{{ asset('storage/' . setting('logo')) }}"
                             alt="{{ setting('site_name', 'DiziBul') }}"
                             class="h-8 w-auto object-contain">
                    @else
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/25">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold tracking-tight gradient-text">{{ setting('site_name', 'DiziBul') }}</span>
                    @endif
                </a>
                <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                    {{ setting('site_tagline', "Turkish Dramas? We've got the çay! ☕") }}
                </p>

                {{-- Social icons --}}
                @php $footerSocialLinks = \App\Models\SocialLink::active()->ordered()->get(); @endphp
                @if($footerSocialLinks->isNotEmpty())
                <div class="flex items-center gap-3 mb-8">
                    @foreach($footerSocialLinks as $sl)
                    <a href="{{ $sl->url }}" target="_blank" rel="noopener" aria-label="{{ $sl->label }}"
                       class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-white/30 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">{!! $sl->svgIcon() !!}</svg>
                    </a>
                    @endforeach
                </div>
                @endif

                {{-- App store buttons (only shown when URL is set) --}}
                @if(setting('appstore_url') || setting('playstore_url'))
                <div class="flex flex-wrap gap-3">
                    @if(setting('appstore_url'))
                    <a href="{{ setting('appstore_url') }}"
                       class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <svg class="w-5 h-5 text-white shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-[9px] text-slate-400 leading-none uppercase tracking-wide">Download on the</div>
                            <div class="text-[13px] font-semibold text-white leading-tight">App Store</div>
                        </div>
                    </a>
                    @endif
                    @if(setting('playstore_url'))
                    <a href="{{ setting('playstore_url') }}"
                       class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-200">
                        <svg class="w-5 h-5 shrink-0" viewBox="0 0 24 24" fill="none">
                            <path d="M3.18 23.76c.3.17.64.24.99.21L15.66 12 12 8.34 3.18 23.76z" fill="#EA4335"/>
                            <path d="M20.47 10.2L17.3 8.37 13.34 12l3.96 3.96 3.17-1.83a2.02 2.02 0 000-3.93z" fill="#FBBC04"/>
                            <path d="M3.18.24A2 2 0 002 2.02v19.96c0 .74.4 1.38 1.18 1.78L15.66 12 3.18.24z" fill="#4285F4"/>
                            <path d="M4.17.03L16.34 8.37 12 12 4.17.03z" fill="#34A853"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-[9px] text-slate-400 leading-none uppercase tracking-wide">Get it on</div>
                            <div class="text-[13px] font-semibold text-white leading-tight">Google Play</div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Col 2 · About --}}
            <div>
                <h4 class="text-[11px] font-bold tracking-[0.14em] text-white uppercase mb-5">About</h4>
                <ul class="space-y-3">
                    @foreach([
//                        ['About Us',       '#'],
                        ['FAQ',            route('faq')],
                        ['Contact Us',     route('contact')],
                        ['Terms of Use',   route('terms')],
                        ['Privacy Policy', route('privacy')],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" class="text-sm text-slate-500 hover:text-white transition-colors duration-150">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 3 · What to Watch --}}
            <div>
                <h4 class="text-[11px] font-bold tracking-[0.14em] text-white uppercase mb-5">What to Watch</h4>
                <ul class="space-y-3">
                    @foreach([
                        ['Trending TV Shows',     route('shows.index', ['sort' => 'subscribers'])],
                        ['Most Popular TV Shows',  route('shows.index', ['sort' => 'rating'])],
                        ['Newest TV Shows',        route('shows.index', ['sort' => 'newest'])],
                        ['Upcoming TV Shows',      route('shows.index')],
                    ] as [$label, $href])
                    <li>
                        <a href="{{ $href }}" class="text-sm text-slate-500 hover:text-white transition-colors duration-150">
                            {{ $label }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Col 4 · Quick Links + Work With Us --}}
            <div>
{{--                <h4 class="text-[11px] font-bold tracking-[0.14em] text-white uppercase mb-5">Quick Links</h4>--}}
{{--                <ul class="space-y-3 mb-8">--}}
{{--                    @foreach([--}}
{{--                        ['Dizi Calendar',       '#'],--}}
{{--                        ['Daily TV Ratings',    '#'],--}}
{{--                        ['Cancellation Buzz',   '#'],--}}
{{--                        ['Upcoming Birthdays',  '#'],--}}
{{--                    ] as [$label, $href])--}}
{{--                    <li>--}}
{{--                        <a href="{{ $href }}" class="text-sm text-slate-500 hover:text-white transition-colors duration-150">--}}
{{--                            {{ $label }}--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    @endforeach--}}
{{--                </ul>--}}

                <h4 class="text-[11px] font-bold tracking-[0.14em] text-white uppercase mb-5">Work With Us</h4>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ url('/contact') }}" class="text-sm text-slate-500 hover:text-white transition-colors duration-150">Advertise</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/5">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-slate-600 text-sm">
                © {{ date('Y') }} <span class="font-semibold text-slate-500">{{ setting('site_name', 'DiziBul') }}</span>. {{ setting('footer_copyright', 'All Rights Reserved.') }}
            </p>
            <p class="text-slate-600 text-sm flex items-center gap-1.5">
                {!! setting('footer_tagline', 'Made with <span class="text-red-500">♥</span> for Turkish drama fans') !!}
            </p>
        </div>
    </div>

</footer>
{{-- ════════════════════════════════════════════════════════════════ --}}

@stack('scripts')

<script>
    // ── Mobile menu ─────────────────────────────────────────────────
    (function () {
        const btn  = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        if (!btn || !menu) return;
        btn.addEventListener('click', () => {
            const isOpen = menu.style.display === 'block';
            menu.style.display = isOpen ? 'none' : 'block';
        });
    })();

    // ── Mega menu (desktop hover) ───────────────────────────────────
    const trigger  = document.getElementById('browse-trigger');
    const megaMenu = document.getElementById('mega-menu');
    const chevron  = document.getElementById('browse-chevron');
    const navbar   = document.getElementById('navbar');

    let closeTimer = null;

    function openMega() {
        clearTimeout(closeTimer);
        megaMenu.classList.remove('hidden', 'mega-closing');
        megaMenu.classList.add('mega-open');
        chevron.style.transform = 'rotate(180deg)';
        trigger.setAttribute('aria-expanded', 'true');
    }

    function closeMega() {
        closeTimer = setTimeout(() => {
            megaMenu.classList.remove('mega-open');
            megaMenu.classList.add('mega-closing');
            chevron.style.transform = '';
            trigger.setAttribute('aria-expanded', 'false');
            setTimeout(() => {
                megaMenu.classList.add('hidden');
                megaMenu.classList.remove('mega-closing');
            }, 200);
        }, 80);
    }

    trigger?.addEventListener('mouseenter', openMega);
    trigger?.addEventListener('focus', openMega);
    megaMenu?.addEventListener('mouseenter', () => clearTimeout(closeTimer));
    megaMenu?.addEventListener('mouseleave', closeMega);
    trigger?.addEventListener('mouseleave', closeMega);
    trigger?.addEventListener('blur', closeMega);

    // ── Scroll reveal ───────────────────────────────────────────────
    const revealObs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); revealObs.unobserve(e.target); } });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    const staggerObs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                Array.from(e.target.children).forEach((c, i) => setTimeout(() => c.classList.add('visible'), i * 75));
                staggerObs.unobserve(e.target);
            }
        });
    }, { threshold: 0.05, rootMargin: '0px 0px -30px 0px' });

    document.querySelectorAll('.reveal').forEach(el => revealObs.observe(el));
    document.querySelectorAll('.stagger').forEach(el => {
        Array.from(el.children).forEach(c => c.classList.add('stagger-item'));
        staggerObs.observe(el);
    });
</script>

</body>
</html>
