@extends('admin.layout')
@section('title', 'Dealers')
@section('page_title', 'Dealers')

@section('content')
<div class="px-2 space-y-6 pb-20">

    <div class="flex items-center justify-between pb-6 border-b border-slate-100">
        <div class="flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-[#1d293d] flex items-center justify-center shadow-xl shadow-[#031629]/20">
                <i data-lucide="users" class="w-7 h-7 text-[#ff6900]"></i>
            </div>
            <div>
                <h1 class="text-3xl font-black text-[#031629] uppercase italic tracking-tighter leading-none">
                    Dealer <span class="text-[#ff6900]">Registry</span>
                </h1>
                <p class="text-[0.65rem] font-bold text-slate-400 uppercase tracking-widest mt-2">Active bidders on the platform</p>
            </div>
        </div>
        <div class="text-right">
            <div class="text-3xl font-black text-[#031629] tabular-nums">{{ $dealers->total() }}</div>
            <div class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest">Registered Dealers</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @forelse($dealers as $dealer)
        <a href="{{ route('admin.dealers.show', $dealer) }}"
           class="group bg-white border border-slate-100 rounded-2xl p-5 hover:shadow-xl hover:shadow-orange-500/5 hover:border-orange-500/20 transition-all duration-300">

            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 rounded-xl bg-[#1d293d] flex items-center justify-center flex-shrink-0 group-hover:bg-[#ff6900] transition-colors shadow-lg">
                    <span class="text-lg font-black text-white uppercase italic">{{ strtoupper(substr($dealer->name,0,2)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-black text-[#031629] text-sm truncate">{{ $dealer->name }}</div>
                    <div class="text-[0.6rem] font-bold text-slate-400 truncate mt-0.5">{{ $dealer->email }}</div>
                </div>
                <div class="w-8 h-8 bg-slate-50 border border-slate-100 rounded-lg flex items-center justify-center text-slate-300 group-hover:text-[#ff6900] group-hover:border-orange-500/20 transition-all">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-50">
                <div class="text-center">
                    <div class="text-lg font-black text-[#031629] tabular-nums">{{ $dealer->bids_count }}</div>
                    <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">Bids</div>
                </div>
                <div class="text-center border-x border-slate-50">
                    <div class="text-lg font-black text-emerald-600 tabular-nums">
                        {{ \App\Models\Auction::whereHas('negotiation', fn($q) => $q->where('status','closed')->where('winning_bidder_id', $dealer->id))->count() }}
                    </div>
                    <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">Won</div>
                </div>
                <div class="text-center">
                    <div class="text-[0.65rem] font-black text-slate-500 italic">{{ $dealer->created_at->diffForHumans(null, true) }}</div>
                    <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">Joined</div>
                </div>
            </div>

        </a>
        @empty
        <div class="col-span-3 py-24 bg-white rounded-2xl border-2 border-dashed border-slate-100 flex flex-col items-center justify-center gap-5 text-center">
            <i data-lucide="users" class="w-12 h-12 text-slate-200"></i>
            <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No dealers registered yet</p>
        </div>
        @endforelse
    </div>

    @if($dealers->hasPages())
    <div class="flex justify-center">{{ $dealers->links() }}</div>
    @endif

</div>
@endsection
