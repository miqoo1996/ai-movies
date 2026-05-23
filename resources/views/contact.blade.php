@extends('layouts.app')

@section('title', 'Contact Us — DiziBul')
@section('description', 'Get in touch with the DiziBul team — questions, feedback, or partnership enquiries.')

@section('content')

<div class="pt-[60px] bg-[#080810] min-h-screen">

    {{-- Hero strip --}}
    <div class="border-b border-white/5 bg-[#0a0a14]">
        <div class="max-w-[1400px] mx-auto px-6 py-10">
            <p class="text-[#e63946] text-[11px] font-black uppercase tracking-[0.2em] mb-2">Support</p>
            <h1 class="text-white text-3xl sm:text-4xl font-black tracking-tight">Contact Us</h1>
        </div>
    </div>

    <div class="max-w-[1400px] mx-auto px-6 py-12">
        <div class="flex gap-10 lg:gap-16 items-start">

            {{-- Sidebar --}}
            <aside class="hidden md:block shrink-0 w-52">
                <div class="sticky top-[84px]">
                    @include('partials.legal-sidebar', ['activePage' => 'contact'])
                </div>
            </aside>

            {{-- Main content --}}
            <main class="flex-1 min-w-0">

                <div class="text-slate-400 text-[15px] leading-[1.85] mb-10 max-w-2xl prose prose-invert prose-sm max-w-none">
                    {!! $page->content !!}
                </div>

                {{-- Two-column: form (wide) + info cards (narrow) --}}
                <div style="display:flex; gap:2rem; align-items:flex-start;">

                    {{-- ── Form card ── --}}
                    <div id="contact-card" style="flex:2; min-width:0; background:#0d0d1a; border:1px solid rgba(255,255,255,0.09); border-radius:1rem; padding:2rem;">

                        {{-- Success state (hidden until sent) --}}
                        <div id="contact-success" style="display:none; text-align:center; padding:2rem 1rem;">
                            <div style="width:3.5rem;height:3.5rem;border-radius:50%;background:rgba(16,185,129,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                                <svg width="28" height="28" fill="none" stroke="#34d399" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <h3 style="color:#fff;font-size:1.1rem;font-weight:700;margin-bottom:0.5rem;">Message sent!</h3>
                            <p style="color:#94a3b8;font-size:0.875rem;line-height:1.6;">We'll get back to you within 24 hours.</p>
                        </div>

                        <h2 class="text-white font-bold text-lg mb-6">Send us a message</h2>

                        <form id="contact-form" novalidate>

                            {{-- Name + Email --}}
                            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; margin-bottom:1.25rem;">
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-2">Name <span class="text-[#e63946]">*</span></label>
                                    <input id="cf-name" type="text" placeholder="John Smith" required class="cf-input w-full rounded-lg px-4 py-3 text-[14px] text-white placeholder-slate-600 outline-none">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-2">Email <span class="text-[#e63946]">*</span></label>
                                    <input id="cf-email" type="email" placeholder="you@example.com" required class="cf-input w-full rounded-lg px-4 py-3 text-[14px] text-white placeholder-slate-600 outline-none">
                                </div>
                            </div>

                            {{-- Subject --}}
                            <div style="margin-bottom:1.25rem;">
                                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-2">Subject <span class="text-[#e63946]">*</span></label>
                                <select id="cf-subject" required class="cf-input w-full rounded-lg px-4 py-3 text-[14px] text-slate-300 outline-none cursor-pointer">
                                    <option value="" disabled selected>Choose a topic…</option>
                                    <option value="general">General Enquiry</option>
                                    <option value="subscription">Subscription &amp; Billing</option>
                                    <option value="cancellation">Cancellation Request</option>
                                    <option value="translation">Translation / Subtitle Issue</option>
                                    <option value="request">Series / Movie Request</option>
                                    <option value="technical">Technical Support</option>
                                    <option value="partnership">Partnership / Advertise</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            {{-- Message --}}
                            <div style="margin-bottom:1.5rem;">
                                <label class="block text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-2">Message <span class="text-[#e63946]">*</span></label>
                                <textarea id="cf-message" rows="6" placeholder="Tell us how we can help…" required
                                          class="cf-input w-full rounded-lg px-4 py-3 text-[14px] text-white placeholder-slate-600 outline-none resize-none"></textarea>
                                <p class="text-[12px] text-slate-600 text-right mt-1"><span id="cf-count">0</span> / 1000</p>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-4">
                                <button type="submit" id="cf-submit"
                                        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.75rem 1.75rem; background:#e63946; color:#fff; border-radius:0.5rem; font-size:0.8125rem; font-weight:700; letter-spacing:0.05em; text-transform:uppercase; cursor:pointer; transition:background 0.2s; border:none; white-space:nowrap;"
                                        onmouseover="this.style.background='#cc2f3b'" onmouseout="this.style.background='#e63946'">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                    Send Message
                                </button>
                                <span class="text-[12px] text-slate-600">We'll reply to your email.</span>
                            </div>

                            <div id="cf-feedback" style="display:none; margin-top:1.25rem;" class="rounded-lg px-5 py-4 text-sm font-medium"></div>

                        </form>
                    </div>

                    {{-- ── Info sidebar ── --}}
                    <div style="flex:0 0 260px; display:flex; flex-direction:column; gap:1rem;">

                        {{-- General email --}}
                        <div style="background:#0d0d1a; border:1px solid rgba(255,255,255,0.09); border-radius:0.875rem; padding:1.25rem 1.5rem;">
                            <div style="width:2.5rem; height:2.5rem; border-radius:0.625rem; background:rgba(139,92,246,0.2); display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                                <svg width="20" height="20" fill="none" stroke="#a78bfa" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1">General</p>
                            <a href="mailto:wecare@turkflix.co.uk" class="text-sm text-slate-200 hover:text-white transition-colors">wecare@turkflix.co.uk</a>
                        </div>

                        {{-- Members email --}}
                        <div style="background:#0d0d1a; border:1px solid rgba(255,255,255,0.09); border-radius:0.875rem; padding:1.25rem 1.5rem;">
                            <div style="width:2.5rem; height:2.5rem; border-radius:0.625rem; background:rgba(230,57,70,0.2); display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                                <svg width="20" height="20" fill="none" stroke="#e63946" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1">Members</p>
                            <a href="mailto:turkflixmembers@gmail.com" class="text-sm text-slate-200 hover:text-white transition-colors" style="word-break:break-all;">turkflixmembers@gmail.com</a>
                        </div>

                        {{-- Response time --}}
                        <div style="background:#0d0d1a; border:1px solid rgba(255,255,255,0.09); border-radius:0.875rem; padding:1.25rem 1.5rem;">
                            <div style="width:2.5rem; height:2.5rem; border-radius:0.625rem; background:rgba(16,185,129,0.2); display:flex; align-items:center; justify-content:center; margin-bottom:1rem;">
                                <svg width="20" height="20" fill="none" stroke="#34d399" stroke-width="1.75" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.12em] text-slate-500 mb-1">Response time</p>
                            <p class="text-sm text-slate-200">Within <span style="color:#34d399; font-weight:600;">24 hours</span></p>
                        </div>

                        {{-- Cancellation note --}}
                        <div style="padding:0 0.25rem;">
                            <p class="text-[13px] text-slate-600 leading-relaxed">
                                For cancellations, contact us at least <span class="text-slate-400 font-semibold">48 hours</span> before your next billing date.
                                <a href="/terms#subscriptions" class="text-slate-400 hover:text-white underline underline-offset-2 transition-colors">Terms →</a>
                            </p>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>
</div>

<style>
.cf-input {
    display: block;
    background: #111122;
    border: 1px solid rgba(255,255,255,0.1);
    color: #fff;
    transition: border-color 0.2s, background 0.2s;
}
.cf-input:focus {
    border-color: rgba(139,92,246,0.6);
    background: #13132a;
}
.cf-input option {
    background: #111122;
    color: #cbd5e1;
}
</style>

@endsection

@push('scripts')
<script>
(function () {
    const textarea = document.getElementById('cf-message');
    const counter  = document.getElementById('cf-count');
    const form     = document.getElementById('contact-form');
    const feedback = document.getElementById('cf-feedback');
    const btn      = document.getElementById('cf-submit');

    textarea?.addEventListener('input', () => {
        if (textarea.value.length > 1000) textarea.value = textarea.value.slice(0, 1000);
        counter.textContent = textarea.value.length;
        counter.style.color = textarea.value.length > 900 ? '#e63946' : '';
    });

    form?.addEventListener('submit', e => {
        e.preventDefault();
        const name    = document.getElementById('cf-name').value.trim();
        const email   = document.getElementById('cf-email').value.trim();
        const subject = document.getElementById('cf-subject').value;
        const message = textarea.value.trim();

        if (!name || !email || !subject || !message) {
            show('Please fill in all required fields.', false); return;
        }
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            show('Please enter a valid email address.', false); return;
        }

        btn.disabled = true;
        btn.innerHTML = 'Sending…';

        fetch('{{ route('contact.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ name, email, subject, message }),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Hide everything except the success panel
                document.querySelector('#contact-card h2').style.display = 'none';
                form.style.display = 'none';
                document.getElementById('contact-success').style.display = 'block';
            } else {
                const msg = data.message
                    || (data.errors ? Object.values(data.errors).flat().join(' ') : 'Something went wrong, please try again.');
                show(msg, false);
                btn.disabled = false;
                btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg> Send Message';
            }
        })
        .catch(() => {
            show('Something went wrong, please try again.', false);
            btn.disabled = false;
            btn.innerHTML = '<svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg> Send Message';
        });
    });

    function show(msg, ok) {
        feedback.textContent = msg;
        feedback.style.display = 'block';
        feedback.className = ok
            ? 'rounded-lg px-5 py-4 text-sm font-medium'
            : 'rounded-lg px-5 py-4 text-sm font-medium';
        feedback.style.background    = ok ? 'rgba(16,185,129,0.1)' : 'rgba(230,57,70,0.1)';
        feedback.style.border        = ok ? '1px solid rgba(16,185,129,0.3)' : '1px solid rgba(230,57,70,0.3)';
        feedback.style.color         = ok ? '#34d399' : '#f87171';
        setTimeout(() => { feedback.style.display = 'none'; }, 6000);
    }
})();
</script>
@endpush
