<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::withCount('items')->get();
        return view('admin.menus.index', compact('menus'));
    }

    public function show(Menu $menu)
    {
        $menu->load(['items' => function($q) {
            $q->whereNull('parent_id')->with('children');
        }]);
        return view('admin.menus.show', compact('menu'));
    }

    public function addItem(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'url' => 'nullable|string',
            'parent_id' => 'nullable|exists:menu_items,id',
        ]);

        $validated['menu_id'] = $menu->id;
        $validated['order'] = $menu->items()->count() + 1;

        MenuItem::create($validated);

        return back()->with('success', 'Nav item added.');
    }

    public function removeItem(MenuItem $item)
    {
        $item->delete();
        return back()->with('success', 'Nav item removed.');
    }

    public function updateItem(Request $request, MenuItem $item)
    {
        $validated = $request->validate([
            'label' => 'required|string',
            'url' => 'nullable|string',
            'order' => 'required|integer',
        ]);

        $item->update($validated);

        return back()->with('success', 'Nav item synchronized.');
    }
}
