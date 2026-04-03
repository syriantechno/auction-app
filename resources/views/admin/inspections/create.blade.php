@extends('admin.layout')

@section('title', 'Execute Technical Audit')

@section('content')
<div class="px-1 space-y-6">
    <form id="audit-form" action="{{ route('admin.inspections.store') }}" method="POST">
        @csrf
        @if($lead)
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
        @endif
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-black text-[#111827] mb-1 tracking-tight">Execute Technical Audit</h1>
                <p class="text-[0.65rem] text-[#adb5bd] font-black uppercase tracking-widest leading-none underline decoration-emerald-500 decoration-2 italic">Expert condition verification</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.inspections.index') }}" class="px-4 py-2 bg-white text-[#5a6a85] rounded-md font-bold border border-[#f1f5f9] shadow-sm hover:bg-gray-50 flex items-center gap-2 text-xs transition-all">
                    <i data-lucide="arrow-left" class="w-3.5"></i> Back to Archive
                </a>
                <button type="submit" class="px-6 py-3 bg-black text-white rounded-md font-black shadow-lg hover:bg-zinc-800 transition-all flex items-center gap-2 text-xs uppercase tracking-widest">
                    <i data-lucide="verified" class="w-4"></i> Publish Official Report
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Technical Metrics --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Global Asset Identity Card (Replaces Select for better UX) --}}
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-zinc-50 text-black flex items-center justify-center border border-zinc-100 shadow-sm">
                            <i data-lucide="car" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Target Vehicle Asset</h2>
                    </div>
                    
                    @if($selectedCar)
                        <input type="hidden" name="car_id" value="{{ $selectedCar->id }}">
                        <div class="bg-[#f8fafc] border border-[#f1f5f9] p-5 rounded-lg flex items-center gap-6 group transition-all">
                            <div class="w-20 h-20 bg-white rounded-lg flex items-center justify-center border border-zinc-200 shadow-sm overflow-hidden flex-shrink-0">
                                @if($selectedCar->logo_url)
                                     <img src="{{ $selectedCar->logo_url }}" class="w-12 h-12 object-contain opacity-40 grayscale group-hover:grayscale-0 group-hover:opacity-100 transition-all">
                                @else
                                     <i data-lucide="car-front" class="w-10 h-10 text-zinc-200"></i>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="px-2 py-0.5 bg-zinc-800 text-white text-[0.55rem] font-black rounded uppercase tracking-widest italic">{{ $selectedCar->year }}</span>
                                    <span class="text-[0.55rem] text-[#adb5bd] font-black uppercase tracking-widest italic">Identity Verified</span>
                                </div>
                                <h3 class="text-xl font-black text-zinc-900 leading-tight truncate">
                                    {{ $selectedCar->make }} {{ $selectedCar->model }}
                                </h3>
                                <div class="flex items-center gap-3 mt-2">
                                    <div class="flex items-center gap-1.5">
                                        <i data-lucide="fingerprint" class="w-3 h-3 text-zinc-400"></i>
                                        <span class="text-[0.7rem] font-bold text-zinc-500 font-mono tracking-tighter">{{ $selectedCar->vin ?? 'NO-VIN-STAMPED' }}</span>
                                    </div>
                                    <span class="text-zinc-200">|</span>
                                    <div class="text-[0.6rem] font-black text-emerald-600 uppercase tracking-widest italic">Ready for Audit</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Select From Inventory</label>
                            <select name="car_id" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-black text-[#111827] text-sm focus:border-zinc-300 outline-none">
                                <option value="" disabled selected>— Choose Unit —</option>
                                @foreach($cars as $car)
                                    <option value="{{ $car->id }}" {{ ($selectedCar && $selectedCar->id == $car->id) ? 'selected' : '' }}>
                                        {{ $car->year }} {{ $car->make }} {{ $car->model }} (VIN: {{ $car->vin }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                </div>

                {{-- Performance Matrix (Scores) --}}
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100">
                            <i data-lucide="gauge" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Performance Dynamics Scorecard</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 p-2">
                        @foreach(['engine_score' => 'Engine Power', 'paint_score' => 'Paint Integrity', 'transmission_score' => 'Transmission', 'interior_score' => 'Interior Trim', 'tires_score' => 'Tire Profile'] as $key => $label)
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">{{ $label }}</label>
                                <span class="text-[0.65rem] font-black text-[#111827] bg-[#f8fafc] px-3 py-1 rounded-lg border border-[#f1f5f9] tabular-nums" id="{{ $key }}_val">100 / 100</span>
                            </div>
                            <input type="range" name="{{ $key }}" min="0" max="100" value="100" oninput="document.getElementById('{{ $key }}_val').textContent = this.value + ' / 100'" class="w-full h-1.5 bg-[#f1f5f9] rounded-lg appearance-none cursor-pointer accent-emerald-500">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Qualitative Observations --}}
                <div class="bg-white rounded-lg p-6 shadow-sm border border-[#f1f5f9]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-500 flex items-center justify-center border border-blue-100">
                            <i data-lucide="message-square" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-[#111827] uppercase tracking-wider">Qualitative Findings</h2>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Mechanical & Propulsion Observations</label>
                            <textarea name="engine_notes" rows="3" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-bold text-[#111827] text-sm tabular-nums outline-none focus:border-blue-200" placeholder="Leakages, abnormal sounds, fluid purity..."></textarea>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.6rem] text-[#adb5bd] font-black uppercase tracking-widest">Bodywork & Paint Discrepancies</label>
                            <textarea name="body_notes" rows="3" class="w-full bg-[#f8fafc] border border-[#f1f5f9] px-4 py-3 rounded-md font-bold text-[#111827] text-sm tabular-nums outline-none focus:border-blue-200" placeholder="Previous paint jobs, scratches, density..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary & Authority --}}
            <div class="space-y-6">
                @if($lead)
                <div class="bg-orange-50 rounded-lg p-6 border border-orange-100 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center">
                            <i data-lucide="info" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-orange-900 uppercase tracking-wider">Lead Context</h2>
                    </div>
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-orange-800 italic">Source: {{ $lead->car_details['name'] ?? 'Authorized Contact' }}</p>
                        <p class="text-[0.65rem] text-orange-600 font-bold uppercase tracking-widest">ID: #LEAD-{{ $lead->id }}</p>
                    </div>
                </div>
                @endif

                <div class="bg-[#111827] rounded-lg p-6 shadow-xl border border-[#111827]">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-zinc-800 text-white flex items-center justify-center border border-white/10 shadow-sm">
                            <i data-lucide="award" class="w-4"></i>
                        </div>
                        <h2 class="text-[0.75rem] font-black text-white uppercase tracking-wider">Expert Executive Summary</h2>
                    </div>
                    <div class="space-y-4">
                        <textarea name="expert_summary" rows="8" class="w-full bg-zinc-800 border-0 px-4 py-4 rounded-md font-bold text-[#d9e685] text-sm outline-none placeholder:text-gray-500 shadow-inner" placeholder="Provide a high-fidelity summary of the vehicle's collectible or operational value..."></textarea>
                        <div class="bg-zinc-800 p-4 rounded-md border border-white/5 flex flex-col gap-2">
                            <span class="text-[0.55rem] text-gray-500 font-black uppercase tracking-widest">Auditor Credentials</span>
                            <div class="text-[0.7rem] font-bold text-white">{{ Auth::user()->name }} (Head of Technical Ops)</div>
                        </div>
                    </div>
                    <div x-data="{ submitting: false }" class="mt-8">
                        <button type="submit" 
                                @click="submitting = true"
                                :class="submitting ? 'opacity-50 cursor-not-allowed' : ''"
                                class="w-full h-16 bg-[#ff4605] text-white rounded-lg font-black shadow-xl shadow-orange-500/20 hover:scale-[1.02] transition-all flex items-center justify-center gap-4 text-xs uppercase tracking-[0.25em]">
                            <i data-lucide="shield-check" class="w-5 h-5" x-show="!submitting"></i>
                            <span x-show="!submitting">Complete & Finalize Mission</span>
                            <span x-show="submitting" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Archiving Report...
                            </span>
                        </button>
                        <p class="text-center text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest mt-4 opacity-60 italic">Official audit submission will trigger automated auction clearance</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

