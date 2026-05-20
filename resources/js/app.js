import './bootstrap';
import Swiper from 'swiper';
import { Navigation, Pagination, FreeMode } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/free-mode';

// ── Navbar scroll effect ──────────────────────────────────────────
const navbar = document.getElementById('navbar');
if (navbar) {
    window.addEventListener('scroll', () => {
        navbar.classList.toggle('scrolled', window.scrollY > 20);
    }, { passive: true });
}

// ── Mobile menu toggle ────────────────────────────────────────────
document.getElementById('mobile-menu-btn')?.addEventListener('click', () => {
    document.getElementById('mobile-menu')?.classList.toggle('hidden');
});

// ── Scroll reveal (single items) ──────────────────────────────────
const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            revealObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

document.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));

// ── Staggered children reveal ─────────────────────────────────────
const staggerObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            Array.from(entry.target.children).forEach((child, i) => {
                setTimeout(() => child.classList.add('visible'), i * 80);
            });
            staggerObserver.unobserve(entry.target);
        }
    });
}, { threshold: 0.05, rootMargin: '0px 0px -30px 0px' });

document.querySelectorAll('.stagger').forEach(el => {
    Array.from(el.children).forEach(child => child.classList.add('stagger-item'));
    staggerObserver.observe(el);
});

// ── Top 10 Swiper ─────────────────────────────────────────────────
if (document.querySelector('.top10-swiper')) {
    new Swiper('.top10-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Recently Added Swiper ─────────────────────────────────────────
if (document.querySelector('.recently-added-swiper')) {
    new Swiper('.recently-added-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Classic Turkish Dramas Swiper ─────────────────────────────────
if (document.querySelector('.classic-dramas-swiper')) {
    new Swiper('.classic-dramas-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── For Dizi Newcomers Swiper ─────────────────────────────────────
if (document.querySelector('.dizi-newcomers-swiper')) {
    new Swiper('.dizi-newcomers-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Period Dramas Swiper ──────────────────────────────────────────
if (document.querySelector('.period-dramas-swiper')) {
    new Swiper('.period-dramas-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Streaming on Netflix Swiper ───────────────────────────────────
if (document.querySelector('.netflix-swiper')) {
    new Swiper('.netflix-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Love Is In The Air Swiper ─────────────────────────────────────
if (document.querySelector('.love-swiper')) {
    new Swiper('.love-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Turkish Remakes Swiper ────────────────────────────────────────
if (document.querySelector('.turkish-remakes-swiper')) {
    new Swiper('.turkish-remakes-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Impossible Love Stories Swiper ────────────────────────────────
if (document.querySelector('.impossible-love-swiper')) {
    new Swiper('.impossible-love-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Turkish Daily Dramas Swiper ───────────────────────────────────
if (document.querySelector('.daily-dramas-swiper')) {
    new Swiper('.daily-dramas-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Enemies To Lovers Swiper ──────────────────────────────────────
if (document.querySelector('.enemies-lovers-swiper')) {
    new Swiper('.enemies-lovers-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Family Tree Swiper ────────────────────────────────────────────
if (document.querySelector('.family-tree-swiper')) {
    new Swiper('.family-tree-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Binge-Worthy Swiper ───────────────────────────────────────────
if (document.querySelector('.binge-worthy-swiper')) {
    new Swiper('.binge-worthy-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Watch In One Weekend Swiper ───────────────────────────────────
if (document.querySelector('.one-weekend-swiper')) {
    new Swiper('.one-weekend-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

// ── Gone Too Soon Swiper ──────────────────────────────────────────
if (document.querySelector('.gone-too-soon-swiper')) {
    new Swiper('.gone-too-soon-swiper', {
        modules: [FreeMode],
        slidesPerView: 'auto',
        spaceBetween: 12,
        freeMode: { enabled: true, momentum: true },
        grabCursor: true,
    });
}

