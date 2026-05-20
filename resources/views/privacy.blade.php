@extends('layouts.app')

@section('title', 'Privacy Policy — DiziBul')
@section('description', 'Privacy policy for DiziBul — how we collect, use and protect your personal data.')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1400px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Legal</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Privacy Policy</h1>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => 'privacy'])

                    <p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-600 px-3 mt-8 mb-3">Sections</p>
                    <nav class="flex flex-col">
                        @foreach([
                            ['label' => 'Overview',          'href' => '#overview'],
                            ['label' => 'Data Protection',   'href' => '#data-protection'],
                            ['label' => 'How We Use Data',   'href' => '#how-we-use'],
                            ['label' => 'Data We Collect',   'href' => '#data-collected'],
                            ['label' => 'Third Parties',     'href' => '#third-parties'],
                            ['label' => 'How We Contact You','href' => '#contact'],
                            ['label' => 'Keeping Your Data', 'href' => '#retention'],
                            ['label' => 'Your Rights',       'href' => '#your-rights'],
                            ['label' => 'Cookie Policy',     'href' => '#cookies'],
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
                <div class="space-y-24 text-slate-400 text-[14.5px] leading-[1.85]">

                    {{-- Overview --}}
                    <section id="overview">
                        <p>
                            When we refer to <span class="text-white font-semibold">'we', 'us', 'our'</span> and the <span class="text-white font-semibold">'Business' / 'Organisation'</span> in this policy, we mean <span class="text-white font-semibold">TurkFlix Ltd</span>, registered company number 12585638, of 71-75 Shelton Street, London, Greater London, United Kingdom, WC2H 9JQ.
                        </p>
                        <p class="mt-4">
                            Our relationship with our customers and the public at large is of great importance to the organisation. This policy sets out the basis by which any personal data we collect from you, or that you provide to us, will be processed by us.
                        </p>
                        <br>
                    </section>

                    {{-- Data Protection Statement --}}
                    <section id="data-protection">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Data Protection Statement</h2>
                        <div class="space-y-4">
                            <p>
                                TurkFlix Official is committed to protecting your personal information and making sure that our communications with you are secure, proportionate and targeted. This policy explains how we collect, manage and use the personal information you provide to us, whether online, via phone or in person, email, writing or any other correspondence.
                            </p>
                            <p>Most information that we hold will have been obtained directly from you.</p>
                            <p>Staff who handle personal data have received relevant data protection training.</p>
                        </div>
                        <br>
                    </section>

                    {{-- How We Use Your Data --}}
                    <section id="how-we-use">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">How TurkFlix May Use Your Data</h2>
                        <p class="mb-5">We may use your personal data for a number of purposes, including the following:</p>
                        <ul class="space-y-4">
                            @foreach([
                                'To provide you with information about the work and activities of the Organisation. This might include sending you publications, e-newsletters, invitations to events and details of new products on offer.',
                                'To provide you with information by email which we feel may interest you and where you have given your consent to be contacted by email.',
                                'To telephone you if you have provided us with your telephone number and have not registered with the Telephone Preference Service.',
                                'For internal record keeping, including the management of any feedback or complaints.',
                                'For recruitment purposes.',
                                'Electronic tools may also be used to monitor the impact of company communications, such as using email tracking to record when an email we sent to you has been opened.',
                                'We also use your data to ensure that the ways in which we communicate with you do not conflict with your chosen communication channel preferences (for example, by post, telephone or electronic means).',
                                'To target communications and messages to you and to identify similar groups of prospective supporters.',
                                'To compile briefing notes for our staff about guests in advance of meetings, dinners and other events at which supporters and potential supporters may be present.',
                            ] as $point)
                            <li class="flex gap-3">
                                <span class="mt-2 shrink-0 w-1.5 h-1.5 rounded-full bg-[#e63946]"></span>
                                <span>{{ $point }}</span>
                            </li>
                            @endforeach
                        </ul>
                        <br>
                    </section>

                    {{-- What Data We Collect --}}
                    <section id="data-collected">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">What Personal Data Do We Collect?</h2>
                        <div class="space-y-4">
                            <p>
                                We may collect personal information about you when you engage with us for a number of reasons — including if you make an enquiry or subscription, attend one of our events, engage with our social media, sign up for our newsletter, participate in a campaign, or apply for a job with us.
                            </p>
                            <p>
                                This can include information such as your name, postal address, email address, phone number, age, bank details and credit/debit card details, if relevant. In addition to your personal data, we may also ask for your preferences so that we can send you information tailored to your interests.
                            </p>
                            <p>
                                We do not usually collect 'sensitive personal data' from you unless there is a clear reason and we have your explicit consent to do so, or you had already made the personal data manifestly public.
                            </p>

                            <div class="mt-6 p-4 rounded-xl bg-white/[0.03] border border-white/8">
                                <p class="text-white font-semibold text-sm mb-1">Website Users</p>
                                <p>Our web servers use cookies and collect anonymous logs during user visits to our website that provide valuable information for improving them in the future. For more information see our Cookie Policy below.</p>
                            </div>
                        </div>
                        <br>
                    </section>

                    {{-- Third Parties --}}
                    <section id="third-parties">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Your Data and Third Parties</h2>
                        <div class="space-y-4">
                            <p class="text-white/70 font-semibold text-[13px] uppercase tracking-wider">Hosting and Processing</p>
                            <p>
                                Our website and Customer Relationship Management database are hosted by third-party service providers and therefore any personal details you submit may be processed by those providers. We also use other third parties to process your personal details, including:
                            </p>
                            <ul class="space-y-2 ml-1">
                                @foreach(['To process online payments.', 'To process information associated with applications for employment or volunteering opportunities and related recruitment processes.'] as $item)
                                <li class="flex gap-3">
                                    <span class="mt-2 shrink-0 w-1.5 h-1.5 rounded-full bg-[#e63946]"></span>
                                    <span>{{ $item }}</span>
                                </li>
                                @endforeach
                            </ul>
                            <p>All third-party service providers process your personal information only on TurkFlix's behalf and are bound by contractual terms compliant with data protection law.</p>
                            <p>Data will not be disclosed to external organisations other than those acting as agents for TurkFlix Official, or suppliers who manage guest lists for events; with relevant agencies such as the police or security services; or with the principal or any special guest at a specific event.</p>
                            <p class="font-semibold text-white/80">TurkFlix does not sell any of its data to third-party organisations.</p>
                            <p>We may have to share certain information with relevant authorities upon request.</p>

                            <div class="mt-2 p-4 rounded-xl bg-white/[0.03] border border-white/8">
                                <p class="text-white font-semibold text-sm mb-1">Payment Processing &amp; Fraud</p>
                                <p>Where submitted, your card details may be disclosed to banks or relevant financial institutions to arrange payments. In the case of a suspected fraudulent transaction, your details may be further disclosed for the sole purpose of performing further checks.</p>
                            </div>
                            <p>We may also share your personal information with your permission or if we are legally required to disclose it in circumstances where this cannot be reasonably resisted.</p>
                        </div>
                        <br>
                    </section>

                    {{-- How We Contact You --}}
                    <section id="contact">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">How We May Contact You</h2>
                        <div class="space-y-6">
                            <div>
                                <p class="text-white font-semibold text-sm mb-2">By Email</p>
                                <p>If possible, we would like to be able to contact you by email as we feel it is the most efficient and productive way to communicate. We will contact you by email if you have given us your address and we are emailing to provide services you have requested. We may also contact you for marketing purposes by email if you have given us your consent to do so.</p>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm mb-2">By Telephone</p>
                                <p>If you have provided us with your postal address or telephone number, we may send you direct mail or telephone you about our work and fundraising campaigns, unless you have told us that you would prefer not to receive such information. We will carry out a legitimate interest assessment to ensure that you would reasonably expect such communications and would not consider them intrusive.</p>
                            </div>
                        </div>
                        <br>
                    </section>

                    {{-- Retention --}}
                    <section id="retention">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Keeping Your Personal Data</h2>
                        <div class="space-y-4">
                            <p>
                                We keep your personal data for as long as required to operate the service we are providing to you or for archival purposes and in accordance with legal requirements and tax and accounting rules. If we have not heard from you or you have not engaged with us for a period of two years, and we do not need to keep your personal data for archival, legal or business reasons, we will delete or suppress your personal information. Where your information is no longer required, we will ensure it is disposed of in a secure manner.
                            </p>
                            <p>
                                If you have told us that you do not want to hear from us at all or by a particular channel, we will hold the minimum amount of your personal data on a suppression list to ensure we comply with your request.
                            </p>
                        </div>
                        <br>
                    </section>

                    {{-- Your Rights --}}
                    <section id="your-rights">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Your Information Rights</h2>
                        <div class="space-y-6">
                            <div>
                                <p class="text-white font-semibold text-sm mb-2">Do you want us to stop contacting you?</p>
                                <p>
                                    It is your choice as to whether and how you want to receive information about us and our work. You have the right at any time to ask us to amend or stop using your personal information, including for marketing purposes. You may opt out of marketing emails at any time by clicking the 'unsubscribe' link, or by contacting
                                    <a href="mailto:turkflixmembers@gmail.com" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors">turkflixmembers@gmail.com</a>.
                                </p>
                            </div>
                            <div>
                                <p class="text-white font-semibold text-sm mb-2">Do you want a copy of the information we hold for you?</p>
                                <p>
                                    You have a right to request a copy of the personal information we hold about you and to have any inaccuracies corrected or your data erased. We try to respond to all legitimate requests within one month. Occasionally it could take longer if your request is particularly complex. Please provide as much information as possible about the nature of your contact with us to help us locate your records. You will not have to pay a fee to access your personal data. However, we may charge a reasonable fee if your request is clearly unfounded, repetitive or excessive.
                                </p>
                            </div>
                            <p>
                                We may amend this Privacy Policy at any time. Any significant changes will be communicated via our website or by contacting you directly. By continuing to engage with us and our website you will be deemed to have accepted such changes.
                            </p>
                            <p>
                                Please feel free to contact us with any questions related to this policy. If a link on this website takes you to a third-party website, you should refer to their privacy policy. We cannot accept any responsibility for the privacy practices of such third-party websites.
                            </p>
                        </div>
                        <br>
                    </section>

                    {{-- Cookie Policy --}}
                    <section id="cookies">
                        <h2 class="text-white text-lg font-bold mb-4 pb-3 border-b border-white/8">Cookie Policy</h2>
                        <div class="space-y-6" style="display: flex;flex-direction: column;row-gap: 10px;">
                            <p>
                                We are committed to protecting and respecting your privacy in accordance with applicable data protection legislation. This section sets out how we collect, use and share information associated with you through the use of cookies.
                            </p>

                            <div>
                                <p class="text-white font-semibold text-sm mb-2">What Are Cookies</p>
                                <p>It's common practice with almost all websites — this site uses cookies, which are small files downloaded to your computer to improve user experience. This section describes what information they gather, how we use it, and why cookies may need to be stored.</p>
                            </div>

                            <div>
                                <p class="text-white font-semibold text-sm mb-2">How We Use Cookies</p>
                                <p>We use cookies for a variety of reasons. In most cases there are no industry standard options for disabling cookies without completely disabling the functionality they add to this site. It is recommended that you leave all cookies active if you are not sure whether you need them or not.</p>
                            </div>

                            <div>
                                <p class="text-white font-semibold text-sm mb-2">Disabling Cookies</p>
                                <p>You can prevent cookies by adjusting the settings on your browser. Disabling cookies can impact the functionality of websites you visit. Therefore it is recommended that you do not disable cookies.</p>
                            </div>

                            <div>
                                <p class="text-white font-semibold text-sm mb-2">Payment Processing</p>
                                <p>This site offers payment facilities and some cookies are essential to ensure that your payment is remembered between pages so that it can be processed accurately. When you submit data through a form such as those found on contact or payment pages, cookies may be set to remember your user details for future correspondence — with the exception of credit or debit card details.</p>
                            </div>

                            <div>
                                <p class="text-white font-semibold text-sm mb-2">Third-Party Cookies</p>
                                <p class="mb-3">In certain cases we also use cookies provided by trusted third parties:</p>
                                <ul class="space-y-3">
                                    @foreach([
                                        'This site uses Google Analytics, one of the most widespread analytics solutions on the web, to help us understand how you use the site and ways we can improve your experience. These cookies may track things such as how long you spend on the site and the pages you visit.',
                                        'From time to time we test new features and make subtle changes to the way the site is delivered. These cookies may be used to ensure that you receive a consistent experience whilst ensuring we understand which optimisations our users appreciate the most.',
                                        'Several partners advertise on our behalf and affiliate tracking cookies allow us to see if our customers have come to the site through one of our partner sites so that we can credit them appropriately.',
                                    ] as $point)
                                    <li class="flex gap-3">
                                        <span class="mt-2 shrink-0 w-1.5 h-1.5 rounded-full bg-[#e63946]"></span>
                                        <span>{{ $point }}</span>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="p-4 rounded-xl bg-white/[0.03] border border-white/8">
                                <p class="text-white font-semibold text-sm mb-1">Questions</p>
                                <p>
                                    Further information, comments and requests regarding this Cookies Policy are welcome and should be addressed to
                                    <a href="mailto:turkflixmembers@gmail.com" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors">turkflixmembers@gmail.com</a>
                                    or TurkFlix Official, 71-75 Shelton Street, London, Greater London, United Kingdom, WC2H 9JQ.
                                </p>
                            </div>

                            <p class="text-slate-500 text-[13px] border-t border-white/5 pt-4">
                                We may update the terms of this Policy at any time. We will notify you about significant changes by sending a notice to the primary email address you have provided or by placing a prominent notice on our website. By continuing to engage with us through our services and website you will be deemed to have accepted such changes.
                            </p>
                        </div>
                        <br>
                    </section>

                </div>
            </main>
        </div>
    </div>
</div>

@endsection
