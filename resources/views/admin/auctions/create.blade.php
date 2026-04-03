@extends('admin.layout')

@section('title', 'Launch New Auction')

@section('content')
<div class="px-1">
    <form id="auction-form" action="{{ route('admin.auctions.store') }}" method="POST">
        @csrf
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Launch Auction Cycle</h1>
                <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-zinc-800 decoration-2">Initialize bidding engine</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.auctions.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
                    <i data-lucide="arrow-left" class="w-3.5"></i> Back to Hub
                </a>
                <button type="submit" class="px-6 py-2 bg-black text-white rounded-md font-black shadow-lg hover:bg-zinc-800 transition-all flex items-center gap-2 text-xs">
                    <i data-lucide="zap" class="w-3.5"></i> Launch Live
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Target Asset Selection --}}
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 text-black flex items-center justify-center border border-zinc-100 shadow-sm">
                            <i data-lucide="car" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Target Vehicle Asset</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Select From Inventory</label>
                            @if($cars->count() > 0)
                                <select name="car_id" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm focus:border-zinc-300 outline-none transition-all">
                                    <option value="" disabled selected>— Choose Unit —</option>
                                    @foreach($cars as $car)
                                        <option value="{{ $car->id }}">{{ $car->year }} {{ $car->make }} {{ $car->model }} (VIN: {{ $car->vin }})</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="bg-amber-50 text-amber-600 px-4 py-3 rounded-md border border-amber-100 text-xs font-bold flex items-center gap-2">
                                    <i data-lucide="alert-circle" class="w-4"></i> No vehicles found. <a href="{{ route('admin.cars.create') }}" class="underline font-black">Register one first</a>.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Lifecycle & Timeline --}}
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-red-50 text-red-500 flex items-center justify-center">
                            <i data-lucide="calendar" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Lifecycle & Timeline</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Activation Time</label>
                            <input type="datetime-local" name="start_at" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm focus:border-zinc-300 outline-none">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Cycle Termination</label>
                            <input type="datetime-local" name="end_at" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm focus:border-zinc-300 outline-none">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Financial Matrix --}}
            <div class="space-y-6">
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-lucide="dollar-sign" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Financial Matrix</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Initial Floor Price</label>
                            <input type="number" name="initial_price" placeholder="0.00" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm outline-none transition-all tabular-nums">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Prequalification Deposit</label>
                            <input type="number" name="deposit_amount" placeholder="0.00" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm outline-none transition-all tabular-nums">
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center">
                            <i data-lucide="layers" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Engine Status</h2>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Initial Phase</label>
                            <select name="status" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm outline-none">
                                <option value="coming_soon">Coming Soon (Preview Only)</option>
                                <option value="active">Active (Immediate Bidding)</option>
                                <option value="paused">Paused / Maintenance</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

