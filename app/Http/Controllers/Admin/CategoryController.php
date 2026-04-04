<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('posts')->orderBy('name')->get();
        return view('admin.posts.categories', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name',
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        Category::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'name' => $validated['name']]);
        }
        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:categories,name,' . $category->id,
        ]);
        $validated['slug'] = Str::slug($validated['name']);
        $category->update($validated);
        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        // Nullify posts before deleting
        $category->posts()->update(['category_id' => null]);
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
