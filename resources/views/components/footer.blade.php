@props([
    'variant' => 'default', // 'default' or 'modern' (Home 2)
    'siteLogo' => null,
    'siteName' => null,
    'description' => null,
    'address' => null,
    'email' => null,
    'phone' => null,
    'socials' => null,
    'quickLinks' => [],
    'pages' => [],
    'copyright' => null,
    'termsUrl' => '#',
    'privacyUrl' => '#',
    'cookiesUrl' => '#',
    'bgColor' => '#eef3f9'
])

@php
    $siteLogo    = $siteLogo    ?? \App\Models\SystemSetting::get('site_logo');
    $siteName    = $siteName    ?? \App\Models\SystemSetting::get('site_name', 'Motor Bazar');
    $description = $description ?? \App\Models\SystemSetting::get('footer_description', 'Your premium automotive marketplace.');
    $address     = $address     ?? \App\Models\SystemSetting::get('site_address');
    $email       = $email       ?? \App\Models\SystemSetting::get('site_email');
    $phone       = $phone       ?? \App\Models\SystemSetting::get('site_phone');
    $copyright   = $copyright   ?? \App\Models\SystemSetting::get('footer_copyright', '© '.date('Y').' Motor Bazar. All rights reserved.');
    
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
@endphp

@if($variant === 'modern')
    {{-- Modern (Home 2) Ultra-Premium Footer --}}
    <footer class="h2-footer-root" style="background-color: {{ $bgColor }} !important;">
        <div class="h2-section-container w-full px-6 lg:px-12">
            <div class="footer-card">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                    {{-- Column 1: Brand --}}
                    <div class="space-y-6">
                        @if($siteLogo)
                            <img src="{{ str_starts_with($siteLogo, 'http') ? $siteLogo : asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="h-14 w-auto object-contain">
                        @else
                            <div class="w-12 h-12 rounded-xl bg-[#031629] flex items-center justify-center text-white shadow-lg">
                                <i data-lucide="car-front" class="w-6 h-6"></i>
                            </div>
                        @endif
                        <p class="text-slate-500 font-medium text-sm leading-relaxed">{{ $description }}</p>
                    </div>

                    {{-- Column 2: Quick Links --}}
                    <div>
                        <span class="footer-header-lux">Quick Links</span>
                        <div class="flex flex-col">
                            @foreach($quickLinks as $link)
                                <a href="{{ data_get($link,'url','#') }}" class="footer-link-lux">{{ data_get($link,'label','') }}</a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Column 3: Contact & Support --}}
                    <div>
                        <span class="footer-header-lux">Direct Contact</span>
                        <div class="space-y-4">
                            @if($phone)
                                <div class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6900] group-hover:bg-[#ff6900] group-hover:text-white transition-all">
                                        <i data-lucide="phone" class="w-4 h-4"></i>
                                    </div>
                                    <a href="tel:{{ $phone }}" class="text-slate-600 font-bold text-sm">{{ $phone }}</a>
                                </div>
                            @endif
                            @if($email)
                                <div class="flex items-center gap-3 group">
                                    <div class="w-10 h-10 rounded-lg bg-orange-50 flex items-center justify-center text-[#ff6900] group-hover:bg-[#ff6900] group-hover:text-white transition-all">
                                        <i data-lucide="mail" class="w-4 h-4"></i>
                                    </div>
                                    <a href="mailto:{{ $email }}" class="text-slate-600 font-bold text-sm">{{ $email }}</a>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Column 4: Location --}}
                    <div>
                        <span class="footer-header-lux">Our Location</span>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-4">{{ $address }}</p>
                        <div class="flex gap-2">
                            @foreach(['facebook', 'instagram', 'tiktok', 'youtube'] as $sk)
                                @if(isset($socials[$sk]))
                                    <a href="{{ $socials[$sk] }}" target="_blank" class="social-item-lux">
                                        <i data-lucide="{{ $sk === 'x' ? 'twitter' : $sk }}" class="w-4.5 h-4.5"></i>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="footer-bottom-lux">
                <p>{!! $copyright !!}</p>
                <div class="flex gap-6">
                    <a href="{{ $termsUrl }}" class="hover:text-[#ff6900] transition-colors">Terms</a>
                    <a href="{{ $privacyUrl }}" class="hover:text-[#ff6900] transition-colors">Privacy</a>
                    <a href="{{ $cookiesUrl }}" class="hover:text-[#ff6900] transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
