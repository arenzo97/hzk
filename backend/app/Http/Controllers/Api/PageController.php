<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        return Page::where('published', true)
            ->with('author:id,name') // include author info
            ->latest()
            ->get();
    }

    public function show(string $slug)
    {
        return Page::where('slug', $slug)
            ->where('published', true)
            ->with('author:id,name')
            ->firstOrFail();
    }
}
