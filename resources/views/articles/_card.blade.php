<a href="{{ route('articles.show', $article) }}"
   class="group block rounded-xl overflow-hidden bg-[#0d0d18] border border-white/5 hover:border-[#e63946]/50 transition-all duration-200">

    <div class="aspect-video overflow-hidden bg-[#111122]">
        @if($article->cover_url)
            <img src="{{ $article->cover_url }}" alt="{{ $article->title }}" loading="lazy"
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
        @else
            <div class="w-full h-full flex items-center justify-center text-slate-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 5h16v14H4z M4 15l4-4 4 4 3-3 5 5"/>
                </svg>
            </div>
        @endif
    </div>

    <div class="p-5">
        @if($article->published_at)
            <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-[#e63946] mb-2">
                {{ $article->published_at->format('M j, Y') }}
            </p>
        @endif

        <h3 class="text-white font-bold leading-snug group-hover:text-[#e63946] transition-colors">
            {{ $article->title }}
        </h3>

        @if($article->excerpt)
            <p class="mt-2 text-sm text-slate-500 leading-relaxed line-clamp-3">{{ $article->excerpt }}</p>
        @endif
    </div>
</a>
