@extends('layouts.app')

@section('title', 'Turkish TV Calendar — DiziBul')
@section('description', 'Weekly Turkish TV schedule — see what episodes are airing each day.')

@section('content')

<div class="pt-[60px]" style="background:#f4f5f7; min-height:100vh;">

    {{-- ── Page header ─────────────────────────────────────── --}}
    <div style="text-align:center; padding:2.5rem 1rem 1.5rem;">
        <h1 style="font-size:2rem; font-weight:900; color:#111; letter-spacing:-0.02em; margin:0 0 0.4rem;">Turkish TV Calendar</h1>
        <p style="color:#888; font-size:0.8125rem; margin:0 0 1.25rem;">*updated daily &mdash; track your favourite shows to stay up-to-date</p>

        {{-- Toggle --}}
        <label style="display:inline-flex; align-items:center; gap:0.625rem; cursor:pointer;">
            <div id="toggle-track" onclick="handleToggle()"
                 style="position:relative; width:44px; height:24px; border-radius:9999px; background:{{ $dailyOnly ? '#e63946' : '#ccc' }}; transition:background 0.2s; cursor:pointer; flex-shrink:0;">
                <div id="toggle-thumb"
                     style="position:absolute; top:3px; left:{{ $dailyOnly ? '23px' : '3px' }}; width:18px; height:18px; border-radius:50%; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.3); transition:left 0.2s;"></div>
            </div>
            <span style="font-size:0.8125rem; font-weight:600; color:#444;">Show Daily Dramas</span>
        </label>
    </div>

    {{-- ── Calendar wrapper ────────────────────────────────── --}}
    <div style="max-width:1400px; margin:0 auto; padding:0 0.75rem 3rem;">

        {{-- Grid with side arrows --}}
        <div style="display:flex; align-items:stretch; gap:0;">

            {{-- Prev arrow --}}
            <a href="{{ request()->fullUrlWithQuery(['week' => $prevWeek]) }}"
               style="display:flex; align-items:center; justify-content:center; width:36px; flex-shrink:0; color:#fff; background:#e63946; border-radius:0.375rem 0 0 0.375rem; text-decoration:none; font-size:1.25rem; font-weight:700; transition:background 0.15s;"
               onmouseover="this.style.background='#cc2f3b'" onmouseout="this.style.background='#e63946'">
                ‹
            </a>

            {{-- 7-column day grid --}}
            <div style="flex:1; display:grid; grid-template-columns:repeat(7,minmax(0,1fr)); gap:0; border:1px solid #ddd; border-left:none; border-right:none;">

                @foreach($days as $day)
                @php
                    $dateKey = $day->format('Y-m-d');
                    $isToday = $day->isSameDay($today);
                    $dayEps  = $episodes->get($dateKey, collect());
                @endphp

                <div style="display:flex; flex-direction:column; border-right:1px solid #ddd; {{ $isToday ? 'border:2px solid #e63946; border-top:none; margin-top:0;' : '' }}">

                    {{-- Day header --}}
                    <div style="background:{{ $isToday ? '#e63946' : '#c0392b' }}; padding:0.625rem 0.5rem; text-align:center;">
                        @if($isToday)
                        <p style="color:rgba(255,255,255,0.85); font-size:0.6rem; font-weight:900; text-transform:uppercase; letter-spacing:0.12em; margin:0 0 0.15rem;">Today</p>
                        @endif
                        <p style="color:rgba(255,255,255,0.8); font-size:0.6rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; margin:0 0 0.1rem;">{{ $day->format('l') }}</p>
                        <p style="color:#fff; font-size:0.75rem; font-weight:800; margin:0;">{{ $day->format('M j') }}</p>
                    </div>

                    {{-- Episode entries --}}
                    <div style="flex:1; background:#fff; min-height:160px;">
                        @forelse($dayEps as $ep)
                        @php $show = $ep->show; @endphp
                        <a href="/shows/{{ $show->slug }}"
                           style="display:flex; align-items:flex-start; gap:0.5rem; padding:0.625rem 0.5rem; border-bottom:1px solid #f0f0f0; text-decoration:none; transition:background 0.15s;"
                           onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='transparent'">

                            {{-- Poster --}}
                            <div style="flex-shrink:0; width:38px; height:52px; border-radius:3px; overflow:hidden; background:#e5e7eb;">
                                @if($show->poster_local && file_exists(storage_path('app/public/' . $show->poster_local)))
                                <img src="{{ asset('storage/' . $show->poster_local) }}" alt="{{ $show->title }}"
                                     style="width:100%; height:100%; object-fit:cover;">
                                @elseif($show->poster)
                                <img src="{{ $show->poster }}" alt="{{ $show->title }}"
                                     style="width:100%; height:100%; object-fit:cover;">
                                @else
                                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#e5e7eb;">
                                    <svg width="16" height="16" fill="#9ca3af" viewBox="0 0 24 24"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4V6zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-8 12.5v-9l6 4.5-6 4.5z"/></svg>
                                </div>
                                @endif
                            </div>

                            {{-- Text info --}}
                            <div style="min-width:0; flex:1;">
                                <p style="color:#111; font-size:0.75rem; font-weight:700; margin:0 0 0.15rem; line-height:1.3; overflow:hidden; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical;">
                                    {{ $show->title }}
                                </p>
                                <p style="color:#888; font-size:0.65rem; margin:0 0 0.1rem;">
                                    Season {{ $ep->season_number }}, Episode {{ $ep->episode_number }}
                                </p>
                                @if($show->network)
                                <p style="color:#aaa; font-size:0.6rem; margin:0; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                                    {{ $show->network }}
                                </p>
                                @endif
                            </div>

                        </a>
                        @empty
                        <div style="display:flex; align-items:center; justify-content:center; height:80px;">
                            <p style="color:#ccc; font-size:0.75rem;">No episodes</p>
                        </div>
                        @endforelse
                    </div>

                </div>
                @endforeach

            </div>

            {{-- Next arrow --}}
            <a href="{{ request()->fullUrlWithQuery(['week' => $nextWeek]) }}"
               style="display:flex; align-items:center; justify-content:center; width:36px; flex-shrink:0; color:#fff; background:#e63946; border-radius:0 0.375rem 0.375rem 0; text-decoration:none; font-size:1.25rem; font-weight:700; transition:background 0.15s;"
               onmouseover="this.style.background='#cc2f3b'" onmouseout="this.style.background='#e63946'">
                ›
            </a>

        </div>

        {{-- Week label below --}}
        <div style="text-align:center; margin-top:1.25rem;">
            <p style="color:#666; font-size:0.8125rem;">
                {{ $weekStart->format('M j') }} – {{ $weekStart->copy()->endOfWeek()->format('M j, Y') }}
                &nbsp;·&nbsp; {{ $episodes->flatten()->count() }} episodes this week
            </p>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
var dailyActive = {{ $dailyOnly ? 'true' : 'false' }};

function handleToggle() {
    dailyActive = !dailyActive;
    document.getElementById('toggle-track').style.background = dailyActive ? '#e63946' : '#ccc';
    document.getElementById('toggle-thumb').style.left = dailyActive ? '23px' : '3px';
    var url = new URL(window.location.href);
    if (dailyActive) url.searchParams.set('daily', '1');
    else url.searchParams.delete('daily');
    setTimeout(function() { window.location.href = url.toString(); }, 220);
}
</script>
@endpush
