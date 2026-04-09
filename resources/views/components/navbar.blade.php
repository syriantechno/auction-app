@props([
    'variant' => 'default', // 'default' (Vehica) or 'modern' (Home 2)
    'siteLogo' => null,
    'siteName' => null,
    'phone' => null,
    'hours' => null,
    'whatsapp' => null,
    'menu' => null,
    'socials' => null,
    'isSticky' => true,
    'isGlass' => true,
    'bgColor' => '#ffffff',
    'textColor' => '#0d121f',
    'dotColor' => '#ff6900',
    'logoScale' => 1
])

@php
    // Fallbacks to System Settings if props are missing
    $siteLogo = $siteLogo ?? \App\Models\SystemSetting::get('site_logo');
    $siteName = $siteName ?? \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
    $phone    = $phone    ?? \App\Models\SystemSetting::get('site_phone');
    $whatsapp = $whatsapp ?? \App\Models\SystemSetting::get('whatsapp_number', $phone);
    $hours    = $hours    ?? \App\Models\SystemSetting::get('site_hours', 'Mon - Sat: 9:00 - 18:00');
    
    // Socials fallback
    if (is_null($socials)) {
        $socials = [
            'facebook'  => \App\Models\SystemSetting::get('facebook_url'),
            'instagram' => \App\Models\SystemSetting::get('instagram_url'),
            'tiktok'    => \App\Models\SystemSetting::get('tiktok_url'),
            'youtube'   => \App\Models\SystemSetting::get('youtube_url'),
            'x'         => \App\Models\SystemSetting::get('twitter_url'),
        ];
        $socials = array_filter($socials);
    }
