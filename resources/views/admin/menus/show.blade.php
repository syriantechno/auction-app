@extends('admin.layout')

@section('title', 'Refine Menu Structure')

@section('content')
<div class="px-1 max-w-6xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Refine: {{ $menu->name }}</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-zinc-800 decoration-2 italic italic italic">Navigation Infrastructure Workshop</p>
        </div>
        <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
            <i data-lucide="arrow-left" class="w-3.5"></i> Back to Zones
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 text-emerald-600 px-4 py-3 rounded-md mb-6 font-bold border border-emerald-100 flex items-center gap-2 text-xs shadow-sm">
            <i data-lucide="check-circle" class="w-4"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Link Infrastructure List --}}
        <div class="md:col-span-2 space-y-4">
            <div class="bg-white rounded-lg shadow-sm border border-[#f1f5f9] overflow-hidden">
                <div class="p-6 border-b border-[#f1f5f9] bg-[#fcfdfe] flex justify-between items-center">
                    <h3 class="text-[0.7rem] font-black text-[#111827] uppercase tracking-wider">Active Link Ledger</h3>
                </div>
                <div class="divide-y divide-[#f1f5f9]">
                    @forelse($menu->items as $item)
                        <div class="p-4 hover:bg-[#fbfcfe] transition-all">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-3 h-3 rounded-full bg-zinc-100 border border-zinc-200"></div>
                                    <div>
                                        <div class="text-[0.85rem] font-black text-[#111827]">{{ $item->label }}</div>
                                        <div class="text-[0.6rem] text-[#adb5bd] font-bold tabular-nums">Target URL: {{ $item->url ?? '— Internal Command —' }}</div>
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.menus.removeItem', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Archive this link?')" class="w-7 h-7 rounded-lg bg-red-50 text-red-300 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all shadow-sm">
                                            <i data-lucide="trash-2" class="w-3"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            {{-- Dropdown Children --}}
                            @if($item->children->count() > 0)
                            <div class="pl-12 mt-4 space-y-3 border-l-2 border-[#f1f5f9] ml-1.5 pb-2">
                                @foreach($item->children as $child)
                                <div class="flex items-center justify-between group/child">
                                    <div class="flex items-center gap-3">
                                        <div class="w-2 h-2 rounded-full bg-indigo-50 border border-indigo-200"></div>
                                        <div>
                                            <div class="text-[0.75rem] font-bold text-[#5a6a85]">{{ $child->label }}</div>
                                            <div class="text-[0.55rem] text-[#adb5bd] font-bold">{{ $child->url }}</div>
                                        </div>
                                    </div>
                                    <form action="{{ route('admin.menus.removeItem', $child) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-6 h-6 rounded-lg bg-gray-50 text-[#adb5bd] flex items-center justify-center hover:bg-red-500 hover:text-white transition-all">
                                            <i data-lucide="x" class="w-2.5"></i>
                                        </button>
                                    </form>
                                </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @empty
                        <div class="py-12 text-center text-[#adb5bd] font-black uppercase tracking-widest text-[0.6rem]">Zero link infrastructure detected. Platform navigation is static.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Control Module: Add New Link --}}
        <div class="space-y-6">
            <div class="bg-[#111827] rounded-lg p-6 shadow-xl border border-[#111827] ring-4 ring-zinc-900">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-zinc-800 text-[#d9e685] flex items-center justify-center border border-white/10 shadow-sm">
                        <i data-lucide="plus" class="w-4"></i>
                    </div>
                    <h2 class="text-[0.75rem] font-black text-white uppercase tracking-wider">Inject Link Component</h2>
                </div>

                <form action="{{ route('admin.menus.addItem', $menu) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-gray-500 font-black uppercase tracking-widest">Public Link Identification</label>
                        <input type="text" name="label" required placeholder="Header Display Title..." class="w-full bg-zinc-800 border-0 px-4 py-3 rounded-md font-bold text-[#d9e685] text-sm outline-none placeholder:text-gray-600 shadow-inner">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-gray-500 font-black uppercase tracking-widest">Target Operational URL</label>
                        <input type="text" name="url" placeholder="/auctions or https://..." class="w-full bg-zinc-800 border-0 px-4 py-3 rounded-md font-bold text-[#d9e685] text-sm outline-none placeholder:text-gray-600 shadow-inner">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-gray-500 font-black uppercase tracking-widest">Hierarchy Depth</label>
                        <select name="parent_id" class="w-full bg-zinc-800 border-0 px-4 py-3 rounded-md font-bold text-[#d9e685] text-sm outline-none shadow-inner">
                            <option value="">— High-Level Root —</option>
                            @foreach($menu->items->whereNull('parent_id') as $rootItem)
                                <option value="{{ $rootItem->id }}">{{ $rootItem->label }} (Dropdown Item)</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full py-4 bg-zinc-800 text-white rounded-md font-black text-[0.65rem] uppercase tracking-widest shadow-lg hover:bg-black transition-all flex items-center justify-center gap-2 mt-4 hover:ring-1 hover:ring-zinc-700">
                        <i data-lucide="zap" class="w-3.5"></i> Commit Navigation Update
                    </button>
                </form>
            </div>

            <div class="bg-gray-50/50 rounded-lg p-6 border border-dashed border-[#f1f5f9] text-center">
                <span class="text-[0.55rem] text-[#adb5bd] font-black uppercase tracking-widest leading-tight italic">Every commitment here syncs directly with the global CDN & Platform Layout Header.</span>
            </div>
        </div>
    </div>
</div>
@endsection

