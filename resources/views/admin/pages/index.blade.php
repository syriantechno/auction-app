@extends('admin.layout')

@section('title', 'قائمة الصفحات الديناميكية')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold">مولد الصفحات المحترف</h1>
            <p class="text-muted-foreground text-sm">تحكم في جميع الصفحات الديناميكية من هنا.</p>
        </div>
        <a href="{{ route('admin.pages.create') }}" class="inline-flex items-center gap-2 bg-primary text-primary-foreground px-4 py-2 rounded-lg hover:opacity-90 transition-all font-bold">
            <i data-lucide="plus" class="w-4 h-4"></i>
            إنشاء صفحة جديدة
        </a>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-6 rounded-lg bg-white border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-md bg-primary/10 flex items-center justify-center text-primary">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-muted-foreground uppercase">إجمالي الصفحات</div>
                <div class="text-2xl font-black">{{ $pages->count() }}</div>
            </div>
        </div>
        <div class="p-6 rounded-lg bg-white border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-md bg-green-500/10 flex items-center justify-center text-green-500">
                <i data-lucide="check-circle" class="w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-muted-foreground uppercase">صفحات منشورة</div>
                <div class="text-2xl font-black">{{ $pages->where('is_published', true)->count() }}</div>
            </div>
        </div>
        <div class="p-6 rounded-lg bg-white border border-border shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-md bg-[#ff6900]/10 flex items-center justify-center text-[#ff6900]">
                <i data-lucide="clock" class="w-6 h-6"></i>
            </div>
            <div>
                <div class="text-sm font-bold text-muted-foreground uppercase">مسودات</div>
                <div class="text-2xl font-black">{{ $pages->where('is_published', false)->count() }}</div>
            </div>
        </div>
    </div>

    {{-- Pages Table --}}
    <div class="bg-white rounded-lg border border-border shadow-soft overflow-hidden">
        <table class="w-full text-right border-collapse">
            <thead class="bg-muted/30">
                <tr>
                    <th class="px-6 py-4 font-bold text-sm">العنوان</th>
                    <th class="px-6 py-4 font-bold text-sm">الرابط (Slug)</th>
                    <th class="px-6 py-4 font-bold text-sm">الحالة</th>
                    <th class="px-6 py-4 font-bold text-sm">تاريخ الإنشاء</th>
                    <th class="px-6 py-4 font-bold text-sm">العمليات</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse($pages as $page)
                <tr class="hover:bg-muted/10 transition-colors">
                    <td class="px-6 py-4">
                        <div class="font-bold text-deep-900">{{ $page->title }}</div>
                        <div class="text-[0.65rem] text-muted-foreground mt-1">{{ Str::limit($page->meta_description, 50) }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <code class="bg-muted px-2 py-1 rounded text-xs">/{{ $page->slug }}</code>
                    </td>
                    <td class="px-6 py-4">
                        @if($page->is_published)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-500/10 text-green-600 text-[0.65rem] font-black uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                منشور
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#ff6900]/10 text-orange-600 text-[0.65rem] font-black uppercase">
                                <span class="w-1.5 h-1.5 rounded-full bg-orange-600"></span>
                                مسودة
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-muted-foreground">
                        {{ $page->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="w-8 h-8 rounded-lg border border-border flex items-center justify-center hover:bg-muted transition-all text-muted-foreground">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page) }}" class="w-8 h-8 rounded-lg border border-border flex items-center justify-center hover:bg-primary hover:text-white transition-all text-muted-foreground">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" onsubmit="return confirm('هل أنت متأكد؟')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg border border-border flex items-center justify-center hover:bg-red-500 hover:text-white transition-all text-muted-foreground">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center gap-4 text-muted-foreground">
                            <i data-lucide="file-x-2" class="w-12 h-12 opacity-20"></i>
                            <div class="font-bold">لا توجد صفحات حالياً.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

