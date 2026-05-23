@extends('layouts.app')

@section('seo_title', $page->seo_title ?: 'Terms & Conditions')
@section('meta_description', $page->seo_description ?: 'Review the DiziBul terms and conditions. By using our Turkish drama platform you agree to these terms — please read carefully before subscribing.')
@if($page->noindex)@section('noindex', '1')@endif

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1600px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Legal</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Terms &amp; Conditions</h1>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => 'terms'])

                    {{-- On-page anchors --}}
                    <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-600 px-3 mt-8 mb-3">Sections</p>
                    <nav class="flex flex-col">
                        @foreach([
                            ['label' => 'Overview',        'href' => '#overview'],
                            ['label' => 'Security',        'href' => '#security'],
                            ['label' => 'Links',           'href' => '#links'],
                            ['label' => 'Subscriptions',   'href' => '#subscriptions'],
                            ['label' => 'Disclaimer',      'href' => '#disclaimer'],
                            ['label' => 'Copyright',       'href' => '#copyright'],
                        ] as $anchor)
                        <a href="{{ $anchor['href'] }}"
                           class="px-3 py-2 text-xs text-slate-500 hover:text-slate-300 rounded-lg transition-colors duration-150">
                            {{ $anchor['label'] }}
                        </a>
                        @endforeach
                    </nav>
                </div>
            </aside>

            {{-- ── Content ───────────────────────────────────────────── --}}
            <main class="flex-1 min-w-0 max-w-3xl">
                {!! $page->content !!}
            </main>
        </div>
    </div>
</div>

@endsection
