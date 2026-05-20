@extends('layouts.app')

@section('title', 'Terms & Conditions — DiziBul')
@section('description', 'Terms and conditions for using DiziBul — please read before using our service.')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1400px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Legal</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Terms &amp; Conditions</h1>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-12">
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

                {{-- Prose styles applied via inline classes --}}
                <div class="space-y-24 text-slate-400 text-[14.5px] leading-[1.85]">

                    {{-- Overview --}}
                    <section id="overview">
                        <p>
                            Access to and use of this website is provided by <span class="text-white font-semibold">TurkFlix Ltd</span> subject to these Terms and Conditions.
                            We provide a service to allow you to enjoy Turkish Series &amp; Movies available in English Subtitles.
                            TurkFlix is fully responsible for its translation work offered to each Series/Movie we publish on our website.
                            We always aim to produce a High-Quality Translation Service.
                        </p>

                        <br>
                    </section>

                    {{-- Security --}}
                    <section id="security">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Security</h2>
                        <div class="space-y-4">
                            <p>
                                No data transmission over the Internet can be guaranteed to be 100% secure. Therefore, TurkFlix cannot guarantee that personal or sensitive information remains confidential during transmission over the Internet. All e-mails sent to TurkFlix Official will be monitored and checked to ensure our systems operate effectively.
                            </p>
                            <p>
                                The personal information we collect from you online is stored by us on databases protected through access controls, firewall technology and other appropriate security measures. However, such security measures cannot prevent all loss, misuse or alteration of personal information and we are not responsible for any damage or liability relating to such incidents.
                            </p>
                        </div>

                        <br>
                    </section>

                    {{-- Links --}}
                    <section id="links">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Links to and from TurkFlix Official Site</h2>
                        <div class="space-y-4">
                            <p>
                                TurkFlix cannot take any responsibility and makes no warranties, representations or undertakings about the content of any other website accessed by hypertext link. TurkFlix Official has no control over the availability of the linked pages.
                            </p>
                            <p>
                                If you click on a link found on our website or on any other website, you can check the location bar within your browser to find out whether you have been linked to a different website.
                            </p>
                        </div>

                        <br>
                    </section>

                    {{-- Subscriptions --}}
                    <section id="subscriptions">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Subscription, Direct/Credit Card Transactions</h2>
                        <p class="mb-5">
                            In order to process payments and other transactions by Direct Debit or Credit Card you will be put through to the website of one of our partner companies. Credit card and bank detail information are not retained on our web servers. All our partner companies who process payments and other transactions use encryption and other security features.
                        </p>

                        <p class="text-white font-semibold text-[13px] uppercase tracking-wider mb-4">By subscribing to any of our plans you agree to the below</p>

                        <ul class="space-y-4">
                            @foreach([
                                'Responsible for joining TurkFlix with full consent and responsibility.',
                                'Responsible for cancelling your services in a timely manner. Any cancellations should be done 48 hours before the next payment is due. We are not responsible for refunds if this is not adhered to. If we do not cancel your payment on time we will therefore refund accordingly. You must send your cancellation request to us via email at turkflixmembers@gmail.com.',
                                'All new members have the right to cancel on our monthly and annual plans within the first 14 days; administration fees will be deducted nevertheless. After this period, we will not make any refunds — it is your responsibility to contact us and inform us of cancellation. Our systems clearly identify all members who have logged in and used our services. Reports will be given to financial institutions as evidence on any disputes and chargeback claims. No refunds are offered under any promotion we are running, be it Monthly or Annual.',
                                'It is your responsibility to check exactly what is on offer on our website before joining. We are not responsible for refunds of this nature.',
                                'All initial payments made are subscriptions for automatic payments to be processed on anniversary dates of each plan — 7, 30 or 365 days. You are fully responsible for accepting this when you first join our services. We are not responsible for refunds of this nature.',
                                'All our content on our website is watermarked with our company logo to protect its brand identity.',
                                'Some of our Series are Low Quality as they date back over a decade.',
                                'Any communication directed to us via our social media platforms is not deemed the correct method of communication. It is critical for your request to be handled by our correct channels — please visit the Contact Us page.',
                            ] as $point)
                            <li class="flex gap-3">
                                <span class="mt-2 shrink-0 w-1.5 h-1.5 rounded-full bg-[#e63946]"></span>
                                <span>{{ $point }}</span>
                            </li>
                            @endforeach
                        </ul>

                        <br>
                    </section>

                    {{-- Disclaimer --}}
                    <section id="disclaimer">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Disclaimer</h2>
                        <p>
                            Although every reasonable effort has been made to ensure that the information on this website is accurate at the time of publication, visitors who use this website and rely on any information do so at their own risk. TurkFlix does not warrant its accuracy and disclaims any liability to any third party anywhere in the world for any injury, damage, loss or inconvenience arising as a consequence of any use of or the inability to use any information on this website to the fullest extent permitted at law.
                        </p>

                        <br>
                    </section>

                    {{-- Copyright --}}
                    <section id="copyright">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Copyright</h2>
                        <div class="space-y-4">
                            <p>
                                The name TurkFlix and all of the logos, slogans, and designs are the trademarks, service marks, trade names and design rights of TurkFlix Official and cannot be reproduced without the prior written consent of the Organisation. Where the names, logos and trademarks of third parties are displayed these are used with the permission of the owners. Copyright in the material contained on this website is owned by TurkFlix or its content suppliers or licensors, as applicable. <span class="text-white/70">We do not own the rights of the video content on this website — the ownership of our service is for the English Subtitles.</span>
                            </p>
                            <p>
                                Nothing contained herein shall be construed as conferring any licence by TurkFlix to use any material displayed. Permission to reproduce any material on this website must be obtained from the copyright holder concerned.
                            </p>
                            <p>
                                Any questions regarding this website should be sent to
                                <a href="mailto:turkflixmembers@gmail.com" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors">turkflixmembers@gmail.com</a>.
                            </p>
                            <p class="text-slate-500 text-[13px] border-t border-white/5 pt-4 mt-2">
                                These terms and conditions shall be governed and construed in accordance with the laws of England and Wales and any disputes arising hereunder shall be subject to the exclusive jurisdiction of the Courts of England and Wales.
                            </p>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>
</div>

@endsection
