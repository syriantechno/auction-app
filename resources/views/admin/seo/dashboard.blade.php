@extends('admin.layout')

@section('title', 'AI SEO Control Panel')
@section('page_title', 'AI SEO Control Panel')

@section('content')
<x-admin-page-standard 
    icon="search" 
    title="AI SEO" 
    highlight="Explorer" 
    subtitle="Powered by AgentRouter DeepSeek Intelligence"
    dot="orange"
>
    <x-slot name="actions">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.seo.settings') }}" 
               class="h-[52px] px-6 bg-white border-2 border-slate-100 rounded-xl text-slate-400 hover:text-slate-900 transition-all flex items-center gap-3 text-[0.7rem] font-black uppercase tracking-[0.2em] shadow-sm">
                <i data-lucide="settings" class="w-4 h-4"></i>
                Settings
            </a>
            <button id="bulk-generate-btn" 
                    class="h-[52px] px-6 bg-[#1d293d] hover:bg-[#ff6900] text-white rounded-xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-3 shadow-xl shadow-slate-900/10 group">
                <i data-lucide="sparkles" class="w-4 h-4 transition-transform group-hover:rotate-12"></i>
                Bulk Generate
            </button>
            <button id="submit-all-btn" 
                    class="h-[52px] px-6 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-black text-[0.7rem] uppercase tracking-[0.2em] transition-all flex items-center gap-3 shadow-xl shadow-blue-900/10">
                <i data-lucide="send" class="w-4 h-4"></i>
                Submit All
            </button>
        </div>
    </x-slot>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 mb-10">
        <x-admin-stat-card 
            label="Total Pages" 
            :value="$stats['total_pages']" 
            icon="files" 
            color="slate" 
        />

        <x-admin-stat-card 
            label="Optimized Pages" 
            :value="$stats['optimized_pages']" 
            icon="check-circle" 
            color="emerald" 
        />

        <x-admin-stat-card 
            label="Search Reach" 
            :value="$stats['indexed_pages']" 
            icon="globe" 
            color="orange" 
        />

        <x-admin-stat-card 
            label="Pending Audits" 
            :value="$stats['pending_submissions']" 
            icon="clock" 
            color="amber" 
        />

        <x-admin-stat-card 
            label="Avg SEO Score" 
            :value="number_format($stats['average_score'], 1)" 
            icon="trending-up" 
            color="rose" 
        />
    </div>


    <!-- Google Analytics & Rankings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-stretch mb-10">
        <!-- Google Analytics -->
        <x-admin-card title="Real-time Analytics" icon="bar-chart-3">
            <div class="px-6 py-4 -mt-8 -mx-8 mb-8 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <span class="flex items-center gap-1.5 px-2.5 py-1 bg-blue-50 text-blue-600 rounded-md text-[0.5rem] font-bold uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Google API Live
                </span>
            </div>
            <div class="space-y-6">
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="text-2xl font-black text-[#1d293d] tabular-nums" id="active-users">0</div>
                        <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest mt-1">Active Users</div>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="text-2xl font-black text-[#1d293d] tabular-nums" id="page-views">0</div>
                        <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest mt-1">Page Views</div>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-xl border border-slate-100">
                        <div class="text-2xl font-black text-[#1d293d] tabular-nums" id="sessions">0</div>
                        <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest mt-1">Sessions</div>
                    </div>
                </div>
            </div>
        </x-admin-card>

        <!-- Keyword Rankings -->
        <x-admin-card title="Growth Tracking" icon="target">
            <div class="px-6 py-4 -mt-8 -mx-8 mb-8 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                <a href="#" class="text-[0.5rem] font-black text-purple-600 uppercase tracking-widest hover:underline">View History</a>
            </div>
            <div id="ranking-list">
                <div class="space-y-4" id="ranking-list-inner">
                    <!-- Rankings will be loaded here -->
                    <div class="flex items-center justify-center py-10 opacity-20">
                        <i data-lucide="loader-2" class="w-8 h-8 animate-spin"></i>
                    </div>
                </div>
            </div>
        </x-admin-card>
    </div>

    <!-- Autonomous Execution Center -->
    <div class="bg-[#1d293d] rounded-[2rem] p-10 border border-white/5 relative overflow-hidden group shadow-2xl shadow-slate-900/40 mt-10 mb-10">
        <div class="absolute top-0 right-0 w-1/3 h-full bg-gradient-to-l from-[#ff6900]/10 to-transparent pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-500/10 rounded-full blur-[100px]"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="flex items-start gap-6 max-w-2xl">
                <div class="w-20 h-20 rounded-[2rem] bg-white/5 border border-white/10 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-700">
                    <i data-lucide="zap" class="w-10 h-10 text-[#ff6900] animate-pulse"></i>
                </div>
                <div>
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-[#ff6900] text-white text-[0.55rem] font-black uppercase tracking-[0.2em] rounded-md shadow-lg shadow-orange-500/20">Active Intelligence v2.0</span>
                        <h2 class="text-3xl font-black text-white tracking-tighter">Autonomous SEO Engine</h2>
                    </div>
                    <p class="text-slate-400 text-sm font-bold leading-relaxed">
                        Trigger a deep site crawl and Search Console propagation. This protocol uses AI to analyze every UI element, car asset, and CMS block to maximize indexed authority across top-tier search engines.
                    </p>
                </div>
            </div>
            
            <div class="shrink-0">
                <button id="activate-autonomous-btn" 
                        class="h-[72px] px-12 bg-[#ff6900] hover:bg-white hover:text-[#1d293d] text-white rounded-[1.5rem] font-black text-[0.85rem] uppercase tracking-[0.2em] transition-all duration-500 flex items-center gap-4 shadow-2xl shadow-orange-900/30 active:scale-95 group/btn">
                    <span class="group-hover/btn:scale-110 transition-transform">Execute Protocol</span>
                    <i data-lucide="shield-check" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        
        <div id="autonomous-status" class="mt-8 pt-8 border-t border-white/5 hidden animate-in fade-in duration-700">
            <div class="flex items-center gap-4">
                <div class="flex-1 h-2 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full bg-gradient-to-r from-blue-500 to-[#ff6900] w-full animate-progress-fast"></div>
                </div>
                <span class="text-[0.6rem] font-black text-[#ff6900] uppercase tracking-widest animate-pulse">System Crawling & Propagating...</span>
            </div>
        </div>
    </div>

    <!-- AI SEO Tools Area -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Meta Generator --}}
        <x-admin-card title="AI Copywriting Engine" icon="cpu">
            <div class="p-8 -m-8 bg-[#f8fafc] rounded-b-2xl">
                <form id="meta-tags-form" class="space-y-5">
                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5 ml-1">Contextual Content</label>
                        <textarea name="content" rows="4" class="w-full bg-white border border-slate-200 rounded-xl px-4 py-3 text-[0.85rem] font-bold text-slate-700 outline-none focus:border-[#ff6900] transition-all" placeholder="Enter keywords or long-form content to analyze..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5 ml-1">Asset Entity Type</label>
                            <select name="type" class="w-full h-11 bg-white border border-slate-200 rounded-xl px-4 text-[0.75rem] font-black text-slate-700 appearance-none outline-none focus:border-[#ff6900]">
                                <option value="page">Standard Page</option>
                                <option value="auction">Live Auction</option>
                                <option value="blog">Editorial Post</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full h-11 bg-[#ff6900] text-white rounded-xl text-[0.62rem] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#e55e00] transition-all shadow-lg shadow-orange-900/10 active:scale-95">
                                <i data-lucide="zap" class="w-3.5 h-3.5"></i> Generate Tags
                            </button>
                        </div>
                    </div>
                </form>
                <div id="meta-tags-result" class="mt-6 hidden animate-in slide-in-from-top-4 duration-500"></div>
            </div>
        </x-admin-card>

        {{-- URL Analyzer --}}
        <x-admin-card title="Protocol Audit Toolkit" icon="binary">
            <div class="p-8 -m-8 bg-[#f8fafc] rounded-b-2xl">
                <form id="url-analyzer-form" class="space-y-5">
                    <div>
                        <label class="text-[0.58rem] font-black text-slate-400 uppercase tracking-widest block mb-1.5 ml-1">Target Endpoint URL</label>
                        <div class="relative">
                            <input type="url" name="url" required class="w-full h-12 bg-white border border-slate-200 rounded-xl pl-4 pr-12 text-[0.85rem] font-bold text-slate-700 outline-none focus:border-blue-500 transition-all" placeholder="https://auction.system/car-listing-...">
                            <i data-lucide="link" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-300"></i>
                        </div>
                    </div>
                    <button type="submit" class="w-full h-11 bg-blue-600 text-white rounded-xl text-[0.62rem] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-blue-700 transition-all shadow-lg shadow-blue-900/10 active:scale-95">
                        <i data-lucide="activity" class="w-3.5 h-3.5"></i> Run Real-time Diagnostic
                    </button>
                </form>
                <div id="url-analyzer-result" class="mt-6 hidden animate-in slide-in-from-top-4 duration-500"></div>
            </div>
        </x-admin-card>
    </div>

    <!-- Recent SEO Reports Table -->
    <x-admin-card title="Audit History & Authority Reports" icon="history" class="mt-10">
        <div class="overflow-x-auto -mx-8 -my-8">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="text-left py-4 px-6 text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Indexed URL</th>
                        <th class="text-left py-4 px-6 text-[0.55rem] font-black text-slate-400 uppercase tracking-widest text-center">Score</th>
                        <th class="text-left py-4 px-6 text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th class="text-left py-4 px-6 text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Audit Date</th>
                        <th class="text-right py-4 px-6 text-[0.55rem] font-black text-slate-400 uppercase tracking-widest">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentReports as $report)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="py-4 px-6">
                            <div class="text-[0.78rem] font-bold text-slate-700 truncate max-w-md group-hover:text-[#ff6900] transition-colors">{{ $report['url'] }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex justify-center">
                                @php 
                                    $scoreColor = $report['score'] >= 80 ? 'text-emerald-500 bg-emerald-50' : ($report['score'] >= 60 ? 'text-amber-500 bg-amber-50' : 'text-red-500 bg-red-50');
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[0.7rem] font-black tabular-nums {{ $scoreColor }}">
                                    {{ $report['score'] }}
                                </span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span class="text-[0.55rem] font-black text-slate-500 uppercase tracking-widest">Indexed</span>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="text-[0.62rem] font-bold text-slate-400 uppercase italic">{{ $report['date']->format('d M, Y') }}</div>
                        </td>
                        <td class="py-4 px-6 text-right">
                            <button class="px-3 py-1.5 bg-slate-100 text-slate-500 rounded-lg text-[0.5rem] font-black uppercase tracking-widest hover:bg-[#1d293d] hover:text-white transition-all">Intelligence Report</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-20 text-center">
                            <p class="text-[0.65rem] font-black text-slate-300 uppercase tracking-widest">No audit reports documented yet</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-admin-card>
</x-admin-page-standard>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load analytics data
    loadAnalyticsData();
    
    // Load ranking data
    loadRankingData();

    // Meta Tags Generator
    document.getElementById('meta-tags-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('/admin/seo/generate-meta-tags', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    content: formData.get('content'),
                    type: formData.get('type')
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const resultDiv = document.getElementById('meta-tags-result');
                resultDiv.innerHTML = `
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h4 class="font-medium text-green-900 mb-2">Generated Meta Tags:</h4>
                        <div class="space-y-2 text-sm">
                            <div><strong>Title:</strong> ${result.meta_tags.title}</div>
                            <div><strong>Description:</strong> ${result.meta_tags.description}</div>
                            <div><strong>Keywords:</strong> ${result.meta_tags.keywords.join(', ')}</div>
                        </div>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // URL Analyzer
    document.getElementById('url-analyzer-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        try {
            const response = await fetch('/admin/seo/analyze', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    url: formData.get('url')
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                const resultDiv = document.getElementById('url-analyzer-result');
                const r = result.report;
                
                resultDiv.innerHTML = `
                    <div class="bg-white border-2 border-slate-100 rounded-[2rem] p-8 shadow-xl">
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-slate-50">
                             <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center font-black">${r.status}</div>
                                <div>
                                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Audit URL Status</h4>
                                    <div class="text-[0.7rem] font-bold text-slate-600 truncate max-w-sm">${r.url}</div>
                                </div>
                             </div>
                             <div class="text-right">
                                <div class="text-2xl font-black text-[#1d293d]">${r.score}%</div>
                                <div class="text-[0.5rem] font-black text-slate-400 uppercase tracking-widest">Global SEO Score</div>
                             </div>
                        </div>

                        <div class="space-y-8">
                            {{-- Title --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em]">Meta Title</label>
                                    <span class="text-[0.6rem] font-bold ${r.title.length < 60 ? 'text-emerald-500' : 'text-amber-500'}">${r.title.length} chars</span>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl text-sm font-bold text-slate-700 leading-relaxed border border-slate-100">
                                    ${r.title.content}
                                </div>
                            </div>

                            {{-- Description --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em]">Meta Description</label>
                                    <span class="text-[0.6rem] font-bold ${r.description.length < 160 ? 'text-emerald-500' : 'text-amber-500'}">${r.description.length} chars</span>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-xl text-sm font-bold text-slate-700 leading-relaxed border border-slate-100">
                                    ${r.description.content}
                                </div>
                            </div>

                            {{-- H1 --}}
                            <div>
                                <div class="flex justify-between items-center mb-2">
                                    <label class="text-[0.6rem] font-black text-slate-400 uppercase tracking-[0.2em]">H1 Headline</label>
                                    <span class="text-[0.6rem] font-bold text-emerald-500">${r.headings.h1} Found</span>
                                </div>
                                <div class="p-4 bg-blue-50/30 rounded-xl text-sm font-black text-blue-900 leading-relaxed border border-blue-100">
                                    ${r.h1}
                                </div>
                            </div>

                            {{-- Headings Grid --}}
                            <div class="grid grid-cols-6 gap-2">
                                ${[1,2,3,4,5,6].map(i => `
                                    <div class="text-center p-3 bg-slate-50 rounded-lg border border-slate-100">
                                        <div class="text-[0.55rem] font-black text-slate-400 uppercase mb-1">H${i}</div>
                                        <div class="text-sm font-black text-slate-800">${r.headings['h'+i]}</div>
                                    </div>
                                `).join('')}
                            </div>

                            {{-- Indexability Checklist --}}
                            <div class="pt-6 border-t border-slate-50">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="${r.indexability.is_indexable ? 'check-circle' : 'x-circle'}" class="w-5 h-5 ${r.indexability.is_indexable ? 'text-emerald-500' : 'text-red-500'}"></i>
                                        <span class="text-[0.65rem] font-black text-slate-600 uppercase tracking-widest">Indexability</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i data-lucide="shield-check" class="w-5 h-5 text-blue-500"></i>
                                        <span class="text-[0.65rem] font-black text-slate-600 uppercase tracking-widest">Robots: ${r.indexability.robots}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                lucide.createIcons();
                resultDiv.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    });

    // Load Analytics Data
    async function loadAnalyticsData() {
        try {
            const response = await fetch('/admin/seo/analytics');
            const data = await response.json();
            
            if (data.realtime) {
                document.getElementById('active-users').textContent = data.realtime.active_users || '-';
                document.getElementById('page-views').textContent = data.realtime.page_views || '-';
                document.getElementById('sessions').textContent = data.realtime.sessions || '-';
            }
        } catch (error) {
            console.error('Error loading analytics:', error);
        }
    }

    // Load Ranking Data
    async function loadRankingData() {
        try {
            const response = await fetch('/admin/seo/rankings');
            const data = await response.json();
            
            if (data.trends) {
                const rankingList = document.getElementById('ranking-list-inner');
                rankingList.innerHTML = '';
                
                Object.entries(data.trends).forEach(([keyword, trend]) => {
                    const trendIcon = trend.trend === 'up' ? '📈' : (trend.trend === 'down' ? '📉' : '➡️');
                    const position = trend.current?.google?.position || '-';
                    
                    rankingList.innerHTML += `
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-700">${keyword}</span>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-medium">#${position}</span>
                                <span class="text-xs">${trendIcon}</span>
                            </div>
                        </div>
                    `;
                });
            }
        } catch (error) {
            console.error('Error loading rankings:', error);
        }
    }

    // Autonomous SEO Protocol Execution
    document.getElementById('activate-autonomous-btn')?.addEventListener('click', async function() {
        const btn = this;
        const status = document.getElementById('autonomous-status');
        const originalContent = btn.innerHTML;
        
        Swal.fire({
            title: 'Autonomous Protocol',
            text: "WARNING: This will trigger a full-site crawl and AI optimization. Proceed?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff6900',
            cancelButtonColor: '#1d293d',
            confirmButtonText: 'Yes, Execute Protocol!',
            background: '#ffffff',
            borderRadius: '2rem',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-black uppercase tracking-widest px-8 py-4',
                cancelButton: 'rounded-xl font-black uppercase tracking-widest px-8 py-4'
            }
        }).then(async (result) => {
            if (result.isConfirmed) {
                btn.disabled = true;
                btn.innerHTML = `<i data-lucide="loader-2" class="w-5 h-5 animate-spin"></i> Running...`;
                lucide.createIcons();
                status.classList.remove('hidden');
                
                try {
                    const response = await fetch('{{ route('admin.seo.autonomous') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    const result = await response.json();
                    
                    if (result.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: result.message,
                            icon: 'success',
                            confirmButtonColor: '#ff6900'
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Protocol Failed',
                            text: result.message || 'Unknown error',
                            icon: 'error',
                            confirmButtonColor: '#ff6900'
                        });
                    }
                } catch (error) {
                    console.error('Autonomous execution error:', error);
                    Swal.fire({
                        title: 'Communication Error',
                        text: 'A critical communication error occurred during protocol execution.',
                        icon: 'error',
                        confirmButtonColor: '#dc2626'
                    });
                } finally {
                    btn.disabled = false;
                    btn.innerHTML = originalContent;
                    status.classList.add('hidden');
                    lucide.createIcons();
                }
            }
        });
    });

    // Refresh data every 30 seconds
    setInterval(() => {
        loadAnalyticsData();
        loadRankingData();
    }, 30000);
});
</script>
@endsection

