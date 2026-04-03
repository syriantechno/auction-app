@extends('admin.layout')

@section('title', 'AI SEO Control Panel')
@section('page_title', 'AI SEO Control Panel')

@section('content')
<div class="px-1 space-y-5">
    <!-- Page Header -->
    <div class="px-1 mb-6">
        <div class="flex items-center gap-4">
            <i data-lucide="search" class="w-8 h-8 text-[#ff6900]"></i>
            <div>
                <h1 class="text-2xl font-black text-slate-900">AI SEO Control Panel</h1>
                <p class="text-slate-500 text-sm mt-1">Powered by AgentRouter DeepSeek AI</p>
            </div>
            <div class="flex gap-3 ml-auto">
                <a href="{{ route('admin.seo.settings') }}" class="px-4 py-2 bg-slate-600 text-white rounded-lg font-medium hover:bg-slate-700 transition-colors">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <button id="bulk-generate-btn" class="px-4 py-2 bg-[#ff6900] text-white rounded-lg font-medium hover:bg-orange-600 transition-colors">
                    <i class="fas fa-magic mr-2"></i> Bulk Generate
                </button>
                <button id="submit-all-btn" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i> Submit All
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Pages</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_pages'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Optimized</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['optimized_pages'] }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Indexed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['indexed_pages'] }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-search text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-orange-600">{{ $stats['pending_submissions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-orange-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Avg Score</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['average_score'], 1) }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-line text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Google Analytics & Rankings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Google Analytics -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Google Analytics</h3>
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fab fa-google text-blue-600 text-sm"></i>
                </div>
            </div>
            <div id="analytics-data">
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Active Users</span>
                        <span class="text-lg font-semibold" id="active-users">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Page Views</span>
                        <span class="text-lg font-semibold" id="page-views">-</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sessions</span>
                        <span class="text-lg font-semibold" id="sessions">-</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Keyword Rankings -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Keyword Rankings</h3>
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-search text-purple-600 text-sm"></i>
                </div>
            </div>
            <div id="ranking-data">
                <div class="space-y-3" id="ranking-list">
                    <!-- Rankings will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Tools Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Meta Tags Generator -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Meta Tags Generator</h3>
            <form id="meta-tags-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                    <textarea name="content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Enter your content here..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="page">Page</option>
                        <option value="auction">Auction</option>
                        <option value="blog">Blog</option>
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white rounded-lg py-2 font-medium hover:bg-blue-700 transition-colors">
                    Generate Meta Tags
                </button>
            </form>
            <div id="meta-tags-result" class="mt-4 hidden">
                <!-- Results will be shown here -->
            </div>
        </div>

        <!-- URL Analyzer -->
        <div class="bg-white rounded-md border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">URL Analyzer</h3>
            <form id="url-analyzer-form" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">URL</label>
                    <input type="url" name="url" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="https://example.com/page">
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white rounded-lg py-2 font-medium hover:bg-purple-700 transition-colors">
                    Analyze SEO
                </button>
            </form>
            <div id="url-analyzer-result" class="mt-4 hidden">
                <!-- Results will be shown here -->
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent SEO Reports</h3>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">URL</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Score</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Status</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Date</th>
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentReports as $report)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 px-4 text-sm text-gray-900">{{ $report['url'] }}</td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $report['score'] >= 80 ? 'bg-green-100 text-green-800' : ($report['score'] >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $report['score'] }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-sm text-gray-600">Indexed</td>
                        <td class="py-3 px-4 text-sm text-gray-600">{{ $report['date']->format('M d, Y') }}</td>
                        <td class="py-3 px-4">
                            <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">View Report</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

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
                const report = result.report;
                
                resultDiv.innerHTML = `
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-purple-900">SEO Analysis Report</h4>
                            <span class="text-2xl font-bold ${report.score >= 80 ? 'text-green-600' : (report.score >= 60 ? 'text-yellow-600' : 'text-red-600')}">${report.score}</span>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div>
                                <strong>Issues Found:</strong>
                                <ul class="mt-1 ml-4 list-disc">
                                    ${report.issues.map(issue => `<li>${issue}</li>`).join('')}
                                </ul>
                            </div>
                            <div>
                                <strong>Recommendations:</strong>
                                <ul class="mt-1 ml-4 list-disc">
                                    ${report.recommendations.map(rec => `<li>${rec}</li>`).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                `;
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
                const rankingList = document.getElementById('ranking-list');
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

    // Refresh data every 30 seconds
    setInterval(() => {
        loadAnalyticsData();
        loadRankingData();
    }, 30000);
});
</script>
@endsection

