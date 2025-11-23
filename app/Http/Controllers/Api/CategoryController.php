<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::with('subcategories')
            ->active()
            ->orderBy('display_order')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => CategoryResource::collection($categories)
        ]);
    }

   
    public function show($slug)
    {
        $category = Category::with(['subcategories', 'perks' => function ($query) {
            $query->active()
                ->published()
                ->with(['subcategory', 'statistics', 'media'])
                ->orderBy('published_at', 'desc');
        }])
            ->where('slug', $slug)
            ->active()
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new CategoryResource($category)
        ]);
    }
}
