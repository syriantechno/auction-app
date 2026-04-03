@extends('admin.layout')

@section('title', 'Deep Map Diagnostics')
@section('page_title', 'Per-Vendor Map Lab')

@section('content')
<div class="px-1 space-y-8 pb-20">
    <!-- Header -->
    <div class="px-1">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white border border-slate-200 rounded-lg flex items-center justify-center shadow-sm text-[#FF6900]">
                    <i data-lucide="microscope" class="w-7 h-7"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Per-Engine Calibration</h1>
                    <p class="text-[0.65rem] font-bold uppercase tracking-[0.3em] text-slate-400 mt-1">Search & GPS</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.settings.google-maps') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-900 rounded-md text-[0.65rem] font-black uppercase tracking-widest hover:bg-slate-50 transition-all">
                    Exit Lab
                </a>
            </div>
        </div>
    </div>

    <!-- Isolated Lab Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Google Maps Lab -->
        <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <img src="https://www.google.com/images/branding/product/2x/maps_96in128dp.png" class="w-6 h-6 object-contain" alt="Google">
                    <h3 class="text-[0.8rem] font-black text-slate-800 uppercase tracking-widest italic">Google Engine</h3>
                </div>
            </div>

            <!-- Google Local Search & Detect -->
            <div class="flex gap-3">
                <div class="flex-1 relative">
                    <i data-lucide="search" class="w-4.5 h-4.5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 transition-colors"></i>
                    <input type="text" id="googleSearchInput" placeholder="Search Google Maps..." 
                        class="w-full h-12 bg-slate-50 border border-slate-100 rounded-md pl-11 pr-5 text-[0.8rem] font-medium text-slate-700 outline-none focus:bg-white focus:border-orange-500 transition-all shadow-inner">
                </div>
                <button onclick="locateGoogle()" class="w-12 h-12 bg-slate-100 border border-slate-200 rounded-md flex items-center justify-center hover:bg-[#ff6900] hover:text-white transition-all text-slate-500 shadow-sm">
                    <i data-lucide="locate" class="w-5 h-5"></i>
                </button>
            </div>
            
            <div class="rounded-lg border border-slate-100 overflow-hidden aspect-video relative group">
                <div id="googleMapTest" class="w-full h-full bg-slate-50 flex flex-col items-center justify-center text-slate-400 text-center p-8">
                    @if(!$apiKey) 
                        <span class="text-sm italic">API Key Missing</span>
                    @else 
                        <div class="animate-pulse">Booting Engine...</div>
                    @endif
                </div>
            </div>
            <p id="googleCoords" class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">Lat: --, Lng: --</p>
        </div>

        <!-- OpenStreetMap Lab (Enhanced) -->
        <div class="bg-white p-8 rounded-lg border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 text-emerald-600">
                    <i data-lucide="globe" class="w-6 h-6"></i>
                    <h3 class="text-[0.8rem] font-black text-slate-800 uppercase tracking-widest italic">OSM Open Protocol (Fully Active)</h3>
                </div>
                <span class="text-[0.55rem] font-black bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded uppercase">Full Manual Capture</span>
            </div>

            <!-- OSM Local Search & Detect -->
            <div class="space-y-2 relative">
                <div class="flex gap-3">
                    <div class="flex-1 relative">
                        <i data-lucide="search" class="w-4.5 h-4.5 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2 transition-colors"></i>
                        <input type="text" id="osmSearchInput" placeholder="Type city or area & wait for suggestions..." 
                            class="w-full h-12 bg-slate-50 border border-slate-100 rounded-md pl-11 pr-5 text-[0.8rem] font-medium text-slate-700 outline-none focus:bg-white focus:border-emerald-500 transition-all shadow-inner">
                    </div>
                    <button onclick="locateOSM()" class="w-12 h-12 bg-slate-100 border border-slate-200 rounded-md flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all text-slate-500 shadow-sm">
                        <i data-lucide="map-pin" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <!-- OSM Search Results Dropdown -->
                <div id="osmResults" class="absolute top-full left-0 right-0 z-[1100] bg-white border border-slate-200 rounded-md shadow-xl mt-1 hidden overflow-hidden">
                </div>
            </div>
            
            <div class="rounded-lg border border-slate-100 overflow-hidden aspect-video relative">
                <div id="osmTestMap" class="w-full h-full rounded-md z-0 bg-slate-50"></div>
                <div class="absolute bottom-4 left-4 right-4 bg-white/90 backdrop-blur px-4 py-2 rounded-lg border border-slate-200 shadow-lg pointer-events-none z-[1000]">
                    <p id="osmAddressLine" class="text-[0.7rem] font-bold text-emerald-700 truncate">Move pin or search to locate...</p>
                </div>
            </div>
            <p id="osmCoords" class="text-[0.6rem] font-bold text-slate-400 uppercase tracking-widest">Lat: --, Lng: --</p>
        </div>
    </div>
</div>

<script>
    let gMap, gMarker, osmMap, osmMarker;
    const defaultCoords = { lat: 25.1384, lng: 55.2285 };

    document.addEventListener('DOMContentLoaded', function() {
        // --- Init OSM Hub ---
        if (window.L) {
            osmMap = L.map('osmTestMap', { zoomControl: true, attributionControl: false }).setView([defaultCoords.lat, defaultCoords.lng], 12);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(osmMap);
            
            // Marker: Draggable
            osmMarker = L.marker([defaultCoords.lat, defaultCoords.lng], { draggable: true }).addTo(osmMap);
            
            // Map Click to reposition
            osmMap.on('click', (e) => {
                const { lat, lng } = e.latlng;
                osmMarker.setLatLng([lat, lng]);
                reverseGeocodeOSM(lat, lng);
            });

            // Update on Drag End
            osmMarker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                reverseGeocodeOSM(pos.lat, pos.lng);
            });

            // OSM Search logic
            const osmIn = document.getElementById('osmSearchInput');
            const osmResults = document.getElementById('osmResults');
            let osmTimeout;

            osmIn.addEventListener('input', (e) => {
                clearTimeout(osmTimeout);
                const query = e.target.value.trim();
                if (query.length < 3) {
                    osmResults.classList.add('hidden');
                    return;
                }
                osmTimeout = setTimeout(() => {
                    document.getElementById('osmAddressLine').innerText = '🛰️ Searching...';
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=5&q=${encodeURIComponent(query)}`, {
                        headers: {
                            'Accept': 'application/json',
                            'User-Agent': 'MotorBazar-App-Diagnostic'
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                renderOsmResults(data);
                                document.getElementById('osmAddressLine').innerText = 'Results Captured.';
                            } else {
                                document.getElementById('osmAddressLine').innerText = 'No Matches Found.';
                                osmResults.classList.add('hidden');
                            }
                        })
                        .catch(err => {
                            document.getElementById('osmAddressLine').innerText = 'Sync Error: ' + err.message;
                        });
                }, 800);
            });

            function renderOsmResults(data) {
                if (!data || data.length === 0) {
                    osmResults.classList.add('hidden');
                    return;
                }
                osmResults.innerHTML = '';
                data.forEach(item => {
                    const btn = document.createElement('button');
                    btn.className = 'w-full px-5 py-3 text-left hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0 flex items-center gap-3 group';
                    btn.innerHTML = `
                        <i data-lucide="map-pin" class="w-4 h-4 text-slate-300 group-hover:text-emerald-500"></i>
                        <span class="text-[0.75rem] font-medium text-slate-700 truncate">${item.display_name}</span>
                    `;
                    btn.onclick = () => {
                        const lat = parseFloat(item.lat);
                        const lon = parseFloat(item.lon);
                        osmMap.setView([lat, lon], 15);
                        osmMarker.setLatLng([lat, lon]);
                        updateOsmLabels(lat, lon, item.display_name);
                        osmResults.classList.add('hidden');
                        osmIn.value = item.display_name;
                    };
                    osmResults.appendChild(btn);
                });
                osmResults.classList.remove('hidden');
                lucide.createIcons();
            }

            document.addEventListener('click', (e) => {
                if (!osmResults.contains(e.target) && e.target !== osmIn) {
                    osmResults.classList.add('hidden');
                }
            });
        }

        // --- Init Google Hub ---
        if (window.google && window.google.maps) {
            const mapContainer = document.getElementById('googleMapTest');
            mapContainer.innerHTML = '';
            gMap = new google.maps.Map(mapContainer, {
                center: defaultCoords,
                zoom: 12,
                disableDefaultUI: true,
                zoomControl: true
            });
            gMarker = new google.maps.Marker({
                position: defaultCoords,
                map: gMap,
                draggable: true,
                animation: google.maps.Animation.DROP
            });

            gMarker.addListener('dragend', () => {
                const pos = gMarker.getPosition();
                updateGoogleLabels(pos.lat(), pos.lng());
            });

            const googleIn = document.getElementById('googleSearchInput');
            const autocomplete = new google.maps.places.Autocomplete(googleIn, {
                 componentRestrictions: { country: 'ae' }
            });
            autocomplete.bindTo('bounds', gMap);
            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;
                const loc = place.geometry.location;
                gMap.setCenter(loc);
                gMap.setZoom(16);
                gMarker.setPosition(loc);
                updateGoogleLabels(loc.lat(), loc.lng());
            });
        }
    });

    function reverseGeocodeOSM(lat, lng) {
        document.getElementById('osmAddressLine').innerText = '🛰️ Loading address...';
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`, {
            headers: {
                'Accept': 'application/json',
                'User-Agent': 'MotorBazar-App-Diagnostic'
            }
        })
            .then(r => r.json())
            .then(data => {
                updateOsmLabels(lat, lng, data.display_name || 'Unknown Location');
            })
            .catch(() => updateOsmLabels(lat, lng, 'Sync Error.'));
    }

    function updateOsmLabels(lat, lng, address) {
        document.getElementById('osmCoords').innerText = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
        document.getElementById('osmAddressLine').innerText = address;
    }

    function updateGoogleLabels(lat, lng) {
        document.getElementById('googleCoords').innerText = `Lat: ${lat.toFixed(6)}, Lng: ${lng.toFixed(6)}`;
    }

    window.locateOSM = function() {
        if (!navigator.geolocation) return alert('No GPS');
        navigator.geolocation.getCurrentPosition((pos) => {
            const { latitude, longitude } = pos.coords;
            if (osmMap) {
                osmMap.setView([latitude, longitude], 15);
                osmMarker.setLatLng([latitude, longitude]);
                reverseGeocodeOSM(latitude, longitude);
            }
        });
    }

    window.locateGoogle = function() {
        if (!navigator.geolocation) return alert('No GPS');
        navigator.geolocation.getCurrentPosition((pos) => {
            const { latitude, longitude } = pos.coords;
            const coords = { lat: latitude, lng: longitude };
            if (gMap) {
                gMap.setCenter(coords);
                gMap.setZoom(15);
                gMarker.setPosition(coords);
                updateGoogleLabels(latitude, longitude);
            }
        });
    }
</script>
@endsection

