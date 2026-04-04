@extends('admin.layout')

@section('title', isset($page) ? 'Edit — ' . $page->title : 'New Page')

@push('head')
{{-- Jodit v3 (stable CDN) --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3.24.5/build/jodit.min.css">
<script src="https://cdn.jsdelivr.net/npm/jodit@3.24.5/build/jodit.min.js"></script>
@endpush

@section('content')
<div class="space-y-4">

    {{-- ── Header ── --}}
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.pages.index') }}"
           class="w-8 h-8 rounded-lg border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50 transition-all shadow-sm shrink-0">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
        </a>
        <div class="flex-1 min-w-0">
            <div class="text-[0.55rem] font-black uppercase tracking-[0.3em] text-slate-400">
                {{ isset($page) ? 'Editing Page' : 'Creating New Page' }}
            </div>
            <h1 class="text-lg font-black text-slate-900 truncate">
                {{ isset($page) ? $page->title : 'New Page' }}
            </h1>
        </div>
        @if(isset($page) && $page->is_published)
            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
               class="shrink-0 flex items-center gap-1.5 text-[0.65rem] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full hover:bg-emerald-100 transition-all">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live ↗
            </a>
        @endif
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs font-bold">
            @foreach($errors->all() as $error)<div>• {{ $error }}</div>@endforeach
        </div>
    @endif

    <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          method="POST" id="pageForm" enctype="multipart/form-data">
        @csrf
        @if(isset($page)) @method('PUT') @endif

        {{-- ── Two-column layout: wide left + narrow right ── --}}
        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4 items-start">

            {{-- ═══════ LEFT (3/4) ═══════ --}}
            <div class="xl:col-span-3 space-y-4">

                {{-- Title + Slug --}}
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6">
                    <div class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest mb-4 ml-1">Page Identity</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">Page Title <span class="text-red-400">*</span></label>
                            <input type="text" name="title" id="pageTitle"
                                   value="{{ old('title', $page->title ?? '') }}" required
                                   class="w-full h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.9rem] font-normal text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                   placeholder="e.g. About Us">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-widest ml-1">URL Slug</label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-slate-400 font-mono shrink-0">/</span>
                                <input type="text" name="slug" id="pageSlug"
                                       value="{{ old('slug', $page->slug ?? '') }}"
                                       class="flex-1 h-[44px] bg-slate-50 border border-slate-300 rounded-md px-4 py-2 text-[0.85rem] font-mono text-slate-700 outline-none focus:bg-white focus:border-orange-500/40 transition-all"
                                       placeholder="about-us">
                                <button type="button" id="regenerateSlug"
                                        class="shrink-0 h-[44px] px-4 rounded-md bg-slate-100 border border-slate-300 text-slate-500 text-[0.6rem] font-medium uppercase tracking-widest hover:bg-slate-200 transition-all">
                                    Auto
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── JODIT EDITOR ── --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
                        <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-slate-400 flex items-center gap-2">
                            <div class="w-3 h-px bg-slate-300"></div> Page Content
                        </div>
                        <span class="bg-emerald-100 text-emerald-600 text-[0.45rem] font-black uppercase tracking-widest px-2 py-0.5 rounded-full">Rich Editor</span>
                    </div>
                    {{-- Jodit initializes on this element --}}
                    <textarea name="content" id="pageContent">{{ old('content', $page->content ?? '') }}</textarea>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
                    <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-slate-400 mb-4 flex items-center gap-2">
                        <div class="w-3 h-px bg-slate-300"></div> SEO Settings
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-slate-700">Meta Description</label>
                        <textarea name="meta_description" rows="3" maxlength="320"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3.5 py-2.5 text-xs text-slate-700 outline-none focus:border-slate-400 transition-all resize-none"
                                  placeholder="120–160 chars for best SEO results">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                    </div>
                </div>

            </div>

            {{-- ═══════ RIGHT (1/4) ═══════ --}}
            <div class="space-y-4">

                {{-- Publish + Save --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-4">
                    <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-slate-400 flex items-center gap-2">
                        <div class="w-3 h-px bg-slate-300"></div> Visibility
                    </div>
                    <label class="flex items-center justify-between cursor-pointer">
                        <div>
                            <div class="text-sm font-bold text-slate-900">Publish</div>
                            <div class="text-[0.6rem] text-slate-400 mt-0.5">Make publicly visible</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-10 h-5 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-4 h-4 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>
                    <button type="submit"
                            class="w-full bg-slate-900 text-white py-2.5 rounded-xl text-[0.7rem] font-black uppercase tracking-widest shadow hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-3.5 h-3.5"></i>
                        {{ isset($page) ? 'Save Changes' : 'Create Page' }}
                    </button>
                </div>

                {{-- ── Hero Image Upload ── --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-2">
                        <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-slate-400 flex items-center gap-2">
                            <div class="w-3 h-px bg-slate-300"></div> Hero Image
                        </div>
                        <span class="text-[0.4rem] font-black uppercase bg-amber-100 text-amber-600 px-1.5 py-0.5 rounded-full tracking-widest">Per Page</span>
                    </div>

                    {{-- Drop Zone --}}
                    <div id="heroDropZone"
                         class="relative cursor-pointer group"
                         onclick="document.getElementById('heroFileInput').click()"
                         ondragover="event.preventDefault();this.classList.add('border-[#ff4605]','bg-orange-50')"
                         ondragleave="this.classList.remove('border-[#ff4605]','bg-orange-50')"
                         ondrop="handleHeroDrop(event)">

                        {{-- Preview (hidden until image chosen) --}}
                        <div id="heroPreview" class="{{ (isset($page) && $page->hero_image) ? '' : 'hidden' }} relative">
                            <img id="heroPreviewImg"
                                 src="{{ $page->hero_image ?? '' }}"
                                 class="w-full h-36 object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <span class="text-white text-[0.65rem] font-black uppercase tracking-wider">Change Image</span>
                            </div>
                            <button type="button" id="heroClearBtn"
                                    onclick="event.stopPropagation();clearHeroImage()"
                                    class="absolute top-2 right-2 w-6 h-6 rounded-full bg-red-500 text-white flex items-center justify-center hover:bg-red-600 transition-all shadow">
                                <i data-lucide="x" class="w-3 h-3"></i>
                            </button>
                        </div>

                        {{-- Empty State --}}
                        <div id="heroEmpty" class="{{ (isset($page) && $page->hero_image) ? 'hidden' : '' }} p-6 flex flex-col items-center justify-center gap-2 border-2 border-dashed border-slate-200 m-3 rounded-xl hover:border-[#ff4605] transition-all">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center">
                                <i data-lucide="image-plus" class="w-5 h-5 text-slate-400"></i>
                            </div>
                            <div class="text-center">
                                <div class="text-xs font-bold text-slate-700">Drop image or click</div>
                                <div class="text-[0.6rem] text-slate-400 mt-0.5">JPG / PNG / WebP · Max 4MB</div>
                            </div>
                        </div>

                        <input type="file" id="heroFileInput" name="hero_image_file"
                               accept="image/*" class="hidden"
                               onchange="handleHeroFile(this.files[0])">
                    </div>

                    {{-- URL fallback --}}
                    <div class="px-4 pb-4 pt-2">
                        <div class="text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-1.5">Or paste URL</div>
                        <input type="text" name="hero_image" id="heroUrlInput"
                               value="{{ old('hero_image', $page->hero_image ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[0.65rem] font-mono text-slate-600 outline-none focus:border-slate-400 transition-all"
                               placeholder="https://..."
                               oninput="if(this.value){showHeroPreview(this.value)}">
                    </div>
                </div>

                {{-- Menu Integration --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 space-y-3">
                    <div class="text-[0.55rem] font-black uppercase tracking-[0.28em] text-indigo-500 flex items-center gap-2">
                        <div class="w-3 h-px bg-indigo-300"></div> Navigation
                        <span class="bg-indigo-100 text-indigo-500 text-[0.4rem] font-black uppercase px-1.5 py-0.5 rounded-full tracking-widest">Menu</span>
                    </div>

                    @if(isset($existingMenuItem) && $existingMenuItem)
                        <div class="flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-lg p-2.5">
                            <i data-lucide="link-2" class="w-3.5 h-3.5 text-indigo-500 shrink-0"></i>
                            <div class="min-w-0 text-xs">
                                <div class="font-bold text-indigo-700 truncate">{{ $existingMenuItem->menu->name ?? 'Menu' }}</div>
                                <div class="text-indigo-400 truncate text-[0.6rem]">{{ $existingMenuItem->url }}</div>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remove_from_menu" value="1" class="w-3.5 h-3.5 rounded border-slate-300 text-red-500">
                            <span class="text-[0.65rem] font-bold text-red-500">Remove from menu</span>
                        </label>
                    @endif

                    <div class="space-y-1.5">
                        <label class="text-[0.6rem] font-black text-slate-600 uppercase tracking-wider">Add to Menu</label>
                        <select name="add_to_menu" id="addToMenu"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all">
                            <option value="">— None —</option>
                            @foreach($menus as $menu)
                                <option value="{{ $menu->id }}"
                                    {{ old('add_to_menu', isset($existingMenuItem) ? $existingMenuItem->menu_id : '') == $menu->id ? 'selected' : '' }}>
                                    {{ $menu->name }}@if($menu->location) · {{ $menu->location }}@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-1.5" id="parentMenuWrapper" style="display:none">
                        <label class="text-[0.6rem] font-black text-slate-600 uppercase tracking-wider">As Sub-item of</label>
                        <select name="menu_parent_id" id="menuParentSelect"
                                class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all">
                            <option value="">— Top level —</option>
                            @if(isset($rootMenuItems))
                                @foreach($rootMenuItems as $ri)
                                    <option value="{{ $ri->id }}"
                                        {{ old('menu_parent_id', isset($existingMenuItem) ? $existingMenuItem->parent_id : '') == $ri->id ? 'selected' : '' }}>
                                        {{ $ri->label }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-2">
                    <div class="text-[0.55rem] font-black uppercase tracking-widest text-slate-400 mb-2">Quick Links</div>
                    <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        <i data-lucide="navigation" class="w-3 h-3"></i> Menu Builder
                    </a>
                    @if(isset($page))
                        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                            <i data-lucide="external-link" class="w-3 h-3"></i> Preview Page
                        </a>
                    @endif
                    <a href="{{ route('admin.pages.index') }}" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        <i data-lucide="list" class="w-3 h-3"></i> All Pages
                    </a>
                </div>

            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // ── Slug auto-generate ───────────────────────────────────────────
    const titleEl  = document.getElementById('pageTitle');
    const slugEl   = document.getElementById('pageSlug');
    const regenBtn = document.getElementById('regenerateSlug');
    const menuSel  = document.getElementById('addToMenu');
    const parentWrapper = document.getElementById('parentMenuWrapper');
    const parentSel     = document.getElementById('menuParentSelect');

    function toSlug(str) {
        return str.toLowerCase().replace(/[^\w\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').trim();
    }
    titleEl?.addEventListener('input', function() {
        if (!slugEl.dataset.manual) slugEl.value = toSlug(this.value);
    });
    slugEl?.addEventListener('input', function() {
        this.dataset.manual = '1';
        this.value = toSlug(this.value);
    });
    regenBtn?.addEventListener('click', function() {
        if (titleEl.value) { delete slugEl.dataset.manual; slugEl.value = toSlug(titleEl.value); }
    });

    // ── Menu items AJAX ──────────────────────────────────────────────
    function loadMenuItems(menuId) {
        if (!menuId) { parentWrapper.style.display = 'none'; return; }
        parentWrapper.style.display = 'block';
        fetch(`/admin/pages/${menuId}/menu-items`)
            .then(r => r.json())
            .then(items => {
                parentSel.innerHTML = '<option value="">— Top level —</option>';
                items.forEach(i => { parentSel.innerHTML += `<option value="${i.id}">${i.label}</option>`; });
            }).catch(() => { parentWrapper.style.display = 'none'; });
    }
    menuSel?.addEventListener('change', function() { loadMenuItems(this.value); });
    if (menuSel?.value) parentWrapper.style.display = 'block';

    // ── Jodit Rich Editor ────────────────────────────────────────────
    if (typeof Jodit !== 'undefined') {
        const editor = Jodit.make('#pageContent', {
            height:             640,
            minHeight:          400,
            toolbarAdaptive:    false,
            toolbarSticky:      true,
            showCharsCounter:   true,
            showWordsCounter:   true,
            showXPathInStatusbar: false,
            uploader:           { insertImageAsBase64URI: true },
            buttons: [
                'undo','redo','|',
                'bold','italic','underline','strikethrough','|',
                'ul','ol','|',
                'outdent','indent','|',
                'font','fontsize','brush','paragraph','|',
                'image','table','link','|',
                'align','|',
                'hr','eraser','|',
                'fullsize','source'
            ],
            style: { font: '15px/1.8 "Plus Jakarta Sans",sans-serif', color: '#334155' },
        });
        // Sync to textarea on submit
        document.getElementById('pageForm').addEventListener('submit', function() {
            document.getElementById('pageContent').value = editor.value;
        });
    } else {
        // Fallback: show raw textarea if Jodit fails
        const ta = document.getElementById('pageContent');
        ta.style.cssText = 'width:100%;min-height:500px;padding:1rem;font-family:monospace;font-size:13px;border:1px solid #e2e8f0;border-radius:0;outline:none;';
    }

    // ── Hero Image Upload ─────────────────────────────────────────────
    window.showHeroPreview = function(src) {
        document.getElementById('heroPreviewImg').src = src;
        document.getElementById('heroPreview').classList.remove('hidden');
        document.getElementById('heroEmpty').classList.add('hidden');
    };
    window.clearHeroImage = function() {
        document.getElementById('heroPreviewImg').src = '';
        document.getElementById('heroPreview').classList.add('hidden');
        document.getElementById('heroEmpty').classList.remove('hidden');
        document.getElementById('heroFileInput').value = '';
        document.getElementById('heroUrlInput').value = '';
    };
    window.handleHeroFile = function(file) {
        if (!file) return;
        if (file.size > 4 * 1024 * 1024) {
            alert('Image too large — max 4MB.');
            return;
        }
        const reader = new FileReader();
        reader.onload = e => showHeroPreview(e.target.result);
        reader.readAsDataURL(file);
        // Clear URL field since we're using file
        document.getElementById('heroUrlInput').value = '';
    };
    window.handleHeroDrop = function(e) {
        e.preventDefault();
        e.currentTarget.classList.remove('border-[#ff4605]','bg-orange-50');
        const file = e.dataTransfer.files[0];
        if (file && file.type.startsWith('image/')) {
            document.getElementById('heroFileInput').files = e.dataTransfer.files;
            handleHeroFile(file);
        }
    };

});
</script>
@endsection
