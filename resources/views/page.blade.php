@extends('layouts.app')

@section('title', $page->title . ' - Motor Bazar')

@section('content')
    {{-- Dynamic Hero Section --}}
    <x-page-hero 
        :title="$page->title" 
        :subtitle="$page->meta_description ?? 'Learn more about ' . $page->title" 
        :image="$page->hero_image ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&q=80&w=1920'"
    />

    {{-- Main Page Content --}}
    <article class="py-24 bg-white relative overflow-hidden">
        {{-- Decorative background --}}
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-gray-50 to-transparent"></div>
        <div class="absolute -right-40 top-40 w-96 h-96 bg-bazar-500/5 rounded-full blur-3xl"></div>
        
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10">
            <div class="bg-white rounded-[40px] p-10 lg:p-20 shadow-2xl shadow-black/5 border border-gray-100 -mt-32 relative">
                <div class="prose prose-xl max-w-none prose-headings:font-black prose-headings:text-deep-900 prose-p:text-gray-600 prose-p:leading-relaxed prose-strong:text-bazar-500 prose-blockquote:border-bazar-500 prose-blockquote:bg-gray-50 prose-blockquote:p-8 prose-blockquote:rounded-lg">
                    @if(is_array($page->content))
                        {{-- If content is dynamic blocks, you'd handle them here --}}
                        @foreach($page->content as $block)
                             {!! $block !!}
                        @endforeach
                    @else
                        {!! $page->content !!}
                    @endif
                </div>

                {{-- Share or Action Section --}}
                <div class="mt-20 pt-10 border-t border-gray-50 flex flex-col md:flex-row justify-between items-center gap-10">
                    <div class="flex items-center gap-6">
                        <span class="text-xs font-black uppercase text-gray-400 tracking-widest">Share this page:</span>
                        <div class="flex gap-3">
                            <a href="#" class="w-12 h-12 rounded-md bg-gray-50 flex items-center justify-center hover:bg-bazar-500 hover:text-white transition-all text-gray-400 border border-gray-100">
                                <i data-lucide="share-2" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="w-12 h-12 rounded-md bg-gray-50 flex items-center justify-center hover:bg-bazar-500 hover:text-white transition-all text-gray-400 border border-gray-100">
                                <i data-lucide="message-circle" class="w-5 h-5"></i>
                            </a>
                            <a href="#" class="w-12 h-12 rounded-md bg-gray-50 flex items-center justify-center hover:bg-bazar-500 hover:text-white transition-all text-gray-400 border border-gray-100">
                                <i data-lucide="mail" class="w-5 h-5"></i>
                            </a>
                        </div>
                    </div>
                    
                    <a href="{{ route('auctions.index') }}" class="btn-bazar flex items-center gap-3">
                        Visit Auctions <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </div>
    </article>

    {{-- Bottom CTA Section --}}
    <section class="py-24 bg-deep-900 overflow-hidden relative">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-bazar-500 to-transparent"></div>
        </div>
        
        <div class="max-w-[1440px] mx-auto px-6 lg:px-12 relative z-10 text-center">
            <h2 class="text-4xl lg:text-5xl font-black text-white mb-8 tracking-tight">Ready to find your <span class="text-bazar-500 italic">dream machine?</span></h2>
            <p class="text-gray-400 text-lg mb-12 max-w-2xl mx-auto">Join thousands of premium car enthusiasts today.</p>
            <div class="flex flex-wrap justify-center gap-6">
                <a href="{{ route('register') }}" class="btn-bazar px-10 py-5 text-sm">Create Account</a>
                <a href="{{ route('how-it-works') }}" class="bg-white/10 hover:bg-white/20 text-white px-10 py-5 rounded-lg font-black text-sm uppercase tracking-widest border border-white/10 transition-all">How it Works</a>
            </div>
        </div>
    </section>
@endsection

