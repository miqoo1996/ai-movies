@extends('layouts.app')

@section('title', 'FAQ — DiziBul')
@section('description', 'Frequently asked questions about DiziBul — subscriptions, streaming, subtitles and more.')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1400px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Support</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Frequently Asked Questions</h1>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => 'faq'])
                </div>
            </aside>

            {{-- ── Accordion ────────────────────────────────────────── --}}
            <main class="flex-1 min-w-0">

                @php
                $faqs = [
                    [
                        'q' => 'How do I access the website?',
                        'a' => 'When you join one of our 3 subscription plans you will be sent all the details you need to login to our website.',
                    ],
                    [
                        'q' => 'When does a certain series or movie come out?',
                        'a' => 'We are constantly aware of any new releases and most often than not we will always take any new Series or Movies with any popular Cast and Crew.',
                    ],
                    [
                        'q' => 'How do we submit ideas or request certain series?',
                        'a' => 'All members can email us with any requests, we usually do a Poll and the popular series are usually considered.',
                    ],
                    [
                        'q' => 'How do we cancel our subscription?',
                        'a' => 'You can send an email to us asking us to terminate your subscription, alternatively you can do this via PayPal.',
                    ],
                    [
                        'q' => 'Are the Movies available to Download?',
                        'a' => 'All our video content is not for downloading, we provide a streaming service directly on our website.',
                    ],
                    [
                        'q' => 'Are all the Movies in HD?',
                        'a' => 'Most of our content is in HD. We also have series which aired on TV before 2010 — these are only available in the quality they were originally released.',
                    ],
                    [
                        'q' => 'Do we have any adverts on our Videos?',
                        'a' => 'We don\'t have any adverts interrupting your viewership. However some series/episodes may contain adverts, simply due to them being embedded directly in the video.',
                    ],
                    [
                        'q' => 'What if there are issues with Translation?',
                        'a' => 'We pride ourselves on quality. Send us an email with the series name and the affected episode and we will do our best to resolve it immediately.',
                    ],
                    [
                        'q' => 'We cannot log in — what do we do?',
                        'a' => 'It could be due to your subscription having expired. Contact our customer services team at wecare@turkflix.co.uk.',
                    ],
                    [
                        'q' => 'How quickly are Episodes translated each week?',
                        'a' => 'New episodes are usually translated and uploaded within a few days of the original air date. We always aim to deliver as quickly as possible.',
                    ],
                    [
                        'q' => 'Are the Subtitles embedded in the Video or added via a File?',
                        'a' => 'All our subtitles are directly burned into the videos.',
                    ],
                    [
                        'q' => 'Do you sell your Subtitle Files?',
                        'a' => 'We do not usually sell our subtitle files. If you are interested you can email md@turkflix.co.uk.',
                    ],
                ];
                @endphp

                <div id="faq-accordion" class="divide-y divide-white/[0.06]">
                    @foreach($faqs as $i => $faq)
                    <div class="faq-item group/item" data-index="{{ $i }}">

                        <button class="faq-trigger w-full flex items-center justify-between gap-8 py-5 text-left">
                            <span class="faq-question text-slate-300 font-semibold text-[15px] leading-snug transition-colors duration-200 group-hover/item:text-white">
                                {{ $faq['q'] }}
                            </span>
                            <span class="faq-icon shrink-0 w-6 h-6 rounded-full border border-white/10 flex items-center justify-center text-slate-500 text-sm leading-none transition-all duration-200 group-hover/item:border-white/20 group-hover/item:text-slate-300">
                                +
                            </span>
                        </button>

                        <div class="faq-body overflow-hidden max-h-0 transition-all duration-300 ease-in-out">
                            <p class="text-slate-500 text-[14px] leading-[1.8] pb-6 max-w-2xl">{{ $faq['a'] }}</p>
                        </div>

                    </div>
                    @endforeach
                </div>

                {{-- Bottom contact note --}}
                <div class="mt-12 pt-8 border-t border-white/5 flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-[#e63946]/15 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-[#e63946]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">
                        Still have questions?
                        <a href="mailto:wecare@turkflix.co.uk" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors ml-1">wecare@turkflix.co.uk</a>
                    </p>
                </div>

            </main>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const items = document.querySelectorAll('.faq-item');

    function open(item) {
        const body     = item.querySelector('.faq-body');
        const icon     = item.querySelector('.faq-icon');
        const question = item.querySelector('.faq-question');
        body.style.maxHeight = body.scrollHeight + 'px';
        icon.textContent = '−';
        icon.style.cssText = 'border-color:rgba(230,57,70,.4);color:#e63946;background:rgba(230,57,70,.1)';
        question.style.color = '#fff';
        item.dataset.open = '1';
    }

    function close(item) {
        const body     = item.querySelector('.faq-body');
        const icon     = item.querySelector('.faq-icon');
        const question = item.querySelector('.faq-question');
        body.style.maxHeight = '0';
        icon.textContent = '+';
        icon.style.cssText = '';
        question.style.color = '';
        delete item.dataset.open;
    }

    // Explicitly collapse all, then open only the first
    items.forEach(item => {
        item.querySelector('.faq-body').style.maxHeight = '0';
    });
    open(items[0]);

    items.forEach(item => {
        item.querySelector('.faq-trigger').addEventListener('click', () => {
            item.dataset.open ? close(item) : open(item);
        });
    });
})();
</script>
@endpush
