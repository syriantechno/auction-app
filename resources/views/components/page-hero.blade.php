@props(['title' => 'Page Title', 'subtitle' => 'Description for this page', 'image' => 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&q=80&w=1920'])

<section class="relative h-[550px] flex items-center overflow-hidden bg-deep-900 border-b border-white/5">
    {{-- Background with Parallax Effect --}}
    <div class="absolute inset-0">
        <img src="{{ $image }}" class="w-full h-full object-cover opacity-30 transform scale-105 hover:scale-100 transition-transform duration-[5s]" alt="{{ $title }}">
        {{-- Overlays --}}
        <div class="absolute inset-0 bg-gradient-to-t from-deep-950 via-deep-950/40 to-transparent"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-deep-950 via-deep-950/20 to-transparent"></div>
        
        {{-- Animated Decorative Blobs --}}
        <div class="absolute top-20 right-[-10%] w-[500px] h-[500px] bg-bazar-500/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[400px] h-[400px] bg-white/5 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-[1440px] mx-auto px-6 lg:px-12 w-full relative z-10">
        <div class="max-w-3xl">
            {{-- Breadcrumbs / Tag --}}
            <div class="flex items-center gap-4 mb-8">
                <span class="px-4 py-1.5 rounded-full bg-bazar-500/10 border border-bazar-500/20 text-bazar-500 text-[0.65rem] font-black uppercase tracking-[0.2em]">Explore Motor Bazar</span>
                <div class="h-[1px] w-12 bg-white/10"></div>
                <nav class="flex items-center gap-2 text-gray-500 text-[0.65rem] font-bold uppercase tracking-widest">
                    <a href="/" class="hover:text-white transition-colors">Home</a>
                    <i data-lucide="chevron-right" class="w-2.5 h-2.5"></i>
                    <span class="text-white">{{ $title }}</span>
                </nav>
            </div>

            {{-- Title with Dynamic Underline --}}
            <div class="relative inline-block mb-8">
                <h1 class="text-6xl lg:text-[5.5rem] font-black text-white tracking-tighter leading-[0.95]">
                    {{ $title }}
                </h1>
                <div class="absolute -bottom-4 left-0 w-24 h-2 bg-bazar-500 rounded-full"></div>
            </div>
            
            <p class="text-xl lg:text-2xl text-gray-400 font-medium leading-relaxed max-w-2xl mb-12">
                {{ $subtitle }}
            </p>

            <div class="flex items-center gap-8">
                <div class="flex -space-x-3">
                    <img src="https://i.pravatar.cc/100?u=1" class="w-10 h-10 rounded-full border-2 border-deep-900 shadow-xl">
                    <img src="https://i.pravatar.cc/100?u=2" class="w-10 h-10 rounded-full border-2 border-deep-900 shadow-xl">
                    <img src="https://i.pravatar.cc/100?u=3" class="w-10 h-10 rounded-full border-2 border-deep-900 shadow-xl">
                    <div class="w-10 h-10 rounded-full bg-bazar-500 border-2 border-deep-900 shadow-xl flex items-center justify-center text-[0.6rem] font-black text-white">+5k</div>
                </div>
                <div class="text-[0.65rem] font-black uppercase text-gray-500 tracking-[0.1em]">Joined by <span class="text-white">5,000+</span> enthusiasts</div>
            </div>
        </div>
    </div>

    {{-- Floating Decorative Element --}}
    <div class="absolute bottom-16 right-12 hidden xl:block">
        <div class="relative">
            <div class="w-48 h-48 rounded-full border border-white/5 animate-[spin_20s_linear_infinite] flex items-center justify-center">
                <div class="absolute inset-0 flex items-center justify-center">
                    <svg viewBox="0 0 100 100" class="w-44 h-44 fill-white/10">
                        <path id="circlePath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="transparent"/>
                        <text class="text-[10px] font-black uppercase tracking-[4px]">
                            <textPath xlink:href="#circlePath">
                                Premium Car Auction • Live Bidding • 
                            </textPath>
                        </text>
                    </svg>
                </div>
            </div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-16 h-16 rounded-lg bg-bazar-500 shadow-[0_0_40px_rgba(255,70,5,0.4)] flex items-center justify-center text-white transform rotate-12">
                    <i data-lucide="star" class="w-8 h-8 fill-white"></i>
                </div>
            </div>
        </div>
    </div>
</section>