@else
    {{-- Default (Vehica-style) Footer --}}
    <footer class="text-slate-900 pt-20 pb-12 overflow-hidden relative transition-colors duration-500" style="background-color: {{ $bgColor }};">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-bazar-500/5 to-transparent pointer-events-none"></div>
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-16">

                {{-- Column 1: Brand --}}
                <div class="lg:col-span-2 space-y-6">
                    <a href="/" class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-lg bg-white flex items-center justify-center shadow-xl border border-slate-200">
                             @if($siteLogo)
                                <img src="{{ asset('storage/' . $siteLogo) }}" class="h-8 w-auto">
                             @else
                                <i data-lucide="car-front" class="w-7 h-7 text-[#031629]"></i>
                             @endif
                        </div>
                    </a>
                    <p class="text-slate-600 text-sm leading-relaxed font-medium max-w-sm">{{ $description }}</p>
                    <div class="flex gap-3 flex-wrap">
                        @foreach($socials as $sk => $surl)
                             <a href="{{ $surl }}" target="_blank" class="w-9 h-9 rounded-full bg-white flex items-center justify-center hover:bg-[#ff6900] hover:text-white transition-all border border-slate-200 shadow-sm">
                                <i data-lucide="{{ $sk === 'x' ? 'twitter' : $sk }}" class="w-4 h-4"></i>
                             </a>
                        @endforeach
                    </div>
                </div>

                {{-- Column 2: Quick Links --}}
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Quick Links
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        @foreach($quickLinks as $link)
                        <li>
                            <a href="{{ data_get($link,'url','#') }}" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-[#ff6900] group-hover:translate-x-1 transition-transform shrink-0"></i>
                                {{ data_get($link,'label','') }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Column 3: Pages --}}
                <div>
                    @if(!empty($pages))
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Pages
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-3">
                        @foreach($pages as $pg)
                        <li>
                            <a href="{{ data_get($pg,'url','#') }}" class="text-slate-600 hover:text-slate-900 transition-all text-sm font-semibold flex items-center gap-2 group">
                                <i data-lucide="chevron-right" class="w-3 h-3 text-[#ff6900] group-hover:translate-x-1 transition-transform shrink-0"></i>
                                {{ data_get($pg,'label','') }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>

                {{-- Column 4: Contact --}}
                <div>
                    <h4 class="text-sm font-black uppercase tracking-widest mb-6 text-slate-800">
                        Contact Us
                        <div class="h-0.5 w-6 bg-[#ff6900] rounded-full mt-2"></div>
                    </h4>
                    <ul class="space-y-4">
                        @if($address)
                        <li class="flex items-start gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-[#ff6900] mt-0.5 shrink-0"></i>
                            <span class="text-sm font-medium text-slate-600">{{ $address }}</span>
                        </li>
                        @endif
                        @if($email)
                        <li class="flex items-center gap-3">
                            <i data-lucide="mail" class="w-4 h-4 text-[#ff6900] shrink-0"></i>
                            <a href="mailto:{{ $email }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">{{ $email }}</a>
                        </li>
                        @endif
                        @if($phone)
                        <li class="flex items-center gap-3">
                            <i data-lucide="phone" class="w-4 h-4 text-[#ff6900] shrink-0"></i>
                            <a href="tel:{{ $phone }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">{{ $phone }}</a>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest">{!! $copyright !!}</p>
                <div class="flex gap-6">
                    <a href="{{ $termsUrl }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Terms</a>
                    <a href="{{ $privacyUrl }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Privacy</a>
                    <a href="{{ $cookiesUrl }}" class="text-slate-500 hover:text-slate-900 transition-all text-[0.65rem] font-black uppercase tracking-widest">Cookies</a>
                </div>
            </div>
        </div>
    </footer>
@endif
