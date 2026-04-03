@extends('admin.layout')

@section('title', isset($page) ? 'Edit Page' : 'New Page')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.pages.index') }}"
           class="w-9 h-9 rounded-xl border border-slate-200 bg-white flex items-center justify-center hover:bg-slate-50 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-600"></i>
        </a>
        <div>
            <div class="text-[0.55rem] font-black uppercase tracking-[0.3em] text-slate-400 mb-0.5">
                {{ isset($page) ? 'Editing' : 'Creating' }}
            </div>
            <h1 class="text-xl font-black text-slate-900 tracking-tight">
                {{ isset($page) ? $page->title : 'New Page' }}
            </h1>
        </div>
        @if(isset($page) && $page->is_published)
            <a href="{{ route('page.show', $page->slug) }}" target="_blank"
               class="ml-auto flex items-center gap-1.5 text-[0.65rem] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-full hover:bg-emerald-100 transition-all">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Live →
            </a>
        @endif
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm font-bold">
            @foreach($errors->all() as $error) <div>• {{ $error }}</div> @endforeach
        </div>
    @endif

    <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          method="POST" id="pageForm" class="space-y-5">
        @csrf
        @if(isset($page)) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ── LEFT: Main Content ── --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Title + Slug --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <div class="w-4 h-px bg-slate-300"></div> Page Identity
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">Page Title <span class="text-red-400">*</span></label>
                        <input type="text" name="title" id="pageTitle"
                               value="{{ old('title', $page->title ?? '') }}" required
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-semibold text-slate-900 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 transition-all"
                               placeholder="e.g. About Us">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">URL Slug</label>
                        <div class="flex items-center gap-2">
                            <span class="text-xs text-slate-400 font-mono shrink-0">/</span>
                            <input type="text" name="slug" id="pageSlug"
                                   value="{{ old('slug', $page->slug ?? '') }}"
                                   class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-mono text-slate-700 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 transition-all"
                                   placeholder="about-us">
                            <button type="button" id="regenerateSlug"
                                    class="shrink-0 px-3 py-2 rounded-lg bg-slate-100 text-slate-500 text-[0.65rem] font-black uppercase hover:bg-slate-200 transition-all">
                                Auto
                            </button>
                        </div>
                        <p class="text-[0.6rem] text-slate-400">Leave blank to auto-generate from title. Lowercase, hyphens only.</p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <div class="w-4 h-px bg-slate-300"></div> Page Content
                    </div>
                    <textarea name="content" id="pageContent" rows="18"
                              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-4 font-mono text-sm text-slate-700 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 transition-all resize-none"
                              placeholder="Write HTML or plain text content for this page...">{{ old('content', $page->content ?? '') }}</textarea>
                    <p class="text-[0.6rem] text-slate-400">Supports full HTML markup. Use semantic tags for best SEO.</p>
                </div>

                {{-- SEO --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <div class="w-4 h-px bg-slate-300"></div> SEO Settings
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">Meta Description</label>
                        <textarea name="meta_description" rows="2" maxlength="320"
                                  class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 transition-all resize-none"
                                  placeholder="Brief description for search engines (120–160 chars recommended)">{{ old('meta_description', $page->meta_description ?? '') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-700">Hero Image URL</label>
                        <input type="text" name="hero_image"
                               value="{{ old('hero_image', $page->hero_image ?? '') }}"
                               class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-700 outline-none focus:border-slate-400 focus:ring-2 focus:ring-slate-100 transition-all"
                               placeholder="https://...">
                    </div>
                </div>

            </div>

            {{-- ── RIGHT: Settings + Menu ── --}}
            <div class="space-y-5">

                {{-- Publish --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400 flex items-center gap-2">
                        <div class="w-4 h-px bg-slate-300"></div> Visibility
                    </div>
                    <label class="flex items-center justify-between cursor-pointer group">
                        <div>
                            <div class="text-sm font-bold text-slate-900">Publish Page</div>
                            <div class="text-[0.65rem] text-slate-400 mt-0.5">Make this page publicly visible</div>
                        </div>
                        <div class="relative">
                            <input type="checkbox" name="is_published" id="is_published" value="1"
                                   {{ old('is_published', $page->is_published ?? false) ? 'checked' : '' }}
                                   class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-checked:bg-emerald-500 transition-colors"></div>
                            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-sm transition-transform peer-checked:translate-x-5"></div>
                        </div>
                    </label>

                    <div class="pt-2 border-t border-slate-100">
                        <button type="submit"
                                class="w-full bg-slate-900 text-white py-3 rounded-xl text-[0.75rem] font-black uppercase tracking-widest shadow hover:bg-slate-800 transition-all flex items-center justify-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            {{ isset($page) ? 'Save Changes' : 'Create Page' }}
                        </button>
                    </div>
                </div>

                {{-- ── Menu Integration ── --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-indigo-500 flex items-center gap-2">
                        <div class="w-4 h-px bg-indigo-300"></div> Navigation Menu
                        <span class="bg-indigo-100 text-indigo-500 text-[0.42rem] font-black uppercase px-2 py-0.5 rounded-full tracking-widest">Integration</span>
                    </div>

                    @if(isset($existingMenuItem) && $existingMenuItem)
                        <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-xl p-3">
                            <i data-lucide="link-2" class="w-4 h-4 text-indigo-500 shrink-0"></i>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-indigo-700">Linked to: {{ $existingMenuItem->menu->name ?? 'Menu' }}</div>
                                <div class="text-[0.6rem] text-indigo-400 truncate">{{ $existingMenuItem->url }}</div>
                            </div>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remove_from_menu" value="1" class="w-4 h-4 rounded border-slate-300 text-red-500 focus:ring-red-300">
                            <span class="text-[0.7rem] font-bold text-red-500">Remove from menu</span>
                        </label>
                        <div class="pt-2 border-t border-slate-100 text-[0.6rem] text-slate-400">Or move to a different menu:</div>
                    @endif

                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-700">Add to Menu</label>
                            <select name="add_to_menu" id="addToMenu"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                <option value="">— Don't add to menu —</option>
                                @foreach($menus as $menu)
                                    <option value="{{ $menu->id }}"
                                        {{ old('add_to_menu', isset($existingMenuItem) ? $existingMenuItem->menu_id : '') == $menu->id ? 'selected' : '' }}>
                                        {{ $menu->name }}
                                        @if($menu->location) · {{ $menu->location }} @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="space-y-1.5" id="parentMenuWrapper" style="display:none">
                            <label class="text-xs font-bold text-slate-700">As Sub-item of</label>
                            <select name="menu_parent_id" id="menuParentSelect"
                                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs font-bold text-slate-700 outline-none focus:border-indigo-400 transition-all">
                                <option value="">— Top-level item —</option>
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

                    <p class="text-[0.6rem] text-slate-400 leading-relaxed">
                        The page title becomes the menu label automatically. You can rename it later in the Menu builder.
                    </p>
                </div>

                {{-- Quick Links --}}
                <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 space-y-2">
                    <div class="text-[0.6rem] font-black uppercase tracking-widest text-slate-400">Quick Links</div>
                    <a href="{{ route('admin.menus.index') }}" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                        <i data-lucide="navigation" class="w-3.5 h-3.5"></i> Menu Builder
                    </a>
                    @if(isset($page))
                        <a href="{{ route('page.show', $page->slug) }}" target="_blank" class="flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-slate-900 transition-colors">
                            <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Preview Page
                        </a>
                    @endif
                </div>

            </div>

        </div>
    </form>
</div>

<script>
(function() {
    // Auto-generate slug from title
    const titleEl = document.getElementById('pageTitle');
    const slugEl  = document.getElementById('pageSlug');
    const regenBtn = document.getElementById('regenerateSlug');
    const menuSel = document.getElementById('addToMenu');
    const parentWrapper = document.getElementById('parentMenuWrapper');
    const parentSel = document.getElementById('menuParentSelect');

    function toSlug(str) {
        return str.toLowerCase()
                  .replace(/[^\w\s-]/g, '')
                  .replace(/\s+/g, '-')
                  .replace(/-+/g, '-')
                  .trim();
    }

    // Auto-fill slug on title input (only if slug is empty or unchanged from title)
    titleEl?.addEventListener('input', function() {
        if (!slugEl.dataset.manual) {
            slugEl.value = toSlug(this.value);
        }
    });

    slugEl?.addEventListener('input', function() {
        this.dataset.manual = '1';
        this.value = toSlug(this.value);
    });

    regenBtn?.addEventListener('click', function() {
        if (titleEl.value) {
            delete slugEl.dataset.manual;
            slugEl.value = toSlug(titleEl.value);
        }
    });

    // Show/hide parent selector based on menu selection
    function loadMenuItems(menuId) {
        if (!menuId) {
            parentWrapper.style.display = 'none';
            parentSel.innerHTML = '<option value="">— Top-level item —</option>';
            return;
        }
        parentWrapper.style.display = 'block';
        fetch(`/admin/pages/${menuId}/menu-items`)
            .then(r => r.json())
            .then(items => {
                parentSel.innerHTML = '<option value="">— Top-level item —</option>';
                items.forEach(item => {
                    parentSel.innerHTML += `<option value="${item.id}">${item.label}</option>`;
                });
            })
            .catch(() => {
                parentWrapper.style.display = 'none';
            });
    }

    menuSel?.addEventListener('change', function() {
        loadMenuItems(this.value);
    });

    // Init: if a menu is pre-selected
    if (menuSel?.value) {
        parentWrapper.style.display = 'block';
    }
})();
</script>
@endsection
