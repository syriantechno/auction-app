@extends('admin.layout')

@section('title', 'Register New Car')

@section('content')
<div class="px-1">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Register New Unit</h1>
            <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-lime-500 decoration-2">Direct inventory entry</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.cars.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
                <i data-lucide="arrow-left" class="w-3.5"></i> Back to Ledger
            </a>
            <button form="car-form" class="px-6 py-2 bg-[#111827] text-white rounded-md font-black shadow-lg hover:bg-black transition-all flex items-center gap-2 text-xs">
                <i data-lucide="save" class="w-3.5"></i> Confirm Entry
            </button>
        </div>
    </div>

    <form id="car-form" action="{{ route('admin.cars.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @csrf
        {{-- Technical Profile --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-blue-50 text-[#5d87ff] flex items-center justify-center">
                        <i data-lucide="info" class="w-4"></i>
                    </div>
                    <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Primary Specifications</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Brand / Make</label>
                        <input type="text" name="make" placeholder="e.g. Porsche, Ferrari" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Model Engine</label>
                        <input type="text" name="model" placeholder="e.g. 911 GT3 RS" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Manufacturing Year</label>
                        <input type="number" name="year" value="{{ date('Y') }}" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">VIN Chassis Number</label>
                        <input type="text" name="vin" placeholder="Unique Identification" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#13deb9] flex items-center justify-center">
                        <i data-lucide="gauge" class="w-4"></i>
                    </div>
                    <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Performance & Color</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Mileage (MI)</label>
                        <input type="number" name="mileage" placeholder="0" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Engine Displacement</label>
                        <input type="text" name="engine" placeholder="e.g. 4.0L V8" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Exterior Paint</label>
                        <input type="text" name="exterior_color" placeholder="e.g. Chalk White" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Interior Material</label>
                        <input type="text" name="interior_color" placeholder="e.g. Alcantara Black" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm focus:border-[#d9e685]/50 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        {{-- Visual & Asset Management --}}
        <div class="space-y-6">
            <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-400 flex items-center justify-center">
                        <i data-lucide="image" class="w-4"></i>
                    </div>
                    <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Asset Media</h2>
                </div>
                
                <div class="space-y-4">
                    <div class="aspect-video bg-gray-50 rounded-md border border-dashed border-gray-200 flex flex-col items-center justify-center text-center p-4">
                        <i data-lucide="upload-cloud" class="w-8 text-gray-300 mb-2"></i>
                        <span class="text-[0.6rem] text-gray-400 font-bold uppercase">Main cover image</span>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">External Image URL</label>
                        <input type="url" name="image_url" placeholder="https://..." class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm outline-none transition-all">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-400 flex items-center justify-center">
                        <i data-lucide="settings-2" class="w-4"></i>
                    </div>
                    <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Drive Logistics</h2>
                </div>
                
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Transmission</label>
                        <select name="transmission" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-2.5 rounded-md font-bold text-[#111827] text-sm outline-none">
                            <option value="automatic">Automatic (PDK/ZF)</option>
                            <option value="manual">Manual 6-Speed</option>
                            <option value="dual_clutch">Dual Clutch (DCT)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

