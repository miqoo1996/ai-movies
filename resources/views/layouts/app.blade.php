<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DiziBul — Turkish Drama Hub')</title>
    <meta name="description" content="@yield('description', 'Discover the best Turkish TV series and dramas.')">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#080810] text-white antialiased">

{{-- ═══════════════════════════════════════════ NAVBAR ═══ --}}
<nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-[#0d0d18] border-b border-white/5">

    {{-- Main bar --}}
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6">
        <div class="flex items-center h-[60px] gap-8">

            {{-- Logo --}}
            <a href="/" class="flex items-center gap-2.5 shrink-0 mr-2">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/30">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/>
                    </svg>
                </div>
                <span class="text-[15px] font-bold tracking-tight gradient-text">DiziBul</span>
            </a>

            {{-- Desktop nav links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">

                <a href="/" class="nav-link px-3 py-2 rounded-md">Home</a>

                {{-- Browse trigger --}}
                <button id="browse-trigger"
                        class="nav-link px-3 py-2 rounded-md flex items-center gap-1.5 group"
                        aria-haspopup="true" aria-expanded="false">
                    Browse
                    <svg id="browse-chevron" class="w-3.5 h-3.5 text-slate-500 transition-transform duration-200 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

{{--                <a href="/calendar" class="nav-link px-3 py-2 rounded-md">Calendar</a>--}}

            </div>

            {{-- Right side --}}
            <div class="flex items-center gap-2 ml-auto">
                {{-- Search --}}
                <a href="/shows" class="p-2 text-slate-400 hover:text-white transition-colors rounded-md hover:bg-white/5" aria-label="Search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Sign In --}}
                <a href="https://turk-flix.com/" target="_blank" class="hidden md:inline-flex items-center gap-2 px-4 py-1.5 rounded-md border border-violet-500/60 text-violet-300 text-sm font-semibold hover:bg-violet-500/10 hover:border-violet-400 transition-all duration-200">
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
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-8">
                <div class="grid grid-cols-12 gap-8">

                    {{-- Column 1 · TV Shows --}}
                    <div class="col-span-12 md:col-span-3 border-r border-white/5 pr-8">
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-4">TV Shows</p>
                        <ul class="space-y-1">
                            @foreach([
                                ['label' => 'Trending TV Shows',    'href' => '/shows?sort=subscribers'],
                                ['label' => 'Most Popular',          'href' => '/shows?sort=rating'],
                                ['label' => 'Top Airing Now',        'href' => '/shows?status=Continuing'],
                                ['label' => 'Latest Dizi News',      'href' => '/shows?sort=newest'],
                                ['label' => 'New Episodes',          'href' => '/shows?sort=newest'],
                                ['label' => 'Upcoming Shows',        'href' => '/shows'],
                                ['label' => 'Cancelled Shows',       'href' => '/shows?status=Ended'],
                                ['label' => 'Activity Feed',         'href' => '/shows'],
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
                        <a href="/shows"
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
                            <a href="/shows?genre={{ $genre->slug }}"
                               class="flex items-center gap-2 py-1.5 text-sm text-slate-400 hover:text-white transition-colors group truncate">
                                <span class="w-1 h-1 rounded-full bg-violet-500/0 group-hover:bg-violet-500 transition-all shrink-0"></span>
                                {{ $genre->name }}
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Column 3 · Quick Links + Social --}}
                    <div class="col-span-12 md:col-span-3">
{{--                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-4">Quick Links</p>--}}
{{--                        <ul class="space-y-1 mb-8">--}}
{{--                            @foreach([--}}
{{--                                ['label' => 'Upcoming Birthdays', 'href' => '#'],--}}
{{--                                ['label' => 'About DiziBul',      'href' => '#'],--}}
{{--                                ['label' => 'FAQ',                'href' => '/faq'],--}}
{{--                                ['label' => 'Contact Us',         'href' => '/contact'],--}}
{{--                                ['label' => 'Terms of Use',       'href' => '/terms'],--}}
{{--                                ['label' => 'Privacy Policy',     'href' => '/privacy'],--}}
{{--                            ] as $link)--}}
{{--                            <li>--}}
{{--                                <a href="{{ $link['href'] }}"--}}
{{--                                   class="flex items-center gap-2 py-1.5 text-sm text-slate-400 hover:text-white transition-colors group">--}}
{{--                                    <span class="w-1 h-1 rounded-full bg-violet-500/0 group-hover:bg-violet-500 transition-all"></span>--}}
{{--                                    {{ $link['label'] }}--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}

                        {{-- Social icons --}}
                        <p class="text-[10px] font-bold tracking-[0.15em] text-violet-400 uppercase mb-3">Follow Us</p>
                        <div class="flex gap-2.5">
                            {{-- Facebook --}}
                            <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center bg-[#1877F2]/20 hover:bg-[#1877F2] transition-all duration-200 text-[#1877F2] hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                            </a>
                            {{-- X / Twitter --}}
                            <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center bg-white/10 hover:bg-white/20 transition-all duration-200 text-slate-300 hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            {{-- Instagram --}}
                            <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center bg-[#E1306C]/20 hover:bg-gradient-to-tr hover:from-[#f09433] hover:via-[#e6683c] hover:to-[#dc2743] transition-all duration-200 text-[#E1306C] hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            {{-- YouTube --}}
                            <a href="#" class="w-8 h-8 rounded-full flex items-center justify-center bg-[#FF0000]/20 hover:bg-[#FF0000] transition-all duration-200 text-[#FF0000] hover:text-white">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-[#0d0d18] border-t border-white/5">
        <div class="px-4 py-4 space-y-1">
            <a href="/" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Home</a>
            <a href="/shows" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Browse Shows</a>
            <a href="/shows?sort=rating" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">Top Rated</a>
            <a href="/shows?sort=newest" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">New</a>
            <a href="/faq" class="block px-3 py-2.5 rounded-lg text-slate-300 hover:text-white hover:bg-white/5 text-sm transition-all">FAQ</a>
            <div class="pt-2 border-t border-white/5 mt-2">
                @foreach($navGenres->take(10) as $genre)
                <a href="/shows?genre={{ $genre->slug }}" class="block px-3 py-2 rounded-lg text-slate-400 hover:text-white hover:bg-white/5 text-sm transition-all">{{ $genre->name }}</a>
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
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">

            {{-- Col 1 · Brand --}}
            <div class="sm:col-span-2 lg:col-span-1">
                {{-- Logo --}}
                <a href="/" class="inline-flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-500/25">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight gradient-text">DiziBul</span>
                </a>
                <p class="text-slate-500 text-sm mb-6 leading-relaxed">
                    Turkish Dramas? We've got the çay! ☕
                </p>

                {{-- Social icons --}}
                <div class="flex items-center gap-3 mb-8">
                    {{-- X --}}
                    <a href="#" aria-label="X / Twitter"
                       class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-white/30 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                    </a>
                    {{-- Instagram --}}
                    <a href="#" aria-label="Instagram"
                       class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-white/30 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                        </svg>
                    </a>
                    {{-- Facebook --}}
                    <a href="#" aria-label="Facebook"
                       class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-white/30 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                        </svg>
                    </a>
                    {{-- YouTube --}}
                    <a href="#" aria-label="YouTube"
                       class="w-8 h-8 rounded-full border border-white/10 flex items-center justify-center text-slate-400 hover:text-white hover:border-white/30 transition-all duration-200">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>

                {{-- App store buttons --}}
                <div class="flex flex-wrap gap-3">
                    {{-- App Store --}}
                    <a href="#"
                       class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-white shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="text-left">
                            <div class="text-[9px] text-slate-400 leading-none uppercase tracking-wide">Download on the</div>
                            <div class="text-[13px] font-semibold text-white leading-tight">App Store</div>
                        </div>
                    </a>
                    {{-- Google Play --}}
                    <a href="#"
                       class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-lg border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-200 group">
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
                </div>
            </div>

            {{-- Col 2 · About --}}
            <div>
                <h4 class="text-[11px] font-bold tracking-[0.14em] text-white uppercase mb-5">About</h4>
                <ul class="space-y-3">
                    @foreach([
//                        ['About Us',       '#'],
                        ['FAQ',            '/faq'],
                        ['Contact Us',     '/contact'],
                        ['Terms of Use',   '/terms'],
                        ['Privacy Policy', '/privacy'],
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
                        ['Trending TV Shows',     '/shows?sort=subscribers'],
                        ['Most Popular TV Shows',  '/shows?sort=rating'],
                        ['Newest TV Shows',        '/shows?sort=newest'],
                        ['Upcoming TV Shows',      '/shows'],
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
                        <a href="#" class="text-sm text-slate-500 hover:text-white transition-colors duration-150">Advertise</a>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="border-t border-white/5">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-slate-600 text-sm">
                © {{ date('Y') }} <span class="font-semibold text-slate-500">DiziBul</span>. All Rights Reserved.
            </p>
            <p class="text-slate-600 text-sm flex items-center gap-1.5">
                Made with <span class="text-red-500">♥</span> for Turkish drama fans
            </p>
        </div>
    </div>

</footer>
{{-- ════════════════════════════════════════════════════════════════ --}}

@stack('scripts')

<script>
    // ── Mobile menu ─────────────────────────────────────────────────
    document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });

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