$navSocialMeta = [
    'facebook'  => ['color'=>'#1877f2','title'=>'Facebook', 'path'=>'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
    'instagram' => ['color'=>'#e1306c','title'=>'Instagram','path'=>'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z'],
    'tiktok'    => ['color'=>'#010101','title'=>'TikTok',   'path'=>'M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.16 8.16 0 004.77 1.52V6.75a4.85 4.85 0 01-1-.06z'],
    'youtube'   => ['color'=>'#ff0000','title'=>'YouTube',  'path'=>'M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z'],
    'x'         => ['color'=>'#000000','title'=>'X',        'path'=>'M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.638 7.584H.474l8.6-9.83L0 1.154h7.594l5.243 6.932ZM17.61 20.644h2.039L6.486 3.24H4.298Z'],
    'linkedin'  => ['color'=>'#0a66c2','title'=>'LinkedIn', 'path'=>'M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z'],
    'whatsapp'  => ['color'=>'#25d366','title'=>'WhatsApp','path'=>'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
];
@endphp

@if($variant === 'modern')
    {{-- Modern (Home 2) Rotary Navbar --}}
    @php
        $navStyles = [
            'position' => $isSticky ? 'sticky' : 'relative',
            'top' => $isSticky ? '0' : 'auto',
            'z-index' => '1000',
            'background' => $isGlass ? "color-mix(in srgb, $bgColor, transparent 20%)" : $bgColor,
            'backdrop-filter' => $isGlass ? 'blur(12px) saturate(180%)' : 'none',
            '-webkit-backdrop-filter' => $isGlass ? 'blur(12px) saturate(180%)' : 'none',
            'color' => $textColor,
            '--h2-nav-bg' => $bgColor,
            '--h2-nav-txt' => $textColor,
            '--h2-nav-dot' => $dotColor,
        ];
        
        $inlineStyle = collect($navStyles)->map(fn($v, $k) => "$k: $v")->implode('; ');
    @endphp
    <style>
        .nav {
            padding: 0 12px !important;
            gap: 16px !important;
        }
    </style>
    <nav class="nav" style="{{ $inlineStyle }}">
        <div class="nav-left">
            <a href="/" class="nav-logo">
                @if($siteLogo)
                    <img src="{{ str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo) }}" 
                         alt="{{ $siteName }}"
                         style="height:70px; width:auto; object-fit:contain; transform: scale({{ $logoScale }}); transform-origin: left center;">
                @else
                    <div class="nav-dots">
                        <div class="nav-dot big" style="background: {{ $textColor }}"></div>
                        <div class="nav-dot sm" style="background: {{ $textColor }}; opacity: .5;"></div>
                    </div>
                    <span class="nav-brand" style="color: {{ $textColor }}">{{ $siteName }}</span>
                @endif
            </a>

            <div class="nav-pill">
                <a href="/" class="nav-pill-item {{ request()->is('/') ? 'active' : '' }}" title="Home">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                </a>
                <div class="nav-pill-sep"></div>
                <a href="{{ route('auctions.index') }}" class="nav-pill-item {{ request()->routeIs('auctions.index') ? 'active' : '' }}" title="Auctions">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" /></svg>
                </a>
                @if($phone)
                    <div class="nav-pill-sep"></div>
                    <a href="tel:{{ $phone }}" class="nav-pill-item" title="{{ $phone }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 6.75Z" /></svg>
                    </a>
                @endif
                @if($whatsapp)
                    <div class="nav-pill-sep"></div>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" target="_blank" class="nav-pill-item" title="WhatsApp">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" /></svg>
                    </a>
                @endif
                <div class="nav-pill-sep"></div>
                <a href="#" class="nav-pill-item" title="Location"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg></a>
            </div>
        </div>

        <div class="nav-center">
            @if($menu && $menu->items)
                @foreach($menu->items as $item)
                    <a href="{{ $item->url }}" class="nav-menu-link {{ request()->url() == $item->url ? 'active' : '' }}">
                        {{ $item->label }}
                    </a>
                @endforeach
            @endif
        </div>

        <div class="nav-right">
            <div class="nav-search">
                <input type="text" placeholder="Search Listings...">
                <button class="nav-search-btn">SEARCH</button>
            </div>
            
            @auth
                <div class="relative group py-2">
                    <div class="flex items-center cursor-pointer group/card transition-all">
                        <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-[#ff6900] shadow-lg shadow-orange-500/10 shrink-0 transform group-hover:scale-105 transition-transform">
                            <img src="https://i.pravatar.cc/100?u={{ auth()->id() }}" class="w-full h-full object-cover">
                        </div>
                        <i data-lucide="chevron-down" class="ml-2 w-3 h-3 text-slate-300 group-hover:text-[#ff6900] transition-colors"></i>
                    </div>
                    
                    <div class="absolute right-0 top-full mt-2 w-56 bg-[#031629] rounded-2xl shadow-2xl border border-white/10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60] origin-top-right transform scale-95 group-hover:scale-100">
                        <div class="p-4 border-b border-white/10 mb-1.5 bg-white/5 rounded-xl">
                            <div class="text-[0.8rem] font-bold text-white truncate leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-[0.55rem] text-[#ff6900] font-black uppercase mt-1 tracking-widest">Logged In</div>
                        </div>
                        <div class="space-y-0.5 text-left">
                            <a href="{{ route('dealer.profile', auth()->id()) }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-white/70 hover:bg-white/10 hover:text-white transition-all group/item text-left">
                                <div class="w-7 h-7 rounded-lg bg-orange-500/20 flex items-center justify-center text-[#ff6900] group-hover/item:scale-110 transition-transform flex-shrink-0"><i data-lucide="shield-user" class="w-3"></i></div>
                                <span>My Profile</span>
                            </a>
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-white/70 hover:bg-white/10 hover:text-white transition-all group/item text-left">
                                    <div class="w-7 h-7 rounded-lg bg-blue-500/20 flex items-center justify-center text-blue-400 group-hover/item:scale-110 transition-transform flex-shrink-0"><i data-lucide="cpu" class="w-3"></i></div>
                                    <span>Control Center</span>
                                </a>
                            @endif
                            <div class="pt-2 mt-2 border-t border-white/10">
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-[0.75rem] font-bold text-red-400 hover:bg-red-500/10 transition-all group/item text-left">
                                        <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center text-red-500 group-hover/item:scale-110 transition-transform flex-shrink-0"><i data-lucide="power" class="w-3.5"></i></div>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="nav-contact-btn" title="Login">
                    <i data-lucide="log-in" class="w-4"></i>
                </a>
            @endauth
        </div>
    </nav>
@else
    {{-- Default (Vehica-style) Navbar used on standard layout --}}
    <nav class="{{ $isSticky ? 'sticky-nav' : 'static-nav' }} fixed w-full z-50 px-2 lg:px-4 top-0 transition-all duration-300">
        <div class="w-full flex justify-start items-center h-24 gap-8">
            <a href="/" class="flex items-center gap-2 group">
                <div class="h-20 flex items-center">
                    @if($siteLogo)
                        <img src="{{ asset('storage/' . $siteLogo) }}" class="h-16 w-auto object-contain">
                    @else
                        <div class="w-20 h-20 rounded-lg bg-white flex items-center justify-center text-[#031629] shadow-xl shadow-slate-200/70 border border-slate-200">
                            <i data-lucide="car-front" class="w-10 h-10 text-[#031629]"></i>
                        </div>
                    @endif
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-0.5">
                @if($menu && $menu->items)
                    @foreach($menu->items as $item)
                        @if($item->children->count() > 0)
                            <div class="relative group flex items-center h-full">
                                <button class="nav-link flex items-center gap-1.5 h-full {{ request()->url() == $item->url ? 'nav-link-active' : '' }}">
                                    {{ $item->label }}
                                    <i data-lucide="chevron-down" class="w-2.5 opacity-40 group-hover:rotate-180 transition-transform"></i>
                                </button>
                                <div class="absolute top-[80%] left-0 w-56 bg-white rounded-lg shadow-2xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60]">
                                    @foreach($item->children as $child)
                                        <a href="{{ $child->url }}" class="block px-4 py-3 rounded-md text-[0.7rem] font-black uppercase tracking-wide text-deep-900 hover:bg-orange-50 hover:text-[#ff6900] transition-all">
                                            {{ $child->label }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ $item->url }}" class="nav-link {{ request()->url() == $item->url ? 'text-bazar-500 nav-link-active' : '' }}">
                                {{ $item->label }}
                            </a>
                        @endif
                        @unless($loop->last)
                            <div class="h-4 w-px bg-slate-300/80"></div>
                        @endunless
                    @endforeach
                @endif
            </div>

            @if(!empty($socials))
            <div class="hidden lg:flex items-center gap-1.5 ml-2">
                @foreach($socials as $sk => $nurl)
                    @php $nm = $navSocialMeta[$sk] ?? null; @endphp
                    @if($nm)
                    <a href="{{ $nurl }}" target="_blank" title="{{ $nm['title'] }}"
                       class="w-7 h-7 rounded-full flex items-center justify-center bg-gray-100 hover:bg-[#ff4605] hover:text-white transition-all"
                       style="color: {{ $nm['color'] }}">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $nm['path'] }}"/></svg>
                    </a>
                    @endif
                @endforeach
            </div>
            @endif

            <div class="flex items-center gap-3 ml-auto">
                {{-- Contact sliding card --}}
                <div class="hidden md:flex">
                    <a href="tel:{{ $phone }}" class="flex flex-row-reverse items-center bg-white/95 rounded-full shadow-[0_15px_40px_-12px_rgba(0,0,0,0.12)] border border-white/40 p-1 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] w-[52px] hover:w-[210px] cursor-pointer overflow-hidden group/contact relative z-40">
                        <div class="w-[42px] h-[42px] rounded-full bg-[#ff4605] border border-orange-400/20 flex items-center justify-center shrink-0 z-20 animate-pulse-orange">
                            <i data-lucide="phone-incoming" class="w-4 h-4 text-white"></i>
                        </div>
                        <div class="opacity-0 translate-x-10 group-hover/contact:opacity-100 group-hover/contact:translate-x-0 transition-all duration-500 delay-75 flex-1 px-4 text-right overflow-hidden whitespace-nowrap pointer-events-none">
                            <p class="text-[0.8rem] font-black text-slate-950 tracking-tight leading-none mb-1">{{ $phone }}</p>
                            <p class="text-[0.45rem] font-black text-[#ff4605] uppercase tracking-[0.12em] leading-none text-nowrap opacity-90">{{ $hours }}</p>
                        </div>
                    </a>
                </div>

                @auth
                    <div class="relative group py-2">
                        <div class="flex flex-row-reverse items-center bg-white/95 backdrop-blur-3xl rounded-full shadow-[0_15px_40px_-12px_rgba(0,0,0,0.12)] border border-white/40 p-1 transition-all duration-700 ease-[cubic-bezier(0.34,1.56,0.64,1)] w-[52px] hover:w-[200px] cursor-pointer overflow-hidden group/card relative z-50">
                            <div class="w-[42px] h-[42px] rounded-full overflow-hidden border-2 border-white shadow-lg shrink-0 z-20 transition-transform duration-500 group-hover/card:scale-95 group-hover/card:rotate-3">
                                <img src="https://i.pravatar.cc/100?u={{ auth()->id() }}" class="w-full h-full object-cover">
                            </div>
                            <div class="opacity-0 translate-x-10 group-hover/card:opacity-100 group-hover/card:translate-x-0 transition-all duration-500 delay-75 flex-1 px-4 text-right overflow-hidden whitespace-nowrap pointer-events-none">
                                <p style="color: #ff4605" class="text-[0.45rem] font-bold uppercase tracking-[0.2em] mb-0.5 leading-none opacity-80 text-nowrap">Welcome</p>
                                <p class="text-[0.82rem] font-black text-slate-900 tracking-tighter leading-none text-nowrap">{{ explode(' ', auth()->user()->name)[0] }}</p>
                            </div>
                        </div>
                        <div class="absolute right-0 top-full mt-2 w-60 bg-white rounded-[1.4rem] shadow-[0_40px_90px_-20px_rgba(0,0,0,0.35)] border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 p-2 z-[60] origin-top-right transform scale-95 group-hover:scale-100">
                            <div class="p-4 border-b border-slate-100/50 mb-1.5 bg-slate-50/50 rounded-2xl">
                                <div class="text-[0.85rem] font-black text-slate-950 truncate leading-tight">{{ auth()->user()->name }}</div>
                                <div class="text-[0.55rem] text-slate-400 font-bold uppercase mt-1 tracking-[0.1em]">Identity Verified</div>
                            </div>
                            <div class="space-y-0.5">
                                <a href="{{ route('dealer.profile', auth()->id()) }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-orange-50 hover:text-[#ff4605] transition-all group/item">
                                    <div class="w-7 h-7 rounded-lg bg-orange-100/50 flex items-center justify-center text-orange-600 group-hover/item:scale-110 transition-transform"><i data-lucide="shield-user" class="w-3"></i></div>
                                    Secure Profile
                                </a>
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3.5 py-3 rounded-xl text-[0.7rem] font-bold text-slate-600 hover:bg-slate-50 transition-all group/item">
                                        <div class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 group-hover/item:scale-110 transition-transform"><i data-lucide="cpu" class="w-3"></i></div>
                                        Core Systems
                                    </a>
                                @endif
                                <div class="pt-2 mt-2 border-t border-slate-100/50">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-3.5 rounded-xl text-[0.75rem] font-bold text-red-500 hover:bg-red-50 transition-all group/item">
                                            <div class="w-8 h-8 rounded-lg bg-red-100/50 flex items-center justify-center text-red-600 group-hover/item:scale-110 transition-transform"><i data-lucide="power" class="w-3.5"></i></div>
                                            Terminate Session
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-[0.85rem] font-bold text-deep-900 border-b-2 border-transparent hover:border-[#ff6900] hover:text-[#ff6900] transition-all pb-1 hidden sm:block">Login</a>
                    <a href="{{ route('login', ['redirect' => request()->url()]) }}"
                       class="flex items-center gap-2 px-4 py-2 bg-[#1d293d] hover:bg-[#ff6900] text-white text-[0.72rem] font-black uppercase tracking-widest rounded-lg transition-all shadow-md hover:shadow-orange-500/25 group">
                        <i data-lucide="user-circle" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        <span>My Profile</span>
                    </a>
                @endauth
                <a href="#" class="btn-bazar flex items-center gap-2 animate-pulse-orange">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    <span>Sell My Car</span>
                </a>
            </div>
        </div>
    </nav>
@endif
