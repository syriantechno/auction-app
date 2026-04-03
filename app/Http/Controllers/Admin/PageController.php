<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::latest()->get();
        $menus = Menu::orderBy('name')->get(['id', 'name', 'location']);
        return view('admin.pages.index', compact('pages', 'menus'));
    }

    public function create()
    {
        $menus = Menu::orderBy('name')->get(['id', 'name', 'location']);
        $rootMenuItems = collect();
        return view('admin.pages.create', compact('menus', 'rootMenuItems'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|unique:pages,slug',
            'content'          => 'nullable|string',
            'hero_image'       => 'nullable|string',
            'meta_description' => 'nullable|string|max:320',
            'is_published'     => 'boolean',
            // menu linking
            'add_to_menu'      => 'nullable|exists:menus,id',
            'menu_parent_id'   => 'nullable|exists:menu_items,id',
        ]);

        $data['slug']         = $data['slug'] ? Str::slug($data['slug']) : Str::slug($data['title']);
        $data['is_published'] = $request->boolean('is_published');

        $page = Page::create($data);

        // Optionally add to a menu
        $this->syncToMenu($page, $request);

        return redirect()->route('admin.pages.index')->with('success', 'Page created successfully!');
    }

    public function edit(Page $page)
    {
        $menus = Menu::orderBy('name')->get(['id', 'name', 'location']);
        $existingMenuItem = MenuItem::where('page_id', $page->id)->first();
        $rootMenuItems = $existingMenuItem
            ? MenuItem::where('menu_id', $existingMenuItem->menu_id)->whereNull('parent_id')->get()
            : collect();

        return view('admin.pages.edit', compact('page', 'menus', 'rootMenuItems', 'existingMenuItem'));
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'required|string|unique:pages,slug,' . $page->id,
            'content'          => 'nullable|string',
            'hero_image'       => 'nullable|string',
            'meta_description' => 'nullable|string|max:320',
            'is_published'     => 'boolean',
            // menu linking
            'add_to_menu'      => 'nullable|exists:menus,id',
            'menu_parent_id'   => 'nullable|exists:menu_items,id',
            'remove_from_menu' => 'nullable|boolean',
        ]);

        $data['slug']         = Str::slug($data['slug']);
        $data['is_published'] = $request->boolean('is_published');

        $page->update($data);

        // Handle menu sync
        if ($request->boolean('remove_from_menu')) {
            MenuItem::where('page_id', $page->id)->delete();
        } else {
            $this->syncToMenu($page, $request);
        }

        return redirect()->route('admin.pages.index')->with('success', 'Page updated successfully!');
    }

    public function destroy(Page $page)
    {
        // Remove from any menus
        MenuItem::where('page_id', $page->id)->delete();
        $page->delete();

        return redirect()->route('admin.pages.index')->with('success', 'Page deleted.');
    }

    /** Add or update the page in a menu */
    private function syncToMenu(Page $page, Request $request): void
    {
        $menuId = $request->input('add_to_menu');
        if (!$menuId) return;

        $existing = MenuItem::where('page_id', $page->id)->first();

        $data = [
            'menu_id'   => $menuId,
            'page_id'   => $page->id,
            'label'     => $page->title,
            'url'       => '/' . $page->slug,
            'parent_id' => $request->input('menu_parent_id') ?: null,
            'target'    => '_self',
        ];

        if ($existing) {
            $existing->update($data);
        } else {
            $data['order'] = MenuItem::where('menu_id', $menuId)->count() + 1;
            MenuItem::create($data);
        }
    }

    /** AJAX: get root items for a given menu (for parent selector) */
    public function menuItems(Menu $menu)
    {
        $items = MenuItem::where('menu_id', $menu->id)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->get(['id', 'label']);

        return response()->json($items);
    }
}
