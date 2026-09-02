@extends('layouts.app')

@section('seo_title', $article->seo_title ?: $article->title)
@section('meta_description', $article->seo_description ?: ($article->excerpt ?: Str::limit(strip_tags($article->content), 155)))
@section('canonical', route('articles.show', $article))
@if($article->noindex)@section('noindex', '1')@endif
@if($article->cover_url)@section('og_image', $article->cover_url)@endif

@section('json_ld')
@php
    $articleJsonLd = array_filter([
        '@context'      => 'https://schema.org',
        '@type'         => 'Article',
        'headline'      => $article->title,
        'url'           => route('articles.show', $article),
        'description'   => $article->seo_description ?: $article->excerpt,
        'image'         => $article->cover_url,
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified'  => $article->updated_at?->toIso8601String(),
        'publisher'     => [
            '@type' => 'Organization',
            'name'  => setting('site_name', 'DiziCentral'),
        ],
    ], fn ($value) => $value !== null && $value !== '');
@endphp
<script type="application/ld+json">{!! json_encode($articleJsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endsection

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    @if($article->cover_url)
        <div class="relative h-[38vh] min-h-[260px] w-full overflow-hidden">
            <img src="{{ $article->cover_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-[#080810] via-[#080810]/70 to-[#080810]/20"></div>
        </div>
    @endif

    <div class="max-w-[820px] mx-auto px-6 {{ $article->cover_url ? '-mt-24 relative' : 'pt-12' }} pb-16">

        {{-- Breadcrumb --}}
        <nav class="text-xs text-slate-500 mb-4">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors">Home</a>
            <span class="mx-1.5 text-slate-700">/</span>
            <a href="{{ route('articles.index') }}" class="hover:text-white transition-colors">Articles</a>
        </nav>

        <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight leading-tight">{{ $article->title }}</h1>

        @if($article->published_at)
            <p class="mt-4 text-[11px] font-bold uppercase tracking-[0.15em] text-[#e63946]">
                {{ $article->published_at->format('F j, Y') }}
            </p>
        @endif

        @if($article->excerpt)
            <p class="mt-5 text-slate-400 text-lg leading-relaxed">{{ $article->excerpt }}</p>
        @endif

        <div class="article-content mt-8">
            {!! $article->content !!}
        </div>

        @if($related->isNotEmpty())
        <section class="mt-16 pt-10 border-t border-white/5">
            <h2 class="text-white text-xl font-black tracking-tight mb-6">More articles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($related as $item)
                    @include('articles._card', ['article' => $item])
                @endforeach
            </div>
        </section>
        @endif

    </div>
</div>

@endsection

@push('scripts')
<style>
    .article-content { color: #cbd5e1; font-size: 1.0625rem; line-height: 1.85; }
    .article-content > * + * { margin-top: 1.25rem; }
    .article-content h2 { color: #fff; font-size: 1.5rem; font-weight: 800; margin-top: 2.5rem; letter-spacing: -0.01em; }
    .article-content h3 { color: #fff; font-size: 1.2rem; font-weight: 700; margin-top: 2rem; }
    .article-content a { color: #e63946; text-decoration: underline; text-underline-offset: 3px; }
    .article-content a:hover { color: #fff; }
    .article-content ul, .article-content ol { padding-left: 1.4rem; }
    .article-content ul { list-style: disc; }
    .article-content ol { list-style: decimal; }
    .article-content li + li { margin-top: .5rem; }
    .article-content img { border-radius: .75rem; max-width: 100%; height: auto; }
    .article-content blockquote { border-left: 3px solid #e63946; padding-left: 1.25rem; color: #94a3b8; font-style: italic; }
    .article-content strong { color: #fff; }
    .article-content table { width: 100%; border-collapse: collapse; }
    .article-content th, .article-content td { border: 1px solid rgba(255,255,255,.1); padding: .6rem .75rem; text-align: left; }
</style>
@endpush
