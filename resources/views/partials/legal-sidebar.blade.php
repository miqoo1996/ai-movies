<p class="text-[10px] font-black uppercase tracking-[0.15em] text-slate-600 px-3 mb-3">Pages</p>
<nav class="flex flex-col">
    @foreach([
        ['label' => 'About Us',      'href' => '#',        'key' => 'about'],
        ['label' => 'FAQ',            'href' => '/faq',     'key' => 'faq'],
        ['label' => 'Contact Us',     'href' => '/contact', 'key' => 'contact'],
        ['label' => 'Terms of Use',   'href' => '/terms',   'key' => 'terms'],
        ['label' => 'Privacy Policy', 'href' => '/privacy', 'key' => 'privacy'],
        ['label' => 'Thanks',         'href' => '#',        'key' => 'thanks'],
    ] as $link)
    @php $isActive = ($link['key'] === $activePage); @endphp
    <a href="{{ $link['href'] }}"
       class="relative flex items-center gap-3 px-3 py-2.5 text-sm rounded-lg transition-all duration-150
              {{ $isActive ? 'text-white font-semibold' : 'text-slate-500 hover:text-slate-200' }}">
        @if($isActive)
            <span class="absolute left-0 top-1/2 -translate-y-1/2 w-0.5 h-5 bg-[#e63946] rounded-full"></span>
        @endif
        {{ $link['label'] }}
    </a>
    @endforeach
</nav>
