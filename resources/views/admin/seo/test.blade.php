@extends('admin.layout')

@section('title', 'SEO Test Page - محرك SEO التجريبي')

@section('content')
<div class="max-w-6xl mx-auto">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-black text-gray-900">🧪 صفحة تجريب SEO</h1>
                <p class="text-gray-600 mt-2">اختبار محرك SEO بالذكاء الاصطناعي - صفحة وهمية للتجربة</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.seo.dashboard') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg">
                    <i class="fas fa-arrow-left mr-2"></i> رجوع للـ Dashboard
                </a>
                <a href="{{ route('admin.seo.settings') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                    <i class="fas fa-cog mr-2"></i> الإعدادات
                </a>
            </div>
        </div>
    </header>

    {{-- API Connection Status --}}
    <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">🔌 حالة الاتصال بالـ API</h3>
                <p class="text-sm text-gray-600 mt-1">اختبار الاتصال بـ AgentRouter</p>
            </div>
            <button id="test-api-btn" class="px-4 py-2 bg-purple-600 text-white rounded-lg">
                <i class="fas fa-plug mr-2"></i> اختبر الاتصال
            </button>
        </div>
        <div id="api-status" class="mt-4 hidden">
            <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm"></div>
        </div>
    </div>

    {{-- Meta Tags Generator Test --}}
    <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">🏷️ اختبار توليد Meta Tags</h3>
                <p class="text-sm text-gray-600 mt-1">اختبار توليد العناوين والوصف تلقائيًا</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المحتوى</label>
                <textarea id="meta-content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="اكتب وصف السيارة هنا...">2023 BMW X5 M Sport Package, low mileage, excellent condition, premium interior</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">النوع</label>
                <select id="meta-type" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="auction">Auction - مزاد</option>
                    <option value="page">Page - صفحة</option>
                    <option value="blog">Blog - مقال</option>
                </select>
                <button id="test-meta-btn" class="w-full mt-4 px-4 py-2 bg-green-600 text-white rounded-lg">
                    <i class="fas fa-magic mr-2"></i>ولد Meta Tags
                </button>
            </div>
        </div>
        
        <div id="meta-result" class="mt-6 hidden">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-medium text-green-900 mb-3">✅ النتيجة:</h4>
                <div class="space-y-2 font-mono text-sm">
                    <div><strong>Title:</strong> <span id="meta-title"></span></div>
                    <div><strong>Description:</strong> <span id="meta-desc"></span></div>
                    <div><strong>Keywords:</strong> <span id="meta-keywords"></span></div>
                    <div id="demo-badge" class="hidden"><span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Demo Mode</span></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Keywords Analysis Test --}}
    <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">🔍 اختبار تحليل الكلمات المفتاحية</h3>
                <p class="text-sm text-gray-600 mt-1">استخراج الكلمات المفتاحية تلقائيًا</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">المحتوى للتحليل</label>
                <textarea id="keywords-content" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2">2023 Mercedes-Benz S-Class luxury sedan with AMG package and premium sound system</textarea>
            </div>
            <div>
                <button id="test-keywords-btn" class="w-full h-full px-4 py-2 bg-blue-600 text-white rounded-lg">
                    <i class="fas fa-search mr-2"></i> حلل الكلمات المفتاحية
                </button>
            </div>
        </div>
        
        <div id="keywords-result" class="mt-6 hidden">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-medium text-blue-900 mb-3">✅ الكلمات المفتاحية:</h4>
                <div id="keywords-list" class="flex flex-wrap gap-2"></div>
            </div>
        </div>
    </div>

    {{-- Structured Data Test --}}
    <div class="bg-white rounded-md border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">📋 اختبار توليد Structured Data</h3>
                <p class="text-sm text-gray-600 mt-1">توليد JSON-LD للـ Google</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العنوان</label>
                <input type="text" id="sd-title" value="2023 BMW X5" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">السعر</label>
                <input type="number" id="sd-price" value="75000" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">العلامة</label>
                <input type="text" id="sd-brand" value="BMW" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الموديل</label>
                <input type="text" id="sd-model" value="X5" class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
        </div>
        
        <button id="test-sd-btn" class="w-full px-4 py-2 bg-orange-600 text-white rounded-lg">
            <i class="fas fa-code mr-2"></i>ولد Structured Data
        </button>
        
        <div id="sd-result" class="mt-6 hidden">
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                <h4 class="font-medium text-orange-900 mb-3">✅ JSON-LD:</h4>
                <pre id="sd-json" class="font-mono text-xs overflow-x-auto bg-gray-800 text-green-400 p-4 rounded-lg"></pre>
            </div>
        </div>
    </div>

    {{-- Console Output --}}
    <div class="bg-black rounded-md border border-gray-700 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-green-400 font-mono">💻 Console Output</h3>
            <button id="clear-console" class="px-3 py-1 bg-gray-700 text-white rounded text-sm">مسح</button>
        </div>
        <div id="console-output" class="font-mono text-xs text-green-400 h-48 overflow-y-auto bg-gray-900 p-4 rounded-lg">
            <div class="text-gray-500">// اضغط أي زر اختبار لعرض النتائج هنا...</div>
        </div>
    </div>

    {{-- Delete Page Button --}}
    <div class="mt-8 p-6 bg-red-50 border border-red-200 rounded-md">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-red-900">⚠️ حذف الصفحة</h3>
                <p class="text-sm text-red-600 mt-1">احذف هذة الصفحة بعد الانتهاء من الاختبار</p>
            </div>
            <form action="{{ route('admin.seo.test.delete') }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف صفحة التجريب؟');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                    <i class="fas fa-trash mr-2"></i> احذف الصفحة
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function log(message, type = 'info') {
    const console = document.getElementById('console-output');
    const time = new Date().toLocaleTimeString();
    const color = type === 'error' ? 'text-red-400' : type === 'success' ? 'text-green-400' : 'text-blue-400';
    console.innerHTML += `<div class="${color}">[${time}] ${message}</div>`;
    console.scrollTop = console.scrollHeight;
}

