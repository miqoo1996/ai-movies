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
    <article class="article-shell">

        {{-- Breadcrumb --}}
        <nav class="article-crumbs">
            <a href="{{ url('/') }}">Home</a>
            <span>/</span>
            <a href="{{ route('articles.index') }}">Articles</a>
        </nav>

        <header class="article-head">
            @if($article->published_at)
                <p class="article-eyebrow">{{ $article->published_at->format('F j, Y') }}</p>
            @endif

            <h1 class="article-title">{{ $article->title }}</h1>

            @if($article->excerpt)
                <p class="article-standfirst">{{ $article->excerpt }}</p>
            @endif
        </header>

        @if($article->cover_url)
            <figure class="article-cover">
                <img src="{{ $article->cover_url }}" alt="{{ $article->title }}">
            </figure>
        @endif

        <div class="article-content">
            {!! $article->content !!}
        </div>

        @if($related->isNotEmpty())
            <section class="article-related">
                <h2>More articles</h2>
                <div class="article-related-grid">
                    @foreach($related as $item)
                        @include('articles._card', ['article' => $item])
                    @endforeach
                </div>
            </section>
        @endif

    </article>
</div>

@endsection

@push('scripts')
<style>
    /* Layout is defined here rather than with Tailwind utilities so the page keeps
       its proportions even if the CSS bundle has not been rebuilt after a deploy. */
    .article-shell { max-width: 820px; margin: 0 auto; padding: 2.5rem 1.5rem 4rem; }

    .article-crumbs { font-size: .75rem; color: #64748b; margin-bottom: 1.5rem; }
    .article-crumbs a { color: #64748b; text-decoration: none; transition: color .15s; }
    .article-crumbs a:hover { color: #fff; }
    .article-crumbs span { margin: 0 .4rem; color: #334155; }

    .article-eyebrow { font-size: .6875rem; font-weight: 800; text-transform: uppercase; letter-spacing: .15em; color: #e63946; margin-bottom: .75rem; }
    .article-title { color: #fff; font-size: clamp(1.85rem, 4vw, 2.6rem); font-weight: 900; line-height: 1.15; letter-spacing: -.02em; }
    .article-standfirst { margin-top: 1.15rem; color: #94a3b8; font-size: 1.125rem; line-height: 1.7; }

    .article-cover { margin: 2.5rem 0 0; border-radius: .875rem; overflow: hidden; background: #111122; border: 1px solid rgba(255,255,255,.06); aspect-ratio: 16 / 9; }
    .article-cover img { display: block; width: 100%; height: 100%; object-fit: cover; }
    @supports not (aspect-ratio: 16 / 9) {
        .article-cover { height: 0; padding-bottom: 56.25%; position: relative; }
        .article-cover img { position: absolute; inset: 0; }
    }

    .article-content { margin-top: 2.5rem; color: #cbd5e1; font-size: 1.0625rem; line-height: 1.85; }
    .article-content > * + * { margin-top: 1.25rem; }
    .article-content h2 { color: #fff; font-size: 1.5rem; font-weight: 800; margin-top: 2.5rem; letter-spacing: -.01em; }
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

    .article-related { margin-top: 4rem; padding-top: 2.5rem; border-top: 1px solid rgba(255,255,255,.06); }
    .article-related > h2 { color: #fff; font-size: 1.25rem; font-weight: 900; letter-spacing: -.01em; margin-bottom: 1.5rem; }
    .article-related-grid { display: grid; grid-template-columns: 1fr; gap: 1.5rem; }
    @media (min-width: 640px) { .article-related-grid { grid-template-columns: 1fr 1fr; } }
</style>
@endpush
