<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach($staticUrls as $item)
    <url>
        <loc>{{ $item['loc'] }}</loc>
    </url>
@endforeach
@foreach($shows as $show)
    <url>
        <loc>{{ route('shows.show', $show->slug) }}</loc>
        @if($show->updated_at)<lastmod>{{ $show->updated_at->toAtomString() }}</lastmod>@endif
    </url>
@endforeach
@foreach($people as $person)
    <url>
        <loc>{{ route('people.show', $person) }}</loc>
        @if($person->updated_at)<lastmod>{{ $person->updated_at->toAtomString() }}</lastmod>@endif
    </url>
@endforeach
@foreach($articles as $article)
    <url>
        <loc>{{ route('articles.show', $article->slug) }}</loc>
        @if($article->updated_at)<lastmod>{{ $article->updated_at->toAtomString() }}</lastmod>@endif
    </url>
@endforeach
</urlset>