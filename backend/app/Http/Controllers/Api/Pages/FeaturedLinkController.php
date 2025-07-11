<?php

namespace App\Http\Controllers\Api\Pages;

use App\Http\Controllers\Controller;
use App\Models\FeaturedLink;

class FeaturedLinkController extends Controller
{
    public function index(string $pageId)
    {
        return FeaturedLink::where('page_id', $pageId)
            ->get();
    }
}
