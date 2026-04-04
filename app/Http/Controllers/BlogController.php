<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with('category')
            ->where('is_published', true)
            ->latest('published_at');

        // Category filter
        if ($request->filled('cat')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->cat));
        }

        $posts      = $query->paginate(9)->withQueryString();
        $categories = Category::withCount(['posts' => fn($q) => $q->where('is_published', true)])
            ->having('posts_count', '>', 0)
            ->orderBy('name')
            ->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(string $slug)
    {
        $post = Post::with('category')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Related posts from same category
        $related = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Latest posts for sidebar
        $latestPosts = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Catalog (Brands) for sidebar
        $brands = \App\Models\Brand::orderBy('name')->get();

        return view('blog.show', compact('post', 'related', 'latestPosts', 'brands'));
    }
}
