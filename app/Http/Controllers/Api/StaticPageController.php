<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;

class StaticPageController extends Controller
{
   
    public function index()
    {
        $pages = StaticPage::where('is_active', true)
            ->orderBy('title')
            ->get();

        return response()->json([
            'success' => true,
            'data' => StaticPageResource::collection($pages)
        ]);
    }


    public function show($slug)
    {
        $page = StaticPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new StaticPageResource($page)
        ]);
    }
}
