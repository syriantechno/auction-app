<div class="bg-white rounded-lg shadow-xl border border-slate-200 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-slate-50 border-b border-slate-200">
                <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] w-16 text-center">Node</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em]">Customer Identity</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em]">Asset Specs</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em]">Technical Stats</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em]">Appointment Hub</th>
                <th class="py-4 px-6 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-center">Status</th>
                <th class="py-4 px-8 text-[0.65rem] text-slate-500 font-black uppercase tracking-[0.2em] text-right">Ops Control</th>
            </tr>
        </thead>
        <tbody id="tableBody" class="divide-y divide-slate-100">
            @forelse($leads as $lead)
            @php
                $details = $lead->car_details ?? [];
                $name = $details['name'] ?? ($lead->user ? $lead->user->name : 'Operator');
                $email = $details['email'] ?? ($lead->user ? $lead->user->email : 'N/A');
                $mileage = $details['mileage'] ?? 'N/A';
                if(is_numeric($mileage)) $mileage = number_format($mileage) . ' KM';
                
                $condition = $details['condition'] ?? 'N/A';
                
                // Numeric Date Formatting (Fix #8)
                $rawDate = $details['inspection_date'] ?? null;
                $appDate = $rawDate ? \Carbon\Carbon::parse($rawDate)->format('d-m-Y') : 'TBD';
                
                $appTime = $details['inspection_time'] ?? '';
                $isHome = ($details['inspection_type'] ?? 'branch') === 'home';
                $address = $details['home_address'] ?? 'Hub Branch';

                // Brand Logo Logic (Fix #3)
                $rawMake = strtolower($details['make'] ?? 'generic');
                $makeSlug = \Illuminate\Support\Str::slug($rawMake);
                $searchPaths = ["images/brands/{$makeSlug}.svg", "images/brands/{$makeSlug}.png"];
                if (str_contains($rawMake, 'mercedes')) $searchPaths[] = "images/brands/mercedes.svg";
                $finalLogo = null;
                foreach ($searchPaths as $path) {
                    if (file_exists(public_path($path))) { $finalLogo = $path; break; }
                }
            @endphp
            <tr class="group hover:bg-slate-50/50 transition-all duration-300 border-l-4 border-l-transparent hover:border-l-[#FF6900]">
                <td class="py-5 px-8 text-center text-slate-300 text-[0.65rem] font-mono group-hover:text-slate-900 transition-colors">#{{ $lead->id }}</td>
                <td class="py-5 px-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-md bg-[#1d293d] flex items-center justify-center text-white shadow-lg">
                            <span class="text-[0.7rem] font-black">{{ mb_substr($name, 0, 1) }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[0.85rem] font-black text-slate-900 tracking-tight">{{ $name }}</span>
                            <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-wider">{{ $details['phone'] ?? 'NO PHONE' }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex items-center gap-3">
                        {{-- Brand Logo --}}
                        <div class="w-10 h-10 rounded-lg bg-slate-50 border border-slate-100 flex items-center justify-center p-1.5 shrink-0">
                            @if($finalLogo)
                                <img src="{{ asset($finalLogo) }}" class="w-full h-full object-contain opacity-60">
                            @else
                                <i data-lucide="car-front" class="w-5 h-5 text-slate-300"></i>
                            @endif
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[0.85rem] font-black text-slate-900 tracking-tighter uppercase">{{ $details['make'] ?? 'Unknown' }}</span>
                            <span class="text-[0.65rem] text-slate-500 font-bold">{{ $details['year'] ?? '' }} {{ $details['model'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col gap-1">
                        <div class="flex items-center gap-2">
                            <i data-lucide="gauge" class="w-3 h-3 text-[#FF6900]"></i>
                            <span class="text-[0.75rem] font-mono text-slate-700 tracking-tighter">{{ $mileage }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full {{ $condition == 'excellent' ? 'bg-emerald-500' : 'bg-orange-400' }}"></div>
                            <span class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">{{ $condition }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="calendar" class="w-3.5 h-3.5 text-blue-500"></i>
                            <span class="text-[0.8rem] font-black text-slate-900 leading-none">{{ $appDate }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            @if($isHome)
                                <span class="bg-orange-50 text-[#FF6900] px-2 py-0.5 rounded-md text-[0.55rem] font-black uppercase tracking-widest border border-orange-100 flex items-center gap-1">
                                    <i data-lucide="home" class="w-2.5 h-2.5"></i> Home
                                </span>
                            @else
                                <span class="bg-slate-50 text-slate-500 px-2 py-0.5 rounded-md text-[0.55rem] font-black uppercase tracking-widest border border-slate-200 flex items-center gap-1">
                                    <i data-lucide="building-2" class="w-2.5 h-2.5"></i> Hub
                                </span>
                            @endif
                            <span class="text-[0.6rem] text-slate-400 font-bold uppercase tracking-widest">{{ $appTime ?: 'ASAP' }}</span>
                        </div>
                    </div>
                </td>
                <td class="py-5 px-6 text-center">
                    @php
                        $statusColors = [
                            'new' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                            'pending' => 'bg-orange-50 text-[#ff6900] border-orange-100',
                            'in_review' => 'bg-blue-50 text-blue-500 border-blue-200',
                            'approved' => 'bg-emerald-900 text-white border-emerald-900',
                            'rejected' => 'bg-red-50 text-red-500 border-red-200',
                            'inspection_scheduled' => 'bg-indigo-50 text-indigo-500 border-indigo-200',
                        ];
                        $col = $statusColors[$lead->status] ?? 'bg-slate-50 text-slate-500 border-slate-100';
                    @endphp
                    <span class="px-4 py-1.5 rounded-md text-[0.65rem] font-black uppercase tracking-widest border shadow-sm {{ $col }}">
                        {{ str_replace('_', ' ', $lead->status) }}
                    </span>
                </td>
                <td class="py-5 px-8 text-right">
                    <div class="flex items-center justify-end gap-3">
                        @if($lead->status === 'new' || $lead->status === 'pending' || $lead->status === 'Active')
                            <button onclick="confirmLead({{ $lead->id }})" title="Confirm & Schedule" class="w-10 h-10 rounded-md bg-orange-50 border border-orange-100 text-[#FF6900] flex items-center justify-center hover:bg-[#FF6900] hover:text-white transition-all shadow-sm active:scale-95 group">
                                <i data-lucide="calendar-check" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                            </button>
                        @endif
                        <button onclick="viewLead({{ $lead->id }})" title="Open Node" class="w-10 h-10 rounded-md bg-white border border-slate-200 text-slate-400 flex items-center justify-center hover:bg-[#1d293d] hover:text-white transition-all shadow-sm active:scale-95 group">
                            <i data-lucide="eye" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                        </button>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $details['phone'] ?? '') }}" target="_blank" class="w-10 h-10 rounded-md bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95 group">
                            <i data-lucide="message-circle" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                        </a>
                        <button onclick="deleteLead({{ $lead->id }})" class="w-10 h-10 rounded-md bg-white border border-red-100 text-red-400 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all shadow-sm active:scale-95 group">
                            <i data-lucide="trash-2" class="w-4.5 h-4.5 transition-transform group-hover:scale-110"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="py-32 text-center bg-slate-50">
                    <div class="flex flex-col items-center gap-6">
                        <div class="relative">
                            <div class="w-20 h-20 bg-white rounded-lg flex items-center justify-center border border-slate-200 shadow-xl">
                                <i data-lucide="layers" class="w-10 text-slate-200"></i>
                            </div>
                            <div class="absolute -right-2 -bottom-2 w-8 h-8 bg-[#ff6900] rounded-full flex items-center justify-center text-white text-[0.6rem] font-black border-4 border-slate-50">0</div>
                        </div>
                        <div class="space-y-1">
                            <h3 class="text-[0.7rem] font-black text-slate-400 uppercase tracking-[0.4em]">No Results</h3>
                            <p class="text-[0.6rem] text-slate-300 font-bold">Waiting for new incoming lead nodes...</p>
                        </div>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div id="paginationContainer">
        @if($leads->hasPages())
        <div class="bg-slate-50 px-10 py-10 border-t border-slate-200 flex items-center justify-center">
            {{ $leads->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>

