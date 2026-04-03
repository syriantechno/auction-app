@extends('layouts.app')

@section('title', 'Create Account - ' . \App\Models\SystemSetting::get('site_name', 'Motor Bazar'))

@section('content')
<div class="min-h-[calc(100vh-6rem)] grid lg:grid-cols-12 overflow-hidden bg-white">
    <!-- Left: Hero Background (Hidden on Mobile) -->
    <div class="hidden lg:flex lg:col-span-6 relative overflow-hidden bg-deep-950 items-center justify-center p-12">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('images/login_bg.png') }}" class="w-full h-full object-cover opacity-60 scale-105 transform -scale-x-100" alt="Register Background">
            <div class="absolute inset-0 bg-gradient-to-t from-deep-950 via-deep-950/40 to-transparent"></div>
        </div>
        
        <div class="relative z-10 max-w-xl text-white">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-bazar-500/20 border border-bazar-500/20 text-bazar-500 text-xs font-black uppercase tracking-widest mb-6 backdrop-blur-md">
                <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                <span>Join the Elite</span>
            </div>
            <h1 class="text-5xl font-black mb-6 leading-[1.1] tracking-tight">Experience auctions like <span class="text-bazar-500 italic">never before</span>.</h1>
            <ul class="space-y-4 mb-8">
                <li class="flex items-center gap-3 text-gray-300 font-bold">
                    <div class="w-6 h-6 rounded-full bg-bazar-500/20 flex items-center justify-center shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-bazar-500"></i>
                    </div>
                    Real-time bidding on world-class vehicles
                </li>
                <li class="flex items-center gap-3 text-gray-300 font-bold">
                    <div class="w-6 h-6 rounded-full bg-bazar-500/20 flex items-center justify-center shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-bazar-500"></i>
                    </div>
                    Verified inspection reports for every car
                </li>
                <li class="flex items-center gap-3 text-gray-300 font-bold">
                    <div class="w-6 h-6 rounded-full bg-bazar-500/20 flex items-center justify-center shrink-0">
                        <i data-lucide="check" class="w-4 h-4 text-bazar-500"></i>
                    </div>
                    Secure payment and title transfer process
                </li>
            </ul>
        </div>
    </div>

    <!-- Right: Register Form -->
    <div class="lg:col-span-6 flex items-center justify-center p-8 bg-[#fcfdfe]">
        <div class="w-full max-w-[500px]">
            <div class="mb-8 lg:hidden">
                <a href="/" class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-md bg-deep-950 flex items-center justify-center text-white shadow-xl">
                        <span class="font-black italic text-xl tracking-tighter">M</span>
                    </div>
                    <div class="text-xl font-black tracking-tight leading-none text-deep-950">MOTOR<span class="text-bazar-500">BAZAR</span></div>
                </a>
            </div>

            <div class="mb-8">
                <h2 class="text-3xl font-black text-deep-950 mb-3 tracking-tight">أنشئ حسابك</h2>
                <p class="text-gray-500 font-bold text-sm tracking-wide">انضم لآلاف المزايدين وابدأ المزايدة اليوم</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                    </div>
                    <div class="text-sm font-bold text-red-900 pt-2">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                @csrf
                <div class="space-y-1.5 md:col-span-2">
                    <label for="name" class="text-[0.65rem] font-black uppercase tracking-widest text-gray-400 ml-1">Full Name</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-bazar-500 transition-colors">
                            <i data-lucide="user" class="w-5 h-5"></i>
                        </div>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus placeholder="John Doe" 
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-lg focus:outline-none focus:border-bazar-500 focus:ring-4 focus:ring-bazar-500/5 transition-all font-bold text-sm text-deep-950 placeholder:text-gray-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="email" class="text-[0.65rem] font-black uppercase tracking-widest text-gray-400 ml-1">Email</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-bazar-500 transition-colors">
                            <i data-lucide="mail" class="w-5 h-5"></i>
                        </div>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required placeholder="name@company.com" 
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-lg focus:outline-none focus:border-bazar-500 focus:ring-4 focus:ring-bazar-500/5 transition-all font-bold text-sm text-deep-950 placeholder:text-gray-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="phone" class="text-[0.65rem] font-black uppercase tracking-widest text-gray-400 ml-1">Phone Number</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-bazar-500 transition-colors">
                            <i data-lucide="phone" class="w-5 h-5"></i>
                        </div>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="05xxxxxxxx" 
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-lg focus:outline-none focus:border-bazar-500 focus:ring-4 focus:ring-bazar-500/5 transition-all font-bold text-sm text-deep-950 placeholder:text-gray-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="password" class="text-[0.65rem] font-black uppercase tracking-widest text-gray-400 ml-1">Password</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-bazar-500 transition-colors">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password" id="password" required placeholder="••••••••" 
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-lg focus:outline-none focus:border-bazar-500 focus:ring-4 focus:ring-bazar-500/5 transition-all font-bold text-sm text-deep-950 placeholder:text-gray-300">
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label for="password_confirmation" class="text-[0.65rem] font-black uppercase tracking-widest text-gray-400 ml-1">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-bazar-500 transition-colors">
                            <i data-lucide="shield-check" class="w-5 h-5"></i>
                        </div>
                        <input type="password" name="password_confirmation" id="password_confirmation" required placeholder="••••••••" 
                            class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-100 rounded-lg focus:outline-none focus:border-bazar-500 focus:ring-4 focus:ring-bazar-500/5 transition-all font-bold text-sm text-deep-950 placeholder:text-gray-300">
                    </div>
                </div>

                <div class="md:col-span-2 pt-2">
                    <button type="submit" class="w-full btn-bazar group h-14 flex items-center justify-center gap-3">
                        <span>إنشاء الحساب</span>
                        <i data-lucide="user-plus" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    </button>
                </div>
            </form>

            <div class="mt-8 text-center">
                <p class="text-sm font-bold text-gray-400">
                    لديك حساب بالفعل؟ 
                    <a href="{{ route('login') }}" class="text-bazar-500 hover:text-bazar-600 ml-1">سجل دخولك الآن</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if(typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection


