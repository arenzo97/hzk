<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function index()
    {
        return Page::where('published', true)
            ->with('author:id,first_name,last_name') // Include author info
            ->orderBy('sort')
            ->get();
    }

    public function show(string $slug)
    {
        return Page::where('slug', $slug)
            ->where('published', true)
            ->with('author:id,first_name,last_name')
            ->firstOrFail();
    }
}
