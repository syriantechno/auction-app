@extends('admin.layout')

@section('title', 'SEO Settings & Integrations')

@section('content')
<x-admin-page-standard 
    icon="settings" 
    title="SEO" 
    highlight="Configuration" 
    subtitle="Configure your AgentRouter & Search Engine API integrations"
    dot="violet"
>
    <x-slot name="actions">
        <a href="{{ route('admin.seo.dashboard') }}" 
           class="h-[52px] px-8 bg-white border-2 border-slate-100 rounded-xl text-slate-400 hover:text-slate-900 transition-all flex items-center gap-3 text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Dashboard
        </a>
    </x-slot>


    <form method="POST" action="{{ route('admin.seo.settings.update') }}" class="space-y-8 pb-20">
        @csrf
        
        {{-- Display Errors --}}
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
            <div class="flex items-center gap-3 text-red-800 font-black uppercase text-[0.6rem] tracking-widest mb-3">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Critical Validation Errors
            </div>
            <ul class="list-disc list-inside text-[0.75rem] font-bold text-red-600 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        
        <!-- AgentRouter API -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="px-8 py-5 bg-[#1d293d] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i data-lucide="cpu" class="w-5 h-5 text-[#ff6900]"></i>
                    <div>
                        <div class="text-[0.65rem] font-black text-white uppercase tracking-widest">AgentRouter Protocol</div>
                        <div class="text-[0.52rem] text-white/40 font-bold uppercase tracking-widest">AI Copywriting Intelligence Engine</div>
                    </div>
                </div>
                <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center border border-white/20">
                    <i data-lucide="shield-check" class="w-5 h-5 text-white/40"></i>
                </div>
            </div>
            <div class="p-8 bg-[#f8fafc] space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">AI Protocol Provider</label>
                        <select name="agent_router_provider" id="provider-selector" class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-black text-slate-700 outline-none focus:border-[#ff6900] appearance-none transition-all">
                            @foreach(\App\Models\SEOSettings::getProviders() as $key => $info)
                                @php 
                                    $savedConfig = $settings->getProviderConfig($key);
                                @endphp
                                <option value="{{ $key }}" 
                                        data-url="{{ $info['base_url'] }}" 
                                        data-models="{{ json_encode($info['models']) }}"
                                        data-saved-key="{{ $savedConfig['api_key'] ?? '' }}"
                                        data-saved-url="{{ $savedConfig['base_url'] ?? '' }}"
                                        data-saved-model="{{ $savedConfig['model'] ?? '' }}"
                                        {{ ($settings->agent_router_provider ?? 'openrouter') === $key ? 'selected' : '' }}>
                                    {{ $info['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">Universal API Key</label>
                        <input type="password" name="agent_router_api_key" value="{{ $settings->agent_router_api_key }}" 
                               class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-mono text-slate-700 outline-none focus:border-[#ff6900] transition-all"
                               placeholder="sk-XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX">
                    </div>

                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">Execution Model</label>
                        <select name="agent_router_model" id="model-selector" class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-black text-slate-700 outline-none focus:border-[#ff6900] appearance-none transition-all">
                            @php 
                                $currentProvider = $settings->agent_router_provider ?? 'openrouter';
                                $providers = \App\Models\SEOSettings::getProviders();
                                $currentModels = $providers[$currentProvider]['models'] ?? [];
                            @endphp
                            @foreach($currentModels as $model)
                                <option value="{{ $model }}" {{ ($settings->agent_router_model ?? '') === $model ? 'selected' : '' }}>
                                    {{ $model }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">API Base Endpoint</label>
                    <input type="text" name="agent_router_base_url" id="base-url-input" value="{{ $settings->agent_router_base_url }}" 
                           class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-mono text-slate-700 outline-none focus:border-blue-500"
                           placeholder="https://api.provider.ai/v1">
                </div>

                {{-- Status & Diagnostic Actions --}}
                <div class="pt-6 border-t border-slate-200 flex items-center justify-between">
                    <div>
                        @if($settings->isAgentRouterConfigured())
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 rounded-lg border border-emerald-100">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-[0.55rem] font-black text-emerald-600 uppercase tracking-widest">Protocol Active</span>
                            </div>
                        @else
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 rounded-lg border border-amber-100">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                <span class="text-[0.55rem] font-black text-amber-600 uppercase tracking-widest">Awaiting Configuration</span>
                            </div>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="button" id="verify-agent-router-key" class="h-10 px-6 bg-slate-100 text-slate-600 rounded-xl text-[0.6rem] font-black uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Verify Architecture
                        </button>
                        <button type="button" id="test-agent-router" class="h-10 px-6 bg-purple-600 text-white rounded-xl text-[0.6rem] font-black uppercase tracking-widest hover:bg-purple-700 shadow-lg shadow-purple-900/10 transition-all flex items-center gap-2">
                            <i data-lucide="zap" class="w-3.5 h-3.5"></i> Live Load Test
                        </button>
                    </div>
                </div>
                <div id="api-key-verify-result" class="mt-4 hidden animate-in zoom-in-95 duration-300"></div>
                <div id="agent-router-test-result" class="mt-4 hidden animate-in zoom-in-95 duration-300"></div>
            </div>
        </div>

        {{-- Google & Bing Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Google Services -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <i data-lucide="globe" class="w-4 h-4 text-blue-500"></i>
                    <span class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Google Lighthouse & Analytics</span>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Measurement ID (G4)</label>
                        <input type="text" name="google_analytics_id" value="{{ $settings->google_analytics_id }}" 
                               class="w-full h-10 bg-slate-50 border border-slate-200 rounded-lg px-4 text-[0.75rem] font-bold text-slate-700 focus:bg-white focus:border-blue-500 transition-all"
                               placeholder="G-XXXXXXXXXX">
                    </div>
                    <div>
                        <label class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Search Console API</label>
                        <input type="password" name="google_search_console_api_key" value="{{ $settings->google_search_console_api_key }}" 
                               class="w-full h-10 bg-slate-50 border border-slate-200 rounded-lg px-4 text-[0.75rem] font-mono text-slate-700 focus:bg-white focus:border-blue-500 transition-all">
                    </div>
                    <label class="flex items-center p-3 bg-blue-50 border border-blue-100 rounded-xl cursor-pointer hover:bg-blue-100/50 transition-colors">
                        <input type="hidden" name="auto_submit_google" value="0">
                        <input type="checkbox" name="auto_submit_google" value="1" {{ $settings->auto_submit_google ? 'checked' : '' }} 
                               class="w-4 h-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                        <span class="ml-3 text-[0.62rem] font-black text-blue-700 uppercase tracking-widest">Enable Autopilot Submission</span>
                    </label>
                </div>
            </div>

            <!-- Bing Services -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <i data-lucide="monitor" class="w-4 h-4 text-emerald-500"></i>
                    <span class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">Microsoft Bing Toolkit</span>
                </div>
                <div class="p-6 space-y-6">
                    <div>
                        <label class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5">Bing Webmaster Key</label>
                        <input type="password" name="bing_ webmaster_api_key" value="{{ $settings->bing_webmaster_api_key }}" 
                               class="w-full h-10 bg-slate-50 border border-slate-200 rounded-lg px-4 text-[0.75rem] font-mono text-slate-700 focus:bg-white focus:border-emerald-500 transition-all">
                    </div>
                    <label class="flex items-center p-3 bg-emerald-50 border border-emerald-100 rounded-xl cursor-pointer hover:bg-emerald-100/50 transition-colors">
                        <input type="hidden" name="auto_submit_bing" value="0">
                        <input type="checkbox" name="auto_submit_bing" value="1" {{ $settings->auto_submit_bing ? 'checked' : '' }} 
                               class="w-4 h-4 rounded border-emerald-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-3 text-[0.62rem] font-black text-emerald-700 uppercase tracking-widest">Connect Bing Indexer API</span>
                    </label>
                </div>
            </div>
        </div>

        {{-- Search Engine Indexing (Sitemap & Pings) --}}
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="px-8 py-5 bg-slate-900 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i data-lucide="map" class="w-5 h-5 text-purple-400"></i>
                    <div>
                        <div class="text-[0.65rem] font-black text-white uppercase tracking-widest">Indexing Assets & Pings</div>
                        <div class="text-[0.52rem] text-white/40 font-bold uppercase tracking-widest">Automatic Sitemap & Search Engine Communication</div>
                    </div>
                </div>
                <div class="flex gap-2">
                    <a href="{{ url('/sitemap.xml') }}" target="_blank" class="h-10 px-6 bg-white/10 text-white rounded-xl text-[0.6rem] font-black uppercase tracking-widest hover:bg-white/20 border border-white/10 transition-all flex items-center gap-2">
                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Live Sitemap
                    </a>
                </div>
            </div>
            <div class="p-8 bg-[#f8fafc]">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                                <i data-lucide="activity" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h5 class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest">Sitemap Engine Status</h5>
                                <p class="text-[0.55rem] text-emerald-500 font-bold uppercase mt-0.5">Active & Injected</p>
                            </div>
                        </div>
                        <p class="text-[0.65rem] text-slate-500 leading-relaxed font-semibold italic">
                            يتم تحديث ملف الخارطة (Sitemap) بشكل لحظي عند كل تغيير في المزادات أو الصفحات.
                        </p>
                    </div>

                    <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                                <i data-lucide="zap" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <h5 class="text-[0.7rem] font-black text-slate-900 uppercase tracking-widest">Indexing API (Autosubmit)</h5>
                                <p class="text-[0.55rem] text-slate-400 font-bold uppercase mt-0.5">Enabled for new content</p>
                            </div>
                        </div>
                        <p class="text-[0.65rem] text-slate-500 leading-relaxed font-semibold italic">
                            بمجرد إضافة سيارة، يتم مخاطبة جوجل مباشرة عبر الـ Indexing API لطلب الأرشفة الفوريّة.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- WhatsApp Agent -->
        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
            <div class="px-8 py-5 bg-[#1d293d] flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i data-lucide="message-circle" class="w-5 h-5 text-emerald-400"></i>
                    <div>
                        <div class="text-[0.65rem] font-black text-white uppercase tracking-widest">WhatsApp Intelligent Agent</div>
                        <div class="text-[0.52rem] text-white/40 font-bold uppercase tracking-widest">Real-time Priority Search Notifications</div>
                    </div>
                </div>
                <button type="button" id="test-whatsapp" class="h-10 px-6 bg-emerald-600 text-white rounded-xl text-[0.6rem] font-black uppercase tracking-widest hover:bg-emerald-700 shadow-lg shadow-emerald-900/10 transition-all">
                    Test Payload
                </button>
            </div>
            <div class="p-8 bg-[#f8fafc] space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">WABA Protocol API Key</label>
                        <input type="password" name="whatsapp_agent_api_key" value="{{ $settings->whatsapp_agent_api_key }}" 
                               class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-mono text-slate-700 outline-none focus:border-emerald-500 transition-all"
                               placeholder="sk-XXXXXXXXXX">
                    </div>
                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-2 ml-1">Operational Phone Line</label>
                        <div class="relative">
                            <input type="text" name="whatsapp_agent_phone" value="{{ $settings->whatsapp_agent_phone }}" 
                                   class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 pl-12 text-[0.75rem] font-bold text-slate-700 outline-none focus:border-emerald-500"
                                   placeholder="+971 50 XXXXXXX">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"><i data-lucide="phone" class="w-4 h-4"></i></span>
                        </div>
                    </div>
                </div>

                <div class="p-6 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="whatsapp_notifications" value="0">
                            <input type="checkbox" name="whatsapp_notifications" value="1" {{ $settings->whatsapp_notifications ? 'checked' : '' }} 
                                   class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                            <span class="text-[0.58rem] font-black text-slate-500 uppercase tracking-widest ml-2">Channel Subscriptions</span>
                        </div>
                    </div>

                    @if($settings->whatsapp_notifications)
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($settings->getNotificationTypesList() as $key => $label)
                        <label class="flex items-center p-3 border border-slate-100 rounded-xl hover:bg-slate-50 cursor-pointer transition-colors">
                            <input type="checkbox" name="notification_types[]" value="{{ $key }}" 
                                   {{ in_array($key, $settings->notification_types ?? []) ? 'checked' : '' }}
                                   class="w-3.5 h-3.5 rounded border-slate-300 text-emerald-600">
                            <span class="ml-2.5 text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tracking & Thresholds Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Rank Tracking -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <i data-lucide="activity" class="w-4 h-4 text-purple-600"></i>
                        <span class="text-[0.6rem] font-black text-[#1d293d] uppercase tracking-widest">Authority Monitor</span>
                    </div>
                    <button type="button" id="add-keyword" class="text-[0.5rem] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center gap-1.5 line">
                        <i data-lucide="plus" class="w-3 h-3"></i> Add Protocol
                    </button>
                </div>
                <div class="p-6 space-y-3" id="keywords-container">
                    @foreach($settings->ranking_track_keywords ?? [] as $keyword)
                    <div class="flex items-center gap-2 group">
                        <input type="text" name="ranking_track_keywords[]" value="{{ $keyword }}" 
                               class="flex-1 h-10 bg-slate-50 border border-slate-200 rounded-lg px-4 text-[0.75rem] font-bold text-slate-600 focus:bg-white focus:border-purple-500 outline-none transition-all">
                        <button type="button" class="remove-keyword w-10 h-10 rounded-lg flex items-center justify-center text-slate-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100">
                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                        </button>
                    </div>
                    @endforeach
                    <div class="flex items-center gap-2">
                        <input type="text" name="ranking_track_keywords[]" placeholder="Enter new keyword..." 
                               class="flex-1 h-10 bg-slate-100 border-dashed border-2 border-slate-200 rounded-lg px-4 text-[0.75rem] font-bold text-slate-400 outline-none">
                    </div>
                </div>
            </div>

            <!-- General Control -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden shadow-sm flex flex-col justify-between">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex items-center gap-3">
                    <i data-lucide="sliders" class="w-4 h-4 text-slate-400"></i>
                    <span class="text-[0.6rem] font-black text-slate-500 uppercase tracking-widest">System Thresholds</span>
                </div>
                <div class="p-8 space-y-8">
                    <div>
                        <label class="text-[0.55rem] font-black text-slate-400 uppercase tracking-widest block mb-2">Alert Threshold Score</label>
                        <div class="flex items-center gap-4">
                            <input type="range" name="alert_threshold" value="{{ $settings->alert_threshold }}" min="0" max="100" 
                                   class="flex-1 h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-[#ff6900]">
                            <span class="w-12 text-center text-sm font-black text-[#1d293d] tabular-nums">{{ $settings->alert_threshold }}</span>
                        </div>
                    </div>
                    <label class="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100/50 transition-all border border-slate-200/50">
                        <div class="flex items-center gap-3">
                            <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-[0.62rem] font-black text-slate-700 uppercase tracking-widest">Sync Daily Reports</span>
                        </div>
                        <div class="relative inline-flex items-center">
                            <input type="hidden" name="daily_reports" value="0">
                            <input type="checkbox" name="daily_reports" value="1" {{ $settings->daily_reports ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-[#ff6900] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-5"></div>
                        </div>
                    </label>
                </div>
                <div class="p-4 bg-amber-50 border-t border-amber-100 text-[0.5rem] font-bold text-amber-700 uppercase tracking-widest flex items-center gap-2">
                    <i data-lucide="info" class="w-3.5 h-3.5"></i> Daily sync fires at midnight UTC
                </div>
            </div>
        </div>

        {{-- Final Global Update --}}
        <div class="flex items-center justify-between p-8 bg-white rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-100">
                    <i data-lucide="save" class="w-6 h-6 text-blue-500"></i>
                </div>
                <div>
                    <div class="text-[0.7rem] font-black text-slate-700 uppercase tracking-widest">Synchronize Meta Data</div>
                    <div class="text-[0.55rem] text-slate-400 font-bold uppercase tracking-widest italic">All API integrations will be updated globally</div>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.seo.dashboard') }}" class="text-[0.6rem] font-black text-slate-400 uppercase tracking-widest hover:text-slate-900 transition-colors mr-4">Discard Changes</a>
                <button type="submit" class="h-[52px] px-10 bg-[#1d293d] hover:bg-[#ff6900] text-white rounded-xl font-black text-[0.72rem] uppercase tracking-[0.2em] transition-all duration-500 shadow-xl shadow-slate-900/10">
                    Propagate All Settings
                </button>
            </div>
        </div>
    </form>
</x-admin-page-standard>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Provider auto-configuration logic
    const providerSelector = document.getElementById('provider-selector');
    const baseUrlInput = document.getElementById('base-url-input');
    const modelSelector = document.getElementById('model-selector');

    providerSelector?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        
        // Grab defaults from data attributes
        const defaultUrl = selected.getAttribute('data-url');
        const models = JSON.parse(selected.getAttribute('data-models') || '[]');
        
        // Grab saved data if exists in our new JSON vault
        const savedKey = selected.getAttribute('data-saved-key');
        const savedUrl = selected.getAttribute('data-saved-url');
        const savedModel = selected.getAttribute('data-saved-model');

        // Handle URL
        const activeUrl = savedUrl || defaultUrl;
        if (activeUrl) {
            baseUrlInput.value = activeUrl;
            baseUrlInput.classList.add('ring-2', 'ring-blue-500');
            setTimeout(() => baseUrlInput.classList.remove('ring-2', 'ring-blue-500'), 1000);
        }

        // Handle API Key
        const apiKeyInput = document.querySelector('input[name="agent_router_api_key"]');
        if (apiKeyInput) {
            apiKeyInput.value = savedKey || '';
            if (savedKey) {
                apiKeyInput.classList.add('ring-2', 'ring-green-500');
                setTimeout(() => apiKeyInput.classList.remove('ring-2', 'ring-green-500'), 1000);
            }
        }

        // Handle Models
        if (models.length > 0) {
            modelSelector.innerHTML = '';
            models.forEach(model => {
                const opt = document.createElement('option');
                opt.value = model;
                opt.textContent = model;
                if (model === savedModel) opt.selected = true;
                modelSelector.appendChild(opt);
            });
            modelSelector.classList.add('ring-2', 'ring-[#ff6900]');
            setTimeout(() => modelSelector.classList.remove('ring-2', 'ring-[#ff6900]'), 1000);
        }
    });

    // Verify saved AgentRouter API key
    document.getElementById('verify-agent-router-key')?.addEventListener('click', async function() {
        const btn = this;
        const resultBox = document.getElementById('api-key-verify-result');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Verifying...';

        try {
            const response = await fetch('/admin/seo/verify-agent-router-key', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                resultBox.className = 'mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800';
                resultBox.innerHTML = `
                    <div class="font-semibold mb-1">API key saved successfully.</div>
                    <div>Source: <span class="font-mono">${result.source}</span></div>
                    <div>Database length: <span class="font-mono">${result.database_length}</span></div>
                    <div>Effective length: <span class="font-mono">${result.effective_length}</span></div>
                    <div>Masked preview: <span class="font-mono">${result.masked_preview}</span></div>
                `;
            } else {
                resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
                resultBox.innerHTML = `<div class="font-semibold">${result.message}</div>`;
            }

            resultBox.classList.remove('hidden');
        } catch (error) {
            resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
            resultBox.textContent = 'Verification failed: ' + error.message;
            resultBox.classList.remove('hidden');
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-fingerprint mr-2"></i> Verify Saved Key';
        }
    });

    // Test AgentRouter connection
    document.getElementById('test-agent-router')?.addEventListener('click', async function() {
        const btn = this;
        const resultBox = document.getElementById('agent-router-test-result');
        
        // Collect live values from the form
        const provider = document.getElementById('provider-selector').value;
        const apiKey = document.querySelector('input[name="agent_router_api_key"]').value;
        const baseUrl = document.getElementById('base-url-input').value;
        const model = document.getElementById('model-selector').value;

        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Testing...';

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const response = await fetch('/admin/seo/test-agent-router', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || ''
                },
                body: JSON.stringify({
                    provider: provider,
                    api_key: apiKey,
                    base_url: baseUrl,
                    model: model
                })
            });
            
            console.log('Response status:', response.status);

            const result = await response.json();
            console.log('Result:', result);

            if (result.success) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                btn.classList.add('bg-green-500');

                resultBox.className = 'mt-4 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800';
                resultBox.innerHTML = `
                    <div class="font-semibold mb-1">Connection successful.</div>
                    <div>Model: <span class="font-mono">${result.details?.model ?? 'N/A'}</span></div>
                    <div>Base URL: <span class="font-mono">${result.details?.base_url ?? 'N/A'}</span></div>
                    <div>Generated title: <span class="font-mono">${result.details?.generated_title ?? 'N/A'}</span></div>
                `;
                resultBox.classList.remove('hidden');
            } else {
                btn.innerHTML = '<i class="fas fa-times mr-2"></i> Failed';
                btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                btn.classList.add('bg-red-500');

                const serviceError = result.details?.service_error || {};
                const errorLines = [
                    `<div class="font-semibold mb-1">${result.message ?? 'Connection failed.'}</div>`,
                    serviceError.provider ? `<div>Provider: <span class="font-mono">${serviceError.provider}</span></div>` : '',
                    serviceError.status ? `<div>Status: <span class="font-mono">${serviceError.status}</span></div>` : '',
                    serviceError.error ? `<div>Error: <span class="font-mono">${serviceError.error}</span></div>` : '',
                    serviceError.body_preview ? `<div class="mt-2 break-words"><span class="font-semibold">Body:</span> <span class="font-mono text-xs">${serviceError.body_preview}</span></div>` : '',
                ].filter(Boolean).join('');

                resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
                resultBox.innerHTML = errorLines || `<div class="font-semibold">${result.message ?? 'Connection failed.'}</div>`;
                resultBox.classList.remove('hidden');
                console.error('Test Connection Error:', result);
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plug mr-2"></i> Test Connection';
                btn.classList.remove('bg-green-500', 'bg-red-500');
                btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 3000);

        } catch (error) {
            console.error('Catch Error:', error);
            console.error('Error details:', error.message);
            
            btn.innerHTML = '<i class="fas fa-times mr-2"></i> Error';
            btn.classList.remove('bg-purple-600', 'hover:bg-purple-700');
            btn.classList.add('bg-red-500');

            resultBox.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800';
            resultBox.innerHTML = `<div class="font-semibold">JavaScript Error</div><div class="font-mono text-xs break-words mt-1">${error.message}</div>`;
            resultBox.classList.remove('hidden');

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-plug mr-2"></i> Test Connection';
                btn.classList.remove('bg-red-500');
                btn.classList.add('bg-purple-600', 'hover:bg-purple-700');
            }, 3000);
        }
    });

    // Test WhatsApp connection
    document.getElementById('test-whatsapp')?.addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Testing...';

        try {
            const response = await fetch('/admin/seo/test-whatsapp', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.success) {
                btn.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-green-500');
            } else {
                btn.innerHTML = '<i class="fas fa-times mr-2"></i> Failed';
                btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                btn.classList.add('bg-red-500');
            }

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Test Connection';
                btn.classList.remove('bg-green-500', 'bg-red-500');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 3000);

        } catch (error) {
            btn.innerHTML = '<i class="fas fa-times mr-2"></i> Error';
            btn.classList.remove('bg-green-600', 'hover:bg-green-700');
            btn.classList.add('bg-red-500');

            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i> Test Connection';
                btn.classList.remove('bg-red-500');
                btn.classList.add('bg-green-600', 'hover:bg-green-700');
            }, 3000);
        }
    });

    // Add keyword functionality
    document.getElementById('add-keyword')?.addEventListener('click', function() {
        const container = this.closest('.space-y-2');
        const newKeywordDiv = document.createElement('div');
        newKeywordDiv.className = 'flex items-center space-x-2';
        newKeywordDiv.innerHTML = `
            <input type="text" name="ranking_track_keywords[]" placeholder="Enter keyword..." 
                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2">
            <button type="button" class="remove-keyword text-red-600 hover:text-red-800">
                <i class="fas fa-trash"></i>
            </button>
        `;
        container.insertBefore(newKeywordDiv, this.parentElement);
    });

    // Remove keyword functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-keyword')) {
            e.target.closest('.flex').remove();
        }
    });
});
</script>
@endsection

