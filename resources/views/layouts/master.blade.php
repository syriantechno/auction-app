<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'أوتومزاد - مزادات السيارات الفاخرة')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a',
                        },
                        gold: {
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Tajawal', sans-serif; }
        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card-hover { transition: all 0.3s ease; }
        .card-hover:hover { transform: translateY(-5px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    </style>
</head>
<body class="min-h-screen" style="background-color: #e7e7e7;">
    <!-- Header -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 space-x-reverse">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">أوتومزاد</span>
                    </a>
                </div>
                
                <div class="flex items-center space-x-6 space-x-reverse">
                    <a href="{{ route('auctions.index') }}" class="text-gray-700 hover:text-primary-600 font-medium transition">المزادات</a>
                    
                    @auth
                        <div class="flex items-center space-x-4 space-x-reverse">
                            <span class="text-gray-700">{{ auth()->user()->name }}</span>
                            <a href="/admin" class="bg-primary-600 text-white px-4 py-2 rounded-lg hover:bg-primary-700 transition">لوحة التحكم</a>
                            <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-700">خروج</a>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition font-medium">دخول</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">أوتومزاد</h3>
                    <p class="text-gray-400">منصة رائدة لمزادات السيارات الفاخرة في الوطن العربي</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">روابط سريعة</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="{{ route('auctions.index') }}" class="hover:text-white transition">المزادات الحالية</a></li>
                        <li><a href="/admin" class="hover:text-white transition">لوحة التحكم</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">تواصل معنا</h3>
                    <p class="text-gray-400">support@automazad.com</p>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-500">
                © 2026 أوتومزاد. جميع الحقوق محفوظة.
            </div>
        </div>
    </footer>
</body>
</html>

