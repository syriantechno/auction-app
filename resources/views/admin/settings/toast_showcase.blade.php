@extends('admin.layout')
@section('title', 'Toast Showcase — Choose Your Style')

@section('content')
<div class="pb-20 space-y-8">

    {{-- ══ HEADER ══ --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-8 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="relative">
                <div class="w-14 h-14 rounded-lg bg-[#1d293d] flex items-center justify-center shadow-xl transform rotate-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2.5"><path d="M18 8h1a4 4 0 0 1 0 8h-1"/><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"/><line x1="6" y1="1" x2="6" y2="4"/><line x1="10" y1="1" x2="10" y2="4"/><line x1="14" y1="1" x2="14" y2="4"/></svg>
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-lg bg-orange-400 border-[3px] border-[#f8fafc] animate-pulse"></div>
            </div>
            <div>
                <h1 class="text-4xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    Toast <span class="text-[#ff6900]">Showcase</span>
                </h1>
                <p class="text-slate-400 font-bold text-[0.65rem] uppercase tracking-[0.2em] italic opacity-80 mt-2.5">
                    اختر التصميم المناسب ← يُطبَّق على كل النظام
                </p>
            </div>
        </div>
    </div>

    {{-- ══ INTRO ══ --}}
    <div class="bg-[#f0f2f5] rounded-xl border border-slate-200 px-6 py-4 flex items-center gap-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ff6900" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p class="text-[0.72rem] font-bold text-slate-600">اضغط <strong class="text-[#ff6900]">"جرّب"</strong> على كل تصميم لترى كيف يبدو ← ثم اختر الواحد اللي يعجبك وأخبرني رقمه</p>
    </div>

    {{-- ══ TOAST DESIGNS GRID ══ --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- ═══ STYLE 1: Dark Pill ═══ --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-[#ff6900] transition-all group">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wider">Style 1 — Dark Pill</span>
                    <p class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Dark rounded • Bottom right • Slide up</p>
                </div>
                <button onclick="demo1('success')" class="px-3 py-1.5 bg-[#1d293d] text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    جرّب ✓
                </button>
            </div>
            {{-- Static Preview --}}
            <div class="p-5 bg-[#f0f2f5]">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-3 bg-[#1d293d] text-white px-5 py-3.5 rounded-full shadow-2xl w-fit">
                        <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="text-[0.78rem] font-bold">Settings saved successfully!</span>
                        <button class="ml-2 text-white/40 hover:text-white/80">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </button>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-red-600 text-white px-5 py-3.5 rounded-full shadow-2xl w-fit">
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </div>
                        <span class="text-[0.78rem] font-bold">An error occurred. Try again.</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 border-t border-slate-100">
                <button onclick="demo1('success')" class="text-[0.58rem] font-black text-emerald-600 hover:underline uppercase tracking-widest">✓ Success</button>
                <button onclick="demo1('error')" class="text-[0.58rem] font-black text-red-500 hover:underline uppercase tracking-widest">✗ Error</button>
                <button onclick="demo1('warning')" class="text-[0.58rem] font-black text-amber-500 hover:underline uppercase tracking-widest">⚠ Warning</button>
            </div>
        </div>

        {{-- ═══ STYLE 2: Orange Banner ═══ --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-[#ff6900] transition-all group">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wider">Style 2 — Orange Brand</span>
                    <p class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Branded orange • Top center • Drop down</p>
                </div>
                <button onclick="demo2('success')" class="px-3 py-1.5 bg-[#ff6900] text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-[#e55e00] transition-all">
                    جرّب ✓
                </button>
            </div>
            <div class="p-5 bg-[#f0f2f5]">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-3 bg-[#ff6900] text-white px-5 py-3.5 rounded-xl shadow-xl w-fit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        <div>
                            <div class="text-[0.75rem] font-black uppercase tracking-wider">Saved!</div>
                            <div class="text-[0.6rem] text-white/80 font-medium">Settings saved successfully</div>
                        </div>
                    </div>
                    <div class="inline-flex items-center gap-3 bg-[#1d293d] text-white px-5 py-3.5 rounded-xl shadow-xl w-fit border-l-4 border-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        <div>
                            <div class="text-[0.75rem] font-black uppercase tracking-wider">Error</div>
                            <div class="text-[0.6rem] text-white/70 font-medium">Something went wrong</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 border-t border-slate-100">
                <button onclick="demo2('success')" class="text-[0.58rem] font-black text-emerald-600 hover:underline uppercase tracking-widest">✓ Success</button>
                <button onclick="demo2('error')" class="text-[0.58rem] font-black text-red-500 hover:underline uppercase tracking-widest">✗ Error</button>
                <button onclick="demo2('warning')" class="text-[0.58rem] font-black text-amber-500 hover:underline uppercase tracking-widest">⚠ Warning</button>
            </div>
        </div>

        {{-- ═══ STYLE 3: White Card ═══ --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-[#ff6900] transition-all group">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wider">Style 3 — White Card</span>
                    <p class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Light card + colored bar • Bottom right • Slide up</p>
                </div>
                <button onclick="demo3('success')" class="px-3 py-1.5 bg-[#1d293d] text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    جرّب ✓
                </button>
            </div>
            <div class="p-5 bg-[#f0f2f5]">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-4 bg-white border border-slate-200 px-5 py-3.5 rounded-xl shadow-lg w-fit border-l-4 border-l-emerald-500">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wide">Success</div>
                            <div class="text-[0.62rem] text-slate-500 font-medium">Settings saved successfully!</div>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="2.5" class="ml-2 cursor-pointer"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </div>
                    <div class="inline-flex items-center gap-4 bg-white border border-slate-200 px-5 py-3.5 rounded-xl shadow-lg w-fit border-l-4 border-l-red-500">
                        <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </div>
                        <div>
                            <div class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wide">Error</div>
                            <div class="text-[0.62rem] text-slate-500 font-medium">Something went wrong!</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 border-t border-slate-100">
                <button onclick="demo3('success')" class="text-[0.58rem] font-black text-emerald-600 hover:underline uppercase tracking-widest">✓ Success</button>
                <button onclick="demo3('error')" class="text-[0.58rem] font-black text-red-500 hover:underline uppercase tracking-widest">✗ Error</button>
                <button onclick="demo3('warning')" class="text-[0.58rem] font-black text-amber-500 hover:underline uppercase tracking-widest">⚠ Warning</button>
            </div>
        </div>

        {{-- ═══ STYLE 4: Minimal Bar ═══ --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-[#ff6900] transition-all group">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wider">Style 4 — Minimal Bar</span>
                    <p class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Full width bar • Top of page • No icon</p>
                </div>
                <button onclick="demo4('success')" class="px-3 py-1.5 bg-[#1d293d] text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    جرّب ✓
                </button>
            </div>
            <div class="p-5 bg-[#f0f2f5]">
                <div class="flex flex-col gap-2">
                    <div class="w-full flex items-center gap-3 bg-[#1d293d] text-white px-5 py-3 rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse flex-shrink-0"></span>
                        <span class="text-[0.72rem] font-black uppercase tracking-widest flex-1">Settings saved successfully!</span>
                        <span class="text-[0.55rem] text-white/30 font-bold">just now</span>
                    </div>
                    <div class="w-full flex items-center gap-3 bg-red-600 text-white px-5 py-3 rounded-lg">
                        <span class="w-2 h-2 rounded-full bg-red-200 flex-shrink-0"></span>
                        <span class="text-[0.72rem] font-black uppercase tracking-widest flex-1">Something went wrong!</span>
                        <span class="text-[0.55rem] text-white/50 font-bold">just now</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 border-t border-slate-100">
                <button onclick="demo4('success')" class="text-[0.58rem] font-black text-emerald-600 hover:underline uppercase tracking-widest">✓ Success</button>
                <button onclick="demo4('error')" class="text-[0.58rem] font-black text-red-500 hover:underline uppercase tracking-widest">✗ Error</button>
                <button onclick="demo4('warning')" class="text-[0.58rem] font-black text-amber-500 hover:underline uppercase tracking-widest">⚠ Warning</button>
            </div>
        </div>

        {{-- ═══ STYLE 5: Premium Stacked GLASS ★ FAVOURITE ═══ --}}
        <div class="bg-white rounded-xl border-2 border-[#ff6900] overflow-hidden shadow-lg shadow-orange-100 group col-span-1 md:col-span-2">
            <div class="px-5 py-3 bg-[#1d293d] border-b border-white/10 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="px-2.5 py-1 bg-[#ff6900] text-white rounded-full text-[0.48rem] font-black uppercase tracking-widest">★ مختار</span>
                    <span class="text-[0.72rem] font-black text-white uppercase tracking-wider">Style 5 — Premium Stacked Glass</span>
                    <p class="text-[0.55rem] text-white/40 font-bold uppercase tracking-widest">Glass blur • Icon + Title + Subtitle • Progress bar countdown</p>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="demo5('success')" class="px-3 py-1.5 bg-emerald-500 text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-emerald-600 transition-all">✓ Success</button>
                    <button onclick="demo5('error')" class="px-3 py-1.5 bg-red-500 text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-red-600 transition-all">✗ Error</button>
                    <button onclick="demo5('warning')" class="px-3 py-1.5 bg-amber-400 text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-amber-500 transition-all">⚠ Warning</button>
                </div>
            </div>
            {{-- Glass Preview on dark bg --}}
            <div class="p-8 bg-gradient-to-br from-[#1d293d] via-[#162236] to-[#0d1824] flex items-center justify-center gap-6 flex-wrap">
                {{-- Success --}}
                <div class="inline-flex items-start gap-4 px-5 py-4 rounded-2xl w-72 relative overflow-hidden"
                     style="background:rgba(255,255,255,0.08);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.12);box-shadow:0 25px 50px rgba(0,0,0,0.4);">
                    <div class="absolute bottom-0 left-0 h-0.5 bg-emerald-400" style="width: 55%"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                         style="background:rgba(52,211,153,0.15);border:1px solid rgba(52,211,153,0.3);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#34d399" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 text-white">
                        <div class="text-[0.72rem] font-black uppercase tracking-wider">Saved!</div>
                        <div class="text-[0.62rem] font-medium mt-0.5" style="color:rgba(255,255,255,0.5);">Settings saved successfully</div>
                    </div>
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2.5" class="flex-shrink-0 mt-1"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </div>
                {{-- Error --}}
                <div class="inline-flex items-start gap-4 px-5 py-4 rounded-2xl w-72 relative overflow-hidden"
                     style="background:rgba(239,68,68,0.12);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(239,68,68,0.25);box-shadow:0 25px 50px rgba(0,0,0,0.4);">
                    <div class="absolute bottom-0 left-0 h-0.5 bg-red-400" style="width: 75%"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                         style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#f87171" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 text-white">
                        <div class="text-[0.72rem] font-black uppercase tracking-wider">Error!</div>
                        <div class="text-[0.62rem] font-medium mt-0.5" style="color:rgba(255,255,255,0.5);">Something went wrong. Try again.</div>
                    </div>
                </div>
                {{-- Warning --}}
                <div class="inline-flex items-start gap-4 px-5 py-4 rounded-2xl w-72 relative overflow-hidden"
                     style="background:rgba(245,158,11,0.12);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(245,158,11,0.25);box-shadow:0 25px 50px rgba(0,0,0,0.4);">
                    <div class="absolute bottom-0 left-0 h-0.5 bg-amber-400" style="width: 40%"></div>
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 mt-0.5"
                         style="background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fbbf24" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    </div>
                    <div class="flex-1 min-w-0 text-white">
                        <div class="text-[0.72rem] font-black uppercase tracking-wider">Warning!</div>
                        <div class="text-[0.62rem] font-medium mt-0.5" style="color:rgba(255,255,255,0.5);">Please review before continuing.</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ STYLE 6: Glass Blur ═══ --}}
        <div class="bg-white rounded-xl border-2 border-slate-200 overflow-hidden hover:border-[#ff6900] transition-all group">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-[0.72rem] font-black text-[#1d293d] uppercase tracking-wider">Style 6 — Glass Morphism</span>
                    <p class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Frosted glass • Top right • Fade in</p>
                </div>
                <button onclick="demo6('success')" class="px-3 py-1.5 bg-[#1d293d] text-white rounded-lg text-[0.58rem] font-black uppercase tracking-widest hover:bg-[#ff6900] transition-all">
                    جرّب ✓
                </button>
            </div>
            <div class="p-5 bg-gradient-to-br from-[#1d293d] to-[#0f1a2a]">
                <div class="flex flex-col gap-2">
                    <div class="inline-flex items-center gap-3 backdrop-blur-md bg-white/15 border border-white/20 text-white px-5 py-3.5 rounded-xl shadow-xl w-fit">
                        <div class="w-6 h-6 rounded-full bg-emerald-400 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="text-[0.75rem] font-bold">Settings saved successfully!</span>
                    </div>
                    <div class="inline-flex items-center gap-3 backdrop-blur-md bg-red-500/30 border border-red-400/30 text-white px-5 py-3.5 rounded-xl shadow-xl w-fit">
                        <div class="w-6 h-6 rounded-full bg-red-400 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                        </div>
                        <span class="text-[0.75rem] font-bold">Something went wrong!</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 border-t border-slate-100">
                <button onclick="demo6('success')" class="text-[0.58rem] font-black text-emerald-600 hover:underline uppercase tracking-widest">✓ Success</button>
                <button onclick="demo6('error')" class="text-[0.58rem] font-black text-red-500 hover:underline uppercase tracking-widest">✗ Error</button>
                <button onclick="demo6('warning')" class="text-[0.58rem] font-black text-amber-500 hover:underline uppercase tracking-widest">⚠ Warning</button>
            </div>
        </div>

    </div>

    {{-- ══ CHOOSE CTA ══ --}}
    <div class="bg-[#1d293d] rounded-xl p-6 flex items-center justify-between gap-6">
        <div>
            <div class="text-[0.6rem] font-black text-[#ff6900] uppercase tracking-widest mb-1">الخطوة التالية</div>
            <p class="text-white font-bold text-sm">جرّب كل التصاميم ← بعد ما تختار قلي الرقم (1–6) ← أطبّقه على كل النظام دفعة واحدة</p>
        </div>
        <div class="flex-shrink-0 text-5xl font-black text-[#ff6900] italic">?</div>
    </div>

</div>

{{-- ══ LIVE TOAST CONTAINER ══ --}}
<div id="toastContainer" class="fixed z-[9999] pointer-events-none" style="bottom: 2rem; right: 2rem; display: flex; flex-direction: column-reverse; gap: 0.75rem; max-width: 380px;"></div>

<script>
const TC = document.getElementById('toastContainer');

function makeToast(el, duration = 4000) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(1rem)';
    el.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
    el.style.pointerEvents = 'auto';
    TC.appendChild(el);
    requestAnimationFrame(() => {
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
    });
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(1rem)';
        setTimeout(() => el.remove(), 300);
    }, duration);
}

const colors = {
    success: { bg: '#10b981', label: 'Success', icon: '<polyline points="20 6 9 17 4 12"/>', msg: 'Settings saved successfully!' },
    error:   { bg: '#ef4444', label: 'Error',   icon: '<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>', msg: 'Something went wrong. Try again.' },
    warning: { bg: '#f59e0b', label: 'Warning',  icon: '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>', msg: 'Please review before continuing.' },
};

// Style 1: Dark Pill
function demo1(type) {
    const c = colors[type];
    const el = document.createElement('div');
    el.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;background:#1d293d;color:white;padding:12px 20px;border-radius:9999px;box-shadow:0 20px 40px rgba(0,0,0,0.4);font-family:inherit;">
            <div style="width:24px;height:24px;border-radius:50%;background:${c.bg};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">${c.icon}</svg>
            </div>
            <span style="font-size:0.78rem;font-weight:700;">${c.msg}</span>
        </div>`;
    makeToast(el);
}

// Style 2: Orange Brand
function demo2(type) {
    const c = colors[type];
    const bg = type === 'success' ? '#ff6900' : type === 'error' ? '#1d293d' : '#f59e0b';
    const border = type === 'error' ? 'border-left:4px solid #ef4444;' : '';
    const el = document.createElement('div');
    el.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;background:${bg};${border}color:white;padding:12px 20px;border-radius:12px;box-shadow:0 20px 40px rgba(0,0,0,0.3);font-family:inherit;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">${c.icon}</svg>
            <div>
                <div style="font-size:0.72rem;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;">${c.label}!</div>
                <div style="font-size:0.6rem;opacity:0.8;font-weight:500;">${c.msg}</div>
            </div>
        </div>`;
    makeToast(el);
}

// Style 3: White Card
function demo3(type) {
    const c = colors[type];
    const el = document.createElement('div');
    el.innerHTML = `
        <div style="display:flex;align-items:center;gap:16px;background:white;border:1px solid #e2e8f0;border-left:4px solid ${c.bg};padding:12px 20px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.12);font-family:inherit;min-width:280px;">
            <div style="width:32px;height:32px;border-radius:8px;background:${c.bg}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="${c.bg}" stroke-width="2.5">${c.icon}</svg>
            </div>
            <div style="flex:1;">
                <div style="font-size:0.7rem;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;color:#1d293d;">${c.label}</div>
                <div style="font-size:0.62rem;color:#64748b;font-weight:500;margin-top:2px;">${c.msg}</div>
            </div>
        </div>`;
    makeToast(el);
}

// Style 4: Minimal Bar
function demo4(type) {
    const c = colors[type];
    const bg = type === 'success' ? '#1d293d' : type === 'error' ? '#dc2626' : '#d97706';
    const dotColor = type === 'success' ? '#34d399' : type === 'error' ? '#fca5a5' : '#fde68a';
    const el = document.createElement('div');
    el.style.width = '100%';
    el.style.maxWidth = '380px';
    el.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;background:${bg};color:white;padding:12px 20px;border-radius:10px;font-family:inherit;box-shadow:0 8px 24px rgba(0,0,0,0.25);">
            <span style="width:8px;height:8px;border-radius:50%;background:${dotColor};flex-shrink:0;animation:pulse 1.5s infinite;"></span>
            <span style="font-size:0.7rem;font-weight:900;text-transform:uppercase;letter-spacing:0.12em;flex:1;">${c.msg}</span>
            <span style="font-size:0.52rem;color:rgba(255,255,255,0.3);font-weight:700;">just now</span>
        </div>`;
    makeToast(el);
}

// Style 5: Premium Stacked GLASS
function demo5(type) {
    const c = colors[type];
    const glassColor = type === 'success' ? 'rgba(52,211,153,0.12)' : type === 'error' ? 'rgba(239,68,68,0.12)' : 'rgba(245,158,11,0.12)';
    const glassBorder = type === 'success' ? 'rgba(52,211,153,0.25)' : type === 'error' ? 'rgba(239,68,68,0.25)' : 'rgba(245,158,11,0.25)';
    const el = document.createElement('div');
    el.innerHTML = `
        <div style="display:flex;align-items:flex-start;gap:14px;background:${glassColor};backdrop-filter:blur(24px);-webkit-backdrop-filter:blur(24px);border:1px solid ${glassBorder};color:white;padding:16px 20px;border-radius:18px;box-shadow:0 25px 50px rgba(0,0,0,0.5);font-family:inherit;width:300px;position:relative;overflow:hidden;">
            <div style="position:absolute;bottom:0;left:0;height:2px;background:${c.bg};width:100%;animation:shrinkBar 4s linear forwards;"></div>
            <div style="width:36px;height:36px;border-radius:10px;background:${c.bg}22;border:1px solid ${c.bg}40;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="${c.bg}" stroke-width="2.5">${c.icon}</svg>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.72rem;font-weight:900;text-transform:uppercase;letter-spacing:0.1em;">${c.label}!</div>
                <div style="font-size:0.62rem;color:rgba(255,255,255,0.55);font-weight:500;margin-top:3px;">${c.msg}</div>
            </div>
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.3)" stroke-width="2.5" style="flex-shrink:0;margin-top:2px;cursor:pointer;" onclick="this.closest('[style]').parentElement.remove()"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </div>`;
    makeToast(el);
}

// Style 6: Glass
function demo6(type) {
    const c = colors[type];
    const el = document.createElement('div');
    el.innerHTML = `
        <div style="display:flex;align-items:center;gap:12px;background:rgba(255,255,255,0.12);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.2);color:white;padding:14px 20px;border-radius:14px;box-shadow:0 20px 40px rgba(0,0,0,0.35);font-family:inherit;">
            <div style="width:26px;height:26px;border-radius:50%;background:${c.bg};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3">${c.icon}</svg>
            </div>
            <span style="font-size:0.75rem;font-weight:700;">${c.msg}</span>
        </div>`;
    makeToast(el);
}
</script>

<style>
@keyframes shrinkBar {
    from { width: 100%; }
    to { width: 0%; }
}
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.4; }
}
</style>
@endsection
