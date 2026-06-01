@extends('layouts.app')

@section('seo_title', $person->name . ' — Actor Profile')
@section('meta_description', $person->name . ' has appeared in ' . $person->shows->count() . ' Turkish series. View their full filmography.')
@section('canonical', route('people.show', $person))

@section('content')
<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- ══════════════════════════════════ HERO ══════════════════════ --}}
    <div class="relative overflow-hidden">

        {{-- Blurred backdrop from photo --}}
        @if($person->photo)
        <div class="absolute inset-0">
            <img src="{{ $person->photo }}" alt="" aria-hidden="true"
                 class="w-full h-full object-cover scale-110 blur-3xl brightness-[0.35] saturate-150">
            <div class="absolute inset-0 bg-gradient-to-r from-[#080810]/90 via-[#080810]/60 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-[#080810] to-transparent"></div>
        </div>
        @else
        <div class="absolute inset-0 bg-gradient-to-b from-[#0d0d22] to-[#080810]"></div>
        @endif

        <div class="relative z-10 max-w-[1600px] mx-auto px-6 py-14">
            <div class="flex gap-9 items-end">

                {{-- Photo --}}
                <div class="shrink-0 w-[140px] sm:w-[180px] rounded-2xl overflow-hidden shadow-[0_20px_60px_rgba(0,0,0,0.8)] ring-1 ring-white/10">
                    @if($person->photo)
                        <img src="{{ $person->photo }}" alt="{{ $person->name }}" class="w-full h-auto object-cover">
                    @else
                        <div class="aspect-[2/3] bg-[#1a1a2e] flex items-center justify-center">
                            <svg class="w-16 h-16 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0 pb-2">
                    <p class="text-[#e63946] text-xs font-black uppercase tracking-widest mb-2">Actor / Actress</p>
                    <h1 class="text-white text-3xl sm:text-[2.4rem] font-black leading-tight tracking-tight mb-4 drop-shadow-xl">
                        {{ $person->name }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        @if($person->gender)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">{{ $person->gender }}</span>
                        @endif
                        @if($person->country)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">{{ $person->country }}</span>
                        @endif
                        @if($person->birthday)
                            <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-white/10 text-slate-200">
                                Born {{ $person->birthday->format('M d, Y') }}
                                <span class="text-slate-400 ml-1">({{ $person->birthday->age }} yrs)</span>
                            </span>
                        @endif
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-md bg-violet-500/20 text-violet-300 border border-violet-500/20">
                            {{ $person->shows->count() }} {{ Str::plural('Show', $person->shows->count()) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════ FILMOGRAPHY ═══════════════════ --}}
    <div class="max-w-[1600px] mx-auto px-6 py-10">

        <div class="flex items-baseline gap-3 mb-7">
            <h2 class="text-[#e63946] text-sm font-black uppercase tracking-widest inline-block border-b-2 border-[#e63946] pb-0.5">
                Known For
            </h2>
            <span class="text-slate-500 text-xs">{{ $person->shows->count() }} {{ Str::plural('series', $person->shows->count()) }}</span>
        </div>

        @if($person->shows->isNotEmpty())
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
            @foreach($person->shows as $show)
            <a href="{{ route('shows.show', $show->slug) }}" class="block group">
                <div class="relative rounded-xl overflow-hidden aspect-[2/3] bg-[#0d0d18] mb-2 shadow-lg">
                    <img src="{{ $show->poster_url }}" alt="{{ $show->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                    {{-- Dark overlay on hover --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-200"></div>

                    {{-- Character badge --}}
                    @if($show->pivot->character_name)
                    <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent px-3 pt-8 pb-2.5">
                        <p class="text-white text-[10px] font-bold leading-snug line-clamp-2">
                            {{ $show->pivot->character_name }}
                        </p>
                    </div>
                    @endif

                    {{-- Network badge --}}
                    @if($show->network)
                    <div class="absolute top-2 left-2">
                        <span class="text-[9px] font-bold bg-black/75 backdrop-blur-sm text-white px-1.5 py-0.5 rounded">
                            {{ $show->network }}
                        </span>
                    </div>
                    @endif

                    {{-- Status dot --}}
                    @if($show->status === 'Running' || $show->status === 'Returning Series')
                    <div class="absolute top-2 right-2 w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_6px_rgba(52,211,153,0.8)]"></div>
                    @endif
                </div>

                <p class="text-slate-200 text-[12px] font-semibold leading-snug line-clamp-2 group-hover:text-white transition-colors">
                    {{ $show->title }}
                </p>
                <p class="text-slate-500 text-[11px] mt-0.5">{{ $show->year }}</p>
            </a>
            @endforeach
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <p class="text-slate-500 text-sm">No shows found for this person.</p>
        </div>
        @endif
    </div>

</div>
@endsection
