@extends('layouts.app')

@section('title', 'كيف يعمل أوتومزاد')

@section('content')

{{-- Hero --}}
<section class="relative pt-32 pb-20 overflow-hidden">
    <div class="absolute inset-0 bg-dark-900"></div>
    <div class="absolute inset-0 bg-[url('/images/hero-bg.png')] bg-cover bg-center opacity-[0.05]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-dark-900/80 to-dark-900"></div>
    <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold-400/20 to-transparent"></div>

    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-white mb-6">
            كيف يعمل
            <span class="gold-text">أوتومزاد</span>
        </h1>
        <p class="text-lg text-gray-400 max-w-2xl mx-auto">عملية بسيطة وشفافة من التصفح إلى التملك. نضمن لك تجربة مزايدة آمنة وموثوقة.</p>
    </div>
</section>

{{-- Steps --}}
<section class="relative py-24">
    <div class="absolute inset-0 bg-dark-800"></div>

    <div class="relative max-w-5xl mx-auto px-6 lg:px-8 space-y-16">
        @php
            $steps = [
                [
                    'num' => '01',
                    'title' => 'تصفح السيارات المتاحة',
                    'desc' => 'اكتشف مجموعة واسعة من السيارات الفاخرة المعروضة للمزاد. كل سيارة تأتي مع تقرير فحص شامل بـ 150 نقطة من خبراء معتمدين، صور تفصيلية عالية الجودة، وتاريخ كامل للصيانة.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>',
                ],
                [
                    'num' => '02',
                    'title' => 'سجل وأنشئ حسابك',
                    'desc' => 'أنشئ حسابك المجاني في دقائق. وثّق هويتك وأضف معلومات الدفع. بعد التحقق يمكنك المشاركة في أي مزاد متاح ودفع تأمين المزايدة المطلوب.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                ],
                [
                    'num' => '03',
                    'title' => 'شارك في المزاد المباشر',
                    'desc' => 'عند بدء المزاد، ضع مزايداتك بشكل فوري ومباشر. تابع المزايدات الأخرى في الوقت الحقيقي. يمكنك استخدام المزايدة التلقائية لتحديد سقف أعلى وترك النظام يزايد نيابة عنك.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>',
                ],
                [
                    'num' => '04',
                    'title' => 'ادفع واستلم سيارتك',
                    'desc' => 'عند الفوز بالمزاد، أكمل عملية الدفع الآمنة خلال 48 ساعة. نتكفل بجميع الإجراءات القانونية لنقل الملكية. يمكنك استلام السيارة أو ترتيب الشحن لموقعك.',
                    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
                ],
            ];
        @endphp

        @foreach($steps as $step)
        <div class="flex flex-col md:flex-row gap-8 items-start group">
            {{-- Number & Icon --}}
            <div class="flex-shrink-0 flex items-center gap-6">
                <span class="text-6xl font-black text-white/[0.04] group-hover:text-gold-400/10 transition-colors duration-500">{{ $step['num'] }}</span>
                <div class="w-16 h-16 rounded-lg bg-gold-400/5 border border-gold-400/10 flex items-center justify-center group-hover:bg-gold-400/10 group-hover:border-gold-400/20 transition-all duration-500">
                    <svg class="w-7 h-7 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                </div>
            </div>
            {{-- Content --}}
            <div class="flex-1">
                <h3 class="text-2xl font-bold text-white mb-3 group-hover:text-gold-400 transition-colors duration-300">{{ $step['title'] }}</h3>
                <p class="text-gray-500 leading-relaxed">{{ $step['desc'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="relative py-24 overflow-hidden">
    <div class="absolute inset-0 bg-dark-900"></div>
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-gold-400/20 to-transparent"></div>

    <div class="relative max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-5xl font-black text-white mb-6">
            جاهز للبدء؟
        </h2>
        <p class="text-gray-400 text-lg mb-10">سجل الآن مجاناً وابدأ المزايدة على سيارتك المفضلة</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="/register" class="btn-gold text-lg px-10 py-4">سجل مجاناً</a>
            <a href="/auctions" class="btn-outline-gold text-lg px-10 py-4">تصفح المزادات</a>
        </div>
    </div>
</section>

@endsection

