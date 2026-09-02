@extends('layouts.app')

@section('seo_title', $seoPage?->seo_title ?: 'Articles')
@section('meta_description', $seoPage?->seo_description ?: 'Guides, news and stories about Turkish dramas — cast news, episode recaps, streaming tips and more.')
@if($seoPage?->noindex)@section('noindex', '1')@endif

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1600px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Read</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Articles</h1>
            <p class="text-slate-400 text-sm mt-3 max-w-2xl">
                Guides, news and stories to go with your watchlist.
            </p>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 py-12">

        @if($articles->isEmpty())
            <p class="text-slate-500 text-sm">No articles published yet.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 stagger">
                @foreach($articles as $article)
                    @include('articles._card', ['article' => $article])
                @endforeach
            </div>

            @if($articles->hasPages())
            <div class="mt-12">
                {{ $articles->onEachSide(1)->links() }}
            </div>
            @endif
        @endif

    </div>
</div>

@endsection