// Test API Connection
document.getElementById('test-api-btn')?.addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> جاري الاختبار...';
    
    log('Testing API connection...');
    
    try {
        const response = await fetch('/admin/seo/test/api-connection', {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
        
        const result = await response.json();
        log('API Response: ' + JSON.stringify(result, null, 2), result.success ? 'success' : 'error');
        
        const statusDiv = document.getElementById('api-status');
        statusDiv.classList.remove('hidden');
        statusDiv.querySelector('div').textContent = JSON.stringify(result, null, 2);
        
    } catch (error) {
        log('Error: ' + error.message, 'error');
    }
    
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-plug mr-2"></i> اختبر الاتصال';
});

// Test Meta Tags
document.getElementById('test-meta-btn')?.addEventListener('click', async function() {
    const content = document.getElementById('meta-content').value;
    const type = document.getElementById('meta-type').value;
    
    log(`Testing Meta Tags generation for: ${type}`);
    log(`Content: ${content.substring(0, 50)}...`);
    
    try {
        const response = await fetch('/admin/seo/test/meta-tags', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content, type })
        });
        
        const result = await response.json();
        log('Meta Tags Result: ' + JSON.stringify(result.result, null, 2), 'success');
        
        document.getElementById('meta-result').classList.remove('hidden');
        document.getElementById('meta-title').textContent = result.result.title;
        document.getElementById('meta-desc').textContent = result.result.description;
        document.getElementById('meta-keywords').textContent = result.result.keywords.join(', ');
        
        if (result.result.demo_mode) {
            document.getElementById('demo-badge').classList.remove('hidden');
        }
        
    } catch (error) {
        log('Error: ' + error.message, 'error');
    }
});

// Test Keywords
document.getElementById('test-keywords-btn')?.addEventListener('click', async function() {
    const content = document.getElementById('keywords-content').value;
    
    log(`Analyzing keywords for: ${content.substring(0, 50)}...`);
    
    try {
        const response = await fetch('/admin/seo/test/keywords', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content })
        });
        
        const result = await response.json();
        log('Keywords: ' + result.keywords.join(', '), 'success');
        
        document.getElementById('keywords-result').classList.remove('hidden');
        const list = document.getElementById('keywords-list');
        list.innerHTML = result.keywords.map(k => `<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">${k}</span>`).join('');
        
    } catch (error) {
        log('Error: ' + error.message, 'error');
    }
});

// Test Structured Data
document.getElementById('test-sd-btn')?.addEventListener('click', async function() {
    const data = {
        title: document.getElementById('sd-title').value,
        price: document.getElementById('sd-price').value,
        brand: document.getElementById('sd-brand').value,
        model: document.getElementById('sd-model').value
    };
    
    log('Generating Structured Data...');
    
    try {
        const response = await fetch('/admin/seo/test/structured-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        log('Structured Data generated successfully', 'success');
        
        document.getElementById('sd-result').classList.remove('hidden');
        document.getElementById('sd-json').textContent = JSON.stringify(result.result, null, 2);
        
    } catch (error) {
        log('Error: ' + error.message, 'error');
    }
});

// Clear Console
document.getElementById('clear-console')?.addEventListener('click', function() {
    document.getElementById('console-output').innerHTML = '<div class="text-gray-500">// Console cleared...</div>';
});
</script>
@endsection

