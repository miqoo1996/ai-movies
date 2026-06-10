@extends('layouts.app')

@section('seo_title', 'Best Turkish Series Ever — Must-Watch Turkish Dramas')
@section('meta_description', 'Our hand-picked selection of the greatest Turkish TV series ever made. Discover epic romances, historical sagas, and gripping dramas — with full synopses and cast details.')
@section('keywords', 'best Turkish series, top Turkish dramas, must watch Turkish shows, greatest Turkish TV series, best Turkish dramas ever, top rated Turkish series')

@section('content')

{{-- ═══════════════════════ HERO ══════════════════════════════════════ --}}
<section class="relative overflow-hidden bg-[#080810] py-20 md:py-28">
    {{-- Background glow --}}
    <div class="absolute inset-0 pointer-events-none overflow-hidden">
        <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-violet-600/10 rounded-full blur-3xl"></div>
        <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-violet-500/30 to-transparent"></div>
    </div>

    <div class="relative max-w-[1600px] mx-auto px-4 sm:px-6 text-center">
        <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full border border-violet-500/30 bg-violet-500/10 text-violet-300 text-xs font-semibold tracking-widest uppercase mb-5">
            Editorial Selection
        </span>
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-black text-white mb-5 leading-tight">
            Best Series <span class="text-transparent bg-clip-text bg-gradient-to-r from-violet-400 to-indigo-400">Ever</span>
        </h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto leading-relaxed">
            The greatest Turkish dramas that defined a generation — sweeping romances,
            historical epics, and gripping thrillers that captivated audiences around the world.
        </p>
        <div class="mt-8 flex items-center justify-center gap-6 text-sm text-slate-500">
            <span class="flex items-center gap-1.5">
                <svg class="w-4 h-4 text-violet-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                {{ $shows->count() }} Essential Shows
            </span>
            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
            <span>Hand-picked Editorials</span>
            <span class="w-1 h-1 rounded-full bg-slate-700"></span>
            <span>Full Cast &amp; Synopses</span>
        </div>
    </div>
</section>

