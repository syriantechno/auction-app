<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('category')->latest()->paginate(20);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'content_raw' => 'required|string',
            'featured_image' => 'nullable|url',
            'is_published' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['content'] = ['body' => $validated['content_raw']]; // Wrap in JSON structure
        $validated['published_at'] = $request->has('is_published') ? now() : null;

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article published successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content_raw' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $validated['content'] = ['body' => $validated['content_raw']];
        $validated['published_at'] = $request->has('is_published') && !$post->is_published ? now() : $post->published_at;

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Article removed.');
    }
}
