<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where(function($q) {
                        $q->where('is_published', true)->orWhere('is_active', true);
                    })
                    ->firstOrFail();

        return view('page', compact('page'));
    }
}
