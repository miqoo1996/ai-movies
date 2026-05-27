@extends('layouts.app')

@section('title', $page->title . ' — DiziCentral')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1600px] mx-auto px-6 py-10">
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">{{ $page->title }}</h1>
        </div>
    </div>

    <div class="max-w-[1600px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- Sidebar --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => $page->slug])
                </div>
            </aside>

            {{-- Content --}}
            <main class="flex-1 min-w-0">
                @if($page->content)
                    <div class="prose prose-invert prose-slate max-w-none
                                prose-headings:text-white prose-headings:font-black
                                prose-p:text-slate-400 prose-p:leading-relaxed
                                prose-a:text-[#e63946] prose-a:no-underline hover:prose-a:underline
                                prose-li:text-slate-400
                                prose-strong:text-slate-200">
                        {!! $page->content !!}
                    </div>
                @else
                    <p class="text-slate-500 italic">No content yet. <a href="/admin/pages" class="text-[#e63946]">Add it in the admin panel.</a></p>
                @endif
            </main>

        </div>
    </div>
</div>

@endsection
