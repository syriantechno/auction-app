@extends('admin.layout')

@section('title', 'Global Navigation Control')

@section('content')
<div class="px-1">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Navigation Architect</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-zinc-800 decoration-2 italic italic">Site-wide link infrastructure</p>
        </div>
        <div class="flex gap-2">
            <div class="bg-white px-4 py-2 rounded-md shadow-sm border border-[#f1f5f9] flex items-center gap-2">
                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                <span class="text-[0.6rem] font-black text-[#5a6a85] uppercase tracking-widest">Active Deployment</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @forelse($menus as $menu)
        <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9] hover:border-black/5 transition-all group">
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-md bg-zinc-50 text-black flex items-center justify-center border border-zinc-100 shadow-sm group-hover:scale-110 transition-transform">
                        <i data-lucide="{{ $menu->location == 'header' ? 'layout-template' : 'align-left' }}" class="w-5"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-[0.95rem] text-[#111827]">{{ $menu->name }}</h2>
                        <span class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">{{ $menu->location }} Profile</span>
                    </div>
                </div>
                <span class="px-3 py-1 bg-[#f8fafc] rounded-full text-[0.6rem] font-extrabold text-[#5a6a85] tabular-nums border border-[#f1f5f9]">{{ $menu->items_count }} Active Items</span>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.menus.show', $menu) }}" class="flex-1 py-3 bg-zinc-50 text-[#5a6a85] rounded-md font-black text-[0.65rem] uppercase tracking-widest border border-[#f1f5f9] shadow-sm hover:bg-black hover:text-white transition-all text-center">
                    Customize Links
                </a>
            </div>
        </div>
        @empty
        <div class="md:col-span-2 py-20 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.7rem] bg-white rounded-lg border border-dashed border-[#f1f5f9]">Nav architectural ledger is empty. No zones detected.</div>
        @endforelse
    </div>
</div>
@endsection

