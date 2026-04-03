<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        $menu->load(['items' => function ($q) {
            $q->whereNull('parent_id')->with(['children.page', 'page']);
        }]);

        $pages = Page::where('is_published', true)->orderBy('title')->get(['id', 'title', 'slug']);

        return view('admin.menus.show', compact('menu', 'pages'));
    }

    public function addItem(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'label'     => 'required|string|max:100',
            'url'       => 'nullable|string|max:500',
            'page_id'   => 'nullable|exists:pages,id',
            'parent_id' => 'nullable|exists:menu_items,id',
            'target'    => 'nullable|in:_self,_blank',
            'icon'      => 'nullable|string|max:60',
        ]);

        // If a page is selected and no label was given, use page title
        if (!empty($validated['page_id']) && empty(trim($validated['label']))) {
            $page = Page::find($validated['page_id']);
            $validated['label'] = $page?->title ?? $validated['label'];
        }

        // Auto-fill URL from page slug if page selected
        if (!empty($validated['page_id']) && empty($validated['url'])) {
            $page = Page::find($validated['page_id']);
            $validated['url'] = '/' . $page?->slug;
        }

        $validated['menu_id'] = $menu->id;
        $validated['order']   = $menu->items()->count() + 1;
        $validated['target']  = $validated['target'] ?? '_self';

        MenuItem::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Menu item added successfully.');
    }

    public function removeItem(MenuItem $item)
    {
        // Remove children first
        $item->children()->delete();
        $item->delete();

        return back()->with('success', 'Menu item removed.');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $validated = $request->validate([
            'label'   => 'required|string|max:100',
            'url'     => 'nullable|string|max:500',
            'page_id' => 'nullable|exists:pages,id',
            'order'   => 'required|integer',
            'target'  => 'nullable|in:_self,_blank',
            'icon'    => 'nullable|string|max:60',
        ]);

        $item->update($validated);

        return back()->with('success', 'Menu item updated.');
    }

    public function reorder(Request $request, Menu $menu)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:menu_items,id',
        ]);

        foreach ($request->order as $position => $id) {
            MenuItem::where('id', $id)->where('menu_id', $menu->id)->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
