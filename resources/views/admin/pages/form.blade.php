@extends('admin.layout')

@section('title', isset($page) ? 'تعديل صفحة' : 'إنشاء صفحة جديدة')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.pages.index') }}" class="w-10 h-10 rounded-md bg-white border border-border flex items-center justify-center hover:bg-muted transition-all">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </a>
        <div>
            <h1 class="text-2xl font-bold">{{ isset($page) ? 'تعديل الصفحة' : 'إنشاء صفحة جديدة' }}</h1>
            <p class="text-muted-foreground text-sm">استخدم الكود البرمجي HTML أو النصوص البسيطة في المحتوى.</p>
        </div>
    </div>

    <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}" method="POST" class="space-y-6">
        @csrf
        @if(isset($page)) @method('PUT') @endif

        <div class="bg-white rounded-lg border border-border shadow-soft p-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Title --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold opacity-70">عنوان الصفحة</label>
                    <input type="text" name="title" value="{{ old('title', $page->title ?? '') }}" required class="w-full bg-muted/30 border border-border rounded-md px-4 py-3 focus:outline-none focus:border-primary transition-all">
                </div>

                {{-- Slug --}}
                <div class="space-y-2">
                    <label class="text-sm font-bold opacity-70">الرابط (Slug)</label>
                    <input type="text" name="slug" value="{{ old('slug', $page->slug ?? '') }}" placeholder="example-page-url" class="w-full bg-muted/30 border border-border rounded-md px-4 py-3 focus:outline-none focus:border-primary transition-all">
                </div>
            </div>

            {{-- Meta Description --}}
            <div class="space-y-2">
                <label class="text-sm font-bold opacity-70">وصف SEO (Meta Description)</label>
                <textarea name="meta_description" rows="2" class="w-full bg-muted/30 border border-border rounded-md px-4 py-3 focus:outline-none focus:border-primary transition-all">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
            </div>

            {{-- Hero Image --}}
            <div class="space-y-2">
                <label class="text-sm font-bold opacity-70">رابط صورة الـ Hero (اختياري)</label>
                <input type="text" name="hero_image" value="{{ old('hero_image', $page->hero_image ?? '') }}" placeholder="https://..." class="w-full bg-muted/30 border border-border rounded-md px-4 py-3 focus:outline-none focus:border-primary transition-all">
            </div>

            {{-- Content --}}
            <div class="space-y-2">
                <label class="text-sm font-bold opacity-70">المحتوى (يدعم HTML)</label>
                <textarea name="content" rows="15" required class="w-full bg-muted/30 border border-border rounded-md p-4 font-mono text-sm focus:outline-none focus:border-primary transition-all">{{ old('content', $page->content ?? '') }}</textarea>
            </div>

            {{-- Published Status --}}
            <div class="flex items-center gap-3 bg-muted/20 p-4 rounded-md border border-border/50">
                <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-border text-primary focus:ring-primary">
                <label for="is_published" class="text-sm font-bold cursor-pointer">نشر الصفحة فوراً</label>
            </div>
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.pages.index') }}" class="px-8 py-3 rounded-md font-bold hover:bg-muted transition-all">إلغاء</a>
            <button type="submit" class="bg-primary text-primary-foreground px-10 py-3 rounded-md font-black shadow-lg shadow-primary/20 hover:opacity-90 transition-all">
                {{ isset($page) ? 'حفظ التغييرات' : 'إنشاء الصفحة' }}
            </button>
        </div>
    </form>
</div>
@endsection

