<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AISEOService;
use Illuminate\Http\Request;

class SEOTestController extends Controller
{
    /**
     * صفحة تجريب وهمية لاختبار SEO
     */
    public function index()
    {
        return view('admin.seo.test');
    }

    /**
     * اختبار توليد Meta Tags
     */
    public function testMetaTags(Request $request)
    {
        $content = $request->input('content', '2023 BMW X5 M Sport Package, low mileage, excellent condition');
        $type = $request->input('type', 'auction');

        $seoService = app(AISEOService::class);
        $result = $seoService->generateMetaTags($content, $type);

        return response()->json([
            'success' => true,
            'content' => $content,
            'type' => $type,
            'result' => $result,
            'is_demo' => $result['demo_mode'] ?? false
        ]);
    }

    /**
     * اختبار توليد Structured Data
     */
    public function testStructuredData(Request $request)
    {
        $data = [
            'title' => $request->input('title', '2023 BMW X5'),
            'description' => $request->input('description', 'Premium SUV with M Sport package'),
            'price' => $request->input('price', 75000),
            'brand' => $request->input('brand', 'BMW'),
            'model' => $request->input('model', 'X5'),
            'year' => $request->input('year', 2023),
        ];

        $seoService = app(AISEOService::class);
        $result = $seoService->generateStructuredData($data, 'Product');

        return response()->json([
            'success' => true,
            'data' => $data,
            'result' => $result
        ]);
    }

    /**
     * اختبار تحليل الكلمات المفتاحية
     */
    public function testKeywords(Request $request)
    {
        $content = $request->input('content', '2023 Mercedes-Benz S-Class luxury sedan with AMG package');

        $seoService = app(AISEOService::class);
        $result = $seoService->analyzeKeywords($content);

        return response()->json([
            'success' => true,
            'content' => $content,
            'keywords' => $result
        ]);
    }

    /**
     * اختبار API connection
     */
    public function testApiConnection()
    {
        $settings = \App\Models\SEOSettings::getCurrent();
        
        try {
            $seoService = app(AISEOService::class);
            $testResponse = $seoService->generateMetaTags('Test BMW X5', 'auction');
            
            return response()->json([
                'success' => !empty($testResponse['title']),
                'api_key_exists' => !empty($settings->agent_router_api_key),
                'model' => $settings->agent_router_model,
                'base_url' => $settings->agent_router_base_url,
                'test_result' => $testResponse,
                'is_demo' => $testResponse['demo_mode'] ?? false
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * حذف صفحة التجريب ( dummy method )
     */
    public function deleteTestPage()
    {
        return redirect()->route('admin.seo.dashboard')
            ->with('info', 'لحذف صفحة التجريب، احذف الملفات:\n- app/Http/Controllers/Admin/SEOTestController.php\n- resources/views/admin/seo/test.blade.php');
    }
}
