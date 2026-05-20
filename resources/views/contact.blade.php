@extends('layouts.app')

@section('title', 'Contact Us — DiziBul')
@section('description', 'Get in touch with the DiziBul team — questions, feedback, or partnership enquiries.')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Page hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1400px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Support</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Contact Us</h1>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- ── Sidebar ──────────────────────────────────────────── --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => 'contact'])
                </div>
            </aside>

            {{-- ── Content ───────────────────────────────────────────── --}}
            <main class="flex-1 min-w-0">

                {{-- Intro --}}
                <p class="text-slate-400 text-[15px] leading-[1.85] mb-10 max-w-2xl">
                    We'd love to hear from you. Whether you have a question about subscriptions, need help with the website, or just want to say hello — our team is here and will get back to you as soon as possible.
                </p>

                {{-- Info cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-12">

                    {{-- Email --}}
                    <div class="rounded-xl border border-white/10 bg-[#0d0d1a] p-5 flex flex-col gap-3">
                        <div class="w-9 h-9 rounded-lg bg-violet-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1.5">Email us</p>
                            <a href="mailto:wecare@turkflix.co.uk" class="text-sm text-slate-300 hover:text-white transition-colors break-all">wecare@turkflix.co.uk</a>
                        </div>
                    </div>

                    {{-- Response time --}}
                    <div class="rounded-xl border border-white/10 bg-[#0d0d1a] p-5 flex flex-col gap-3">
                        <div class="w-9 h-9 rounded-lg bg-emerald-500/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1.5">Response time</p>
                            <p class="text-sm text-slate-300">Within <span class="text-emerald-400 font-semibold">24 hours</span></p>
                        </div>
                    </div>

                    {{-- Members --}}
                    <div class="rounded-xl border border-white/10 bg-[#0d0d1a] p-5 flex flex-col gap-3">
                        <div class="w-9 h-9 rounded-lg bg-[#e63946]/20 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#e63946]" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1.5">Members support</p>
                            <a href="mailto:turkflixmembers@gmail.com" class="text-sm text-slate-300 hover:text-white transition-colors break-all">turkflixmembers@gmail.com</a>
                        </div>
                    </div>

                </div>

                {{-- Divider --}}
                <div class="flex items-center gap-4 mb-10">
                    <div class="flex-1 h-px bg-white/[0.06]"></div>
                    <span class="text-[11px] font-bold uppercase tracking-[0.15em] text-slate-600 shrink-0">Send us a message</span>
                    <div class="flex-1 h-px bg-white/[0.06]"></div>
                </div>

                {{-- Contact form --}}
                <form id="contact-form" class="max-w-2xl" novalidate>

                    {{-- Name + Email row --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                        <div class="flex flex-col gap-2">
                            <label for="cf-name" class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                                Your Name <span class="text-[#e63946]">*</span>
                            </label>
                            <input id="cf-name" name="name" type="text" placeholder="John Smith" required
                                   style="background:#0f0f1c; border:1px solid rgba(255,255,255,0.1); color:#fff;"
                                   class="w-full rounded-lg px-4 py-3 text-[14px] placeholder-slate-600 outline-none transition-all duration-200 focus:border-violet-500">
                        </div>
                        <div class="flex flex-col gap-2">
                            <label for="cf-email" class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                                Email Address <span class="text-[#e63946]">*</span>
                            </label>
                            <input id="cf-email" name="email" type="email" placeholder="you@example.com" required
                                   style="background:#0f0f1c; border:1px solid rgba(255,255,255,0.1); color:#fff;"
                                   class="w-full rounded-lg px-4 py-3 text-[14px] placeholder-slate-600 outline-none transition-all duration-200 focus:border-violet-500">
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div class="flex flex-col gap-2 mb-5">
                        <label for="cf-subject" class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                            Subject <span class="text-[#e63946]">*</span>
                        </label>
                        <div class="relative">
                            <select id="cf-subject" name="subject" required
                                    style="background:#0f0f1c; border:1px solid rgba(255,255,255,0.1); color:#cbd5e1;"
                                    class="w-full appearance-none rounded-lg px-4 py-3 text-[14px] outline-none transition-all duration-200 focus:border-violet-500 cursor-pointer">
                                <option value="" disabled selected style="background:#0f0f1c; color:#64748b;">Choose a topic…</option>
                                <option value="general"      style="background:#0f0f1c;">General Enquiry</option>
                                <option value="subscription" style="background:#0f0f1c;">Subscription &amp; Billing</option>
                                <option value="cancellation" style="background:#0f0f1c;">Cancellation Request</option>
                                <option value="translation"  style="background:#0f0f1c;">Translation / Subtitle Issue</option>
                                <option value="request"      style="background:#0f0f1c;">Series / Movie Request</option>
                                <option value="technical"    style="background:#0f0f1c;">Technical Support</option>
                                <option value="partnership"  style="background:#0f0f1c;">Partnership / Advertise</option>
                                <option value="other"        style="background:#0f0f1c;">Other</option>
                            </select>
                            <svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="flex flex-col gap-2 mb-6">
                        <label for="cf-message" class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500">
                            Message <span class="text-[#e63946]">*</span>
                        </label>
                        <textarea id="cf-message" name="message" rows="6" placeholder="Tell us how we can help…" required
                                  style="background:#0f0f1c; border:1px solid rgba(255,255,255,0.1); color:#fff;"
                                  class="w-full rounded-lg px-4 py-3 text-[14px] placeholder-slate-600 resize-none outline-none transition-all duration-200 focus:border-violet-500"></textarea>
                        <p class="text-[12px] text-slate-600 text-right"><span id="cf-count">0</span> / 1000</p>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-5">
                        <button type="submit" id="cf-submit"
                                class="inline-flex items-center gap-2.5 px-7 py-3 rounded-lg bg-[#e63946] hover:bg-[#cc2f3b] text-white text-sm font-bold uppercase tracking-wider transition-all duration-200 hover:shadow-lg active:scale-[0.98]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Send Message
                        </button>
                        <p class="text-[12px] text-slate-600">We'll reply to your email address.</p>
                    </div>

                    {{-- Feedback banner --}}
                    <div id="cf-feedback" class="hidden mt-5 rounded-lg px-5 py-4 text-sm font-medium"></div>

                </form>

                {{-- Bottom note --}}
                <div class="mt-14 pt-8 border-t border-white/5">
                    <p class="text-slate-500 text-sm leading-relaxed max-w-xl">
                        For cancellation requests please email
                        <a href="mailto:turkflixmembers@gmail.com" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors">turkflixmembers@gmail.com</a>
                        at least <span class="text-white/70 font-semibold">48 hours</span> before your next billing date.
                        See our <a href="/terms#subscriptions" class="text-slate-300 hover:text-white underline underline-offset-2 transition-colors">Terms of Use</a> for full details.
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
    const textarea = document.getElementById('cf-message');
    const counter  = document.getElementById('cf-count');
    const form     = document.getElementById('contact-form');
    const feedback = document.getElementById('cf-feedback');
    const btn      = document.getElementById('cf-submit');

    // Focus ring on dark inputs
    document.querySelectorAll('#contact-form input, #contact-form select, #contact-form textarea').forEach(el => {
        el.addEventListener('focus',  () => el.style.borderColor = 'rgba(139,92,246,0.6)');
        el.addEventListener('blur',   () => el.style.borderColor = 'rgba(255,255,255,0.1)');
    });

    // Character counter
    textarea?.addEventListener('input', () => {
        const len = Math.min(textarea.value.length, 1000);
        if (textarea.value.length > 1000) textarea.value = textarea.value.slice(0, 1000);
        counter.textContent = len;
        counter.style.color = len > 900 ? '#e63946' : '';
    });

    // Submit
    form?.addEventListener('submit', e => {
        e.preventDefault();
        const name    = document.getElementById('cf-name').value.trim();
        const email   = document.getElementById('cf-email').value.trim();
        const subject = document.getElementById('cf-subject').value;
        const message = textarea.value.trim();

        if (!name || !email || !subject || !message) {
            showFeedback('Please fill in all required fields.', false); return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            showFeedback('Please enter a valid email address.', false); return;
        }

        btn.disabled = true;
        btn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/></svg> Sending…';

        setTimeout(() => {
            showFeedback("Message sent! We'll get back to you within 24 hours.", true);
            form.reset();
            counter.textContent = '0';
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg> Send Message';
        }, 1200);
    });

    function showFeedback(msg, ok) {
        feedback.textContent = msg;
        feedback.className = ok
            ? 'mt-5 rounded-lg px-5 py-4 text-sm font-medium bg-emerald-500/10 border border-emerald-500/30 text-emerald-400'
            : 'mt-5 rounded-lg px-5 py-4 text-sm font-medium bg-[#e63946]/10 border border-[#e63946]/30 text-[#e63946]';
        feedback.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        setTimeout(() => feedback.className = 'hidden', 6000);
    }
})();
</script>
@endpush
