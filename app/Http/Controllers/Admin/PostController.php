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
        $posts      = Post::with('category')->latest()->paginate(20);
        $totalCount = Post::count();
        $liveCount  = Post::where('is_published', true)->count();
        $draftCount = Post::where('is_published', false)->count();
        return view('admin.posts.index', compact('posts', 'totalCount', 'liveCount', 'draftCount'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'category_id'         => 'nullable|exists:categories,id',
            'content_raw'         => 'required|string',
            'featured_image'      => 'nullable|string|max:1000',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'meta_description'    => 'nullable|string|max:320',
            'is_published'        => 'boolean',
        ]);

        if ($request->hasFile('featured_image_file')) {
            $path = $request->file('featured_image_file')->store('posts', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $validated['slug']         = Str::slug($validated['title']) . '-' . Str::random(4);
        $validated['content']      = ['body' => $validated['content_raw']];
        $validated['published_at'] = $request->has('is_published') ? now() : null;

        unset($validated['content_raw'], $validated['featured_image_file']);

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article published successfully.');
    }

    public function edit(Post $post)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'category_id'         => 'nullable|exists:categories,id',
            'content_raw'         => 'required|string',
            'featured_image'      => 'nullable|string|max:1000',
            'featured_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'meta_description'    => 'nullable|string|max:320',
            'is_published'        => 'boolean',
        ]);

        if ($request->hasFile('featured_image_file')) {
            // Optional: delete old local image if exists
            if ($post->featured_image && str_starts_with($post->featured_image, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $post->featured_image);
                \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPath);
            }
            $path = $request->file('featured_image_file')->store('posts', 'public');
            $validated['featured_image'] = '/storage/' . $path;
        }

        $validated['content']      = ['body' => $validated['content_raw']];
        $validated['published_at'] = ($request->has('is_published') && !$post->is_published) ? now() : $post->published_at;

        unset($validated['content_raw'], $validated['featured_image_file']);

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Article updated.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Article removed.');
    }
}