{{-- ═══════════════════════ SHOW LIST ════════════════════════════════ --}}
<section class="max-w-[1600px] mx-auto px-4 sm:px-6 py-12 space-y-0">

    @foreach($shows as $rank => $show)
    @php
        $cast    = $show->castMembers->take(5);
        $genres  = $show->genres->take(3);
        $isEven  = $rank % 2 === 0;
        $ed      = $editorial[$show->slug] ?? null;
    @endphp

    {{-- Divider --}}
    @if($rank > 0)
    <div class="border-t border-white/5 my-0"></div>
    @endif

    <article class="group relative py-12 md:py-16">

        {{-- Rank watermark --}}
        <div class="absolute top-8 {{ $isEven ? 'right-4' : 'left-4' }} text-[120px] font-black leading-none select-none pointer-events-none hidden lg:block"
             style="color: transparent; -webkit-text-stroke: 1px rgba(139,92,246,0.1); font-family: Arial Black, sans-serif;">
            {{ $rank + 1 }}
        </div>

        <div class="flex flex-col {{ $isEven ? 'md:flex-row' : 'md:flex-row-reverse' }} gap-6 md:gap-10 items-start">

            {{-- ── Poster column ──────────────────────────────── --}}
            <div class="relative shrink-0 w-full md:w-40 lg:w-44 mx-auto max-w-[160px] md:max-w-none">
                <a href="{{ route('shows.show', $show->slug) }}" class="block group/poster">
                    <div class="relative rounded-xl overflow-hidden shadow-2xl shadow-black/60 aspect-[2/3] w-full">
                        <img src="{{ $show->poster_url }}"
                             alt="{{ $show->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover/poster:scale-105">
                        {{-- Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/0 to-black/0 opacity-0 group-hover/poster:opacity-100 transition-opacity duration-300 flex items-end p-4">
                            <span class="text-white text-sm font-semibold flex items-center gap-1.5">
                                View Series
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </div>
                </a>

                {{-- Rank badge (mobile) --}}
                <div class="absolute -top-3 -left-3 w-10 h-10 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-violet-500/30 md:hidden">
                    {{ $rank + 1 }}
                </div>

                {{-- Subscribers pill --}}
                <div class="mt-3 flex items-center justify-center gap-1.5 text-xs text-slate-500">
                    <svg class="w-3.5 h-3.5 text-violet-400" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                    {{ number_format($show->subscribers) }} followers
                </div>
            </div>

            {{-- ── Content column ─────────────────────────────── --}}
            <div class="flex-1 min-w-0 flex flex-col gap-5">

                {{-- Rank badge (desktop) --}}
                <div class="hidden md:flex items-center gap-3">
                    <span class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white font-black text-sm shadow-lg shadow-violet-500/30 shrink-0">
                        {{ $rank + 1 }}
                    </span>
                    <span class="text-xs font-bold tracking-widest text-violet-400 uppercase">Editorial Pick</span>
                </div>

                {{-- Title --}}
                <div>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white leading-tight mb-1">
                        <a href="{{ route('shows.show', $show->slug) }}" class="hover:text-violet-300 transition-colors">
                            {{ $show->title }}
                        </a>
                    </h2>
                    @if($show->original_title && $show->original_title !== $show->title)
                    <p class="text-slate-500 text-sm font-medium">{{ $show->original_title }}</p>
                    @endif
                    @if($ed && !empty($ed['headline']))
                    <p class="text-violet-300/80 text-base font-medium italic mt-1">{{ $ed['headline'] }}</p>
                    @endif
                </div>

                {{-- Meta badges --}}
                <div class="flex flex-wrap gap-2">
                    @if($show->year)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md bg-white/5 text-slate-300 text-xs font-semibold border border-white/10">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
                        {{ $show->year }}
                    </span>
                    @endif
                    @if($show->network)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-white/5 text-slate-300 text-xs font-semibold border border-white/10">
                        {{ $show->network }}
                    </span>
                    @endif
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border
                        {{ $show->status === 'Running' || $show->status === 'Returning Series'
                            ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20'
                            : 'bg-white/5 text-slate-400 border-white/10' }}">
                        @if($show->status === 'Running' || $show->status === 'Returning Series')
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                        @endif
                        {{ $show->status }}
                    </span>
                    @foreach($genres as $genre)
                    <span class="inline-flex items-center px-2.5 py-1 rounded-md bg-violet-500/10 text-violet-300 text-xs font-semibold border border-violet-500/20">
                        {{ $genre->name }}
                    </span>
                    @endforeach
                </div>

                {{-- Synopsis --}}
                <div class="prose prose-invert prose-sm max-w-none">
                    <p class="text-slate-300 leading-relaxed text-[15px]">
                        {{ $show->synopsis }}
                    </p>
                </div>

                {{-- Themes --}}
                @if($ed && !empty($ed['themes']))
                <div>
                    <p class="text-[10px] font-bold tracking-widest text-violet-400 uppercase mb-2.5">Themes</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($ed['themes'] as $themeName => $themeDesc)
                        <span title="{{ $themeDesc }}"
                              class="inline-flex items-center px-2.5 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-medium cursor-default">
                            {{ $themeName }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Why Audiences Love --}}
                @if($ed && !empty($ed['why_love']))
                <div class="rounded-xl bg-white/[0.03] border border-white/[0.06] px-5 py-4">
                    <p class="text-[10px] font-bold tracking-widest text-emerald-400 uppercase mb-2">Why Audiences Love It</p>
                    <p class="text-slate-300 text-sm leading-relaxed">{{ $ed['why_love'] }}</p>
                </div>
                @endif

                {{-- Final Thoughts --}}
                @if($ed && !empty($ed['final']))
                <div class="border-l-2 border-violet-500/50 pl-4">
                    <p class="text-[10px] font-bold tracking-widest text-violet-400 uppercase mb-2">Final Thoughts</p>
                    <p class="text-slate-400 text-sm leading-relaxed italic">{{ $ed['final'] }}</p>
                </div>
                @endif

                {{-- Cast --}}
                @if($cast->isNotEmpty())
                <div>
                    <p class="text-[10px] font-bold tracking-widest text-violet-400 uppercase mb-2.5">Starring</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($cast as $person)
                        <a href="{{ route('people.show', $person) }}"
                           class="group/cast inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-medium hover:bg-white/10 hover:border-violet-500/40 hover:text-white transition-all">
                            <svg class="w-3 h-3 text-slate-500 group-hover/cast:text-violet-400 transition-colors" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            {{ $person->name }}
                            @if($person->pivot->character_name)
                            <span class="text-slate-500">as {{ $person->pivot->character_name }}</span>
                            @endif
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- CTA --}}
                <div class="flex items-center gap-3 pt-1">
                    <a href="{{ route('shows.show', $show->slug) }}"
                       class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-gradient-to-r from-violet-600 to-indigo-600 text-white text-sm font-semibold hover:from-violet-500 hover:to-indigo-500 transition-all duration-200 shadow-lg shadow-violet-500/20">
                        Explore Series
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @if($show->episodes_count ?? $show->episodes->count() ?? null)
                    <span class="text-slate-500 text-xs">{{ $show->episodes_count ?? $show->episodes->count() }} episodes</span>
                    @endif
                </div>

            </div>
        </div>
    </article>
    @endforeach

</section>

{{-- ═══════════════════════ BOTTOM CTA ══════════════════════════════ --}}
<section class="border-t border-white/5 bg-[#0a0a15] py-16 mt-8">
    <div class="max-w-[1600px] mx-auto px-4 sm:px-6 text-center">
        <h3 class="text-2xl font-bold text-white mb-3">Discover More Turkish Dramas</h3>
        <p class="text-slate-400 mb-7">Browse our full catalog of Turkish series, sorted by popularity, rating, or genre.</p>
        <a href="{{ route('shows.index') }}"
           class="inline-flex items-center gap-2 px-6 py-3 rounded-lg border border-violet-500/50 text-violet-300 font-semibold text-sm hover:bg-violet-500/10 hover:border-violet-400 transition-all">
            Browse All Shows
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

@endsection
