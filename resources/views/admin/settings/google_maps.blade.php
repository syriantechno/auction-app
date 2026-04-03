@extends('admin.layout')

@section('title', 'Maps Infrastructure')
@section('page_title', 'Maps Infrastructure')

@section('content')
<div class="px-1 space-y-8 pb-20" x-data="{ 
    activeTab: '{{ ($provider ?? 'google') }}',
    googleKey: '{{ $apiKey }}',
    branchLat: '{{ $branchLat }}',
    branchLng: '{{ $branchLng }}'
}">
    <!-- Header Matrix -->
    <div class="px-1 group">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Mapping Core Ecosystem</h1>
                <p class="text-slate-500 text-[0.7rem] font-bold uppercase tracking-[0.2em] mt-1 italic">V10.2 Hybrid Protocol Management</p>
            </div>
            <a href="{{ route('admin.settings.map-test') }}" class="flex items-center gap-2 px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-500 hover:text-slate-900 rounded-md text-[0.6rem] font-black uppercase tracking-widest transition-all shadow-sm">
                <i data-lucide="activity" class="w-4 h-4"></i> Deep Diagnostics Hub
            </a>
        </div>
    </div>

    <form action="{{ route('admin.settings.google-maps.update') }}" method="POST" class="max-w-6xl grid grid-cols-1 lg:grid-cols-12 gap-10">
        @csrf
        
        <!-- Hidden Inputs for logic -->
        <input type="hidden" name="google_maps_provider" :value="activeTab">

        <!-- Left Column: Navigation & Control -->
        <div class="lg:col-span-4 space-y-5">
            <div class="bg-white p-2 rounded-lg border border-slate-200 shadow-sm space-y-2 overflow-hidden">
                <p class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest px-4 py-3">Select Active Engine</p>
                
                <button type="button" @click="activeTab = 'google'" 
                    :class="activeTab === 'google' ? 'bg-orange-50 border-orange-200' : 'bg-transparent border-transparent grayscale opacity-60'"
                    class="w-full flex items-center gap-4 p-4 rounded-[1.5rem] border-2 transition-all text-left">
                    <div class="w-12 h-12 rounded-md bg-white border border-slate-200 flex items-center justify-center shadow-sm">
                        <img src="https://www.google.com/images/branding/product/2x/maps_96in128dp.png" class="w-6 h-6 object-contain" alt="Google">
                    </div>
                    <div>
                        <div class="text-[0.75rem] font-black text-slate-900 uppercase">Google Enterprise</div>
                        <div class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-tighter">Premium Matrix</div>
                    </div>
                    <template x-if="activeTab === 'google'">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-[#ff6900] ml-auto"></i>
                    </template>
                </button>

                <button type="button" @click="activeTab = 'osm'" 
                    :class="activeTab === 'osm' ? 'bg-emerald-50 border-emerald-200' : 'bg-transparent border-transparent grayscale opacity-60'"
                    class="w-full flex items-center gap-4 p-4 rounded-[1.5rem] border-2 transition-all text-left">
                    <div class="w-12 h-12 rounded-md bg-white border border-slate-200 flex items-center justify-center shadow-sm text-emerald-500">
                        <i data-lucide="globe" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <div class="text-[0.75rem] font-black text-emerald-600 uppercase">OpenStreetMap</div>
                        <div class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-tighter">Failsafe Open Sourcing</div>
                    </div>
                    <template x-if="activeTab === 'osm'">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500 ml-auto"></i>
                    </template>
                </button>
            </div>

            <!-- Common Identity Card -->
            <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center">
                        <i data-lucide="building-2" class="w-4 h-4"></i>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest leading-none">Branch Identity</h3>
                </div>
                
                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest block ml-1">Official Hub Name</label>
                        <input type="text" name="branch_name" value="{{ $branchName }}" class="w-full h-11 bg-slate-50 border border-slate-100 rounded-md px-4 text-[0.8rem] font-bold text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest block ml-1">HQ Coordinates (Lat, Lng)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" name="branch_lat" x-model="branchLat" class="w-full h-11 bg-slate-50 border border-slate-100 rounded-md px-4 text-[0.8rem] font-bold text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all">
                            <input type="text" name="branch_lng" x-model="branchLng" class="w-full h-11 bg-slate-50 border border-slate-100 rounded-md px-4 text-[0.8rem] font-bold text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all">
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="w-full py-5 bg-[#1d293d] text-white rounded-lg text-[0.7rem] font-black uppercase tracking-[0.2em] hover:bg-[#FF6900] active:scale-[0.98] transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-3">
                <i data-lucide="save" class="w-4 h-4"></i> Synchronize Settings
            </button>
        </div>

        <!-- Right Column: Protocol Config & Live Lab -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- Google Configuration Segment -->
            <div x-show="activeTab === 'google'" x-transition class="space-y-6">
                <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
                    <div class="flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-md flex items-center justify-center border border-blue-100 shadow-sm">
                                <i data-lucide="key" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest">Protocol Authorization</h2>
                                <p class="text-[0.65rem] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Enterprise Matrix Key Access</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="relative">
                            <i data-lucide="shield-check" class="w-5 h-5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2"></i>
                            <input type="password" name="google_maps_api_key" x-model="googleKey" id="apiKeyInput" placeholder="Enter Matrix Key (AIzaSy...)" 
                                class="w-full h-14 bg-slate-50 border border-slate-100 rounded-md pl-12 pr-12 text-[0.85rem] font-black text-slate-900 outline-none focus:bg-white focus:border-blue-500 transition-all tracking-widest">
                            <button type="button" onclick="toggleVisibility()" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1d293d] p-2 rounded-lg overflow-hidden aspect-video relative group border-[6px] border-white shadow-2xl">
                    <div class="absolute inset-0 bg-slate-800 opacity-20 group-hover:opacity-10 transition-all"></div>
                    <div class="w-full h-full rounded-lg overflow-hidden bg-slate-800 flex items-center justify-center">
                        <template x-if="googleKey && googleKey.length > 5">
                            <iframe width="100%" height="100%" frameborder="0" style="border:0" 
                                :src="`https://www.google.com/maps/embed/v1/place?key=${googleKey}&q=${branchLat},${branchLng}`" 
                                allowfullscreen>
                            </iframe>
                        </template>
                        <template x-if="!googleKey || googleKey.length <= 5">
                            <div class="text-center space-y-3">
                                <i data-lucide="map-pin-off" class="w-12 h-12 text-slate-600 mx-auto opacity-30 animate-pulse"></i>
                                <p class="text-slate-500 text-[0.7rem] font-black uppercase tracking-widest">Signal Missing: Enter Protocol Key</p>
                            </div>
                        </template>
                    </div>
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-1.5 bg-white/10 backdrop-blur rounded-lg text-[0.55rem] font-black text-white uppercase tracking-widest border border-white/10">Google Embed Previewed</span>
                    </div>
                </div>
            </div>

            <!-- OpenStreetMap Configuration Segment -->
            <div x-show="activeTab === 'osm'" x-transition class="space-y-6">
                <div class="bg-emerald-600 p-10 rounded-lg text-white shadow-2xl relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-2 bg-emerald-400 opacity-50 z-20"></div>
                    <div class="flex items-center gap-8 relative z-10">
                        <div class="w-20 h-20 bg-white/10 rounded-lg flex items-center justify-center border border-white/20 shadow-inner">
                            <i data-lucide="globe" class="w-10 h-10 text-white"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-2xl font-black italic tracking-tight">Open Source Protocol</h3>
                            <p class="text-emerald-100 text-[0.75rem] font-bold leading-relaxed max-w-sm">
                                Switched to **OpenStreetMap** Matrix. No API keys or billing required. Reliability index is currently **Global High**.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-[#1d293d] p-2 rounded-lg overflow-hidden aspect-video relative group border-[6px] border-white shadow-2xl">
                    <div id="osmPreviewMap" class="w-full h-full rounded-lg z-0 bg-slate-800"></div>
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-4 py-1.5 bg-emerald-600 rounded-lg text-[0.55rem] font-black text-white uppercase tracking-widest border border-emerald-400 shadow-xl">OSM Matrix Live Preview</span>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    let osmMap, osmMarker;

    function toggleVisibility() {
        const input = document.getElementById('apiKeyInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') { input.type = 'text'; icon.setAttribute('data-lucide', 'eye-off'); } 
        else { input.type = 'password'; icon.setAttribute('data-lucide', 'eye'); }
        lucide.createIcons();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Alpine initialization logic handled by x-data
        
        // Initialize OSM Preview (Static one-way for display only)
        if (window.L) {
            const coords = [{{ $branchLat }}, {{ $branchLng }}];
            osmMap = L.map('osmPreviewMap', { zoomControl: false, attributionControl: false }).setView(coords, 14);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(osmMap);
            L.marker(coords).addTo(osmMap);
        }
    });
</script>
@endsection

