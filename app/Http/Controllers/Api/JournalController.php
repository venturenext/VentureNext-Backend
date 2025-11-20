<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\JournalPostResource;
use App\Models\Category;
use App\Models\JournalPost;

class JournalController extends Controller
{
    public function index()
    {
        $category = request('category', '');
        $perPage = (int) request('per_page', 9);

        $query = JournalPost::with('category')
            ->published()
            ->byCategory($category)
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        $paginator = $query->paginate($perPage);

        // Distinct list of categories for filter pills
        $categories = Category::whereHas('journalPosts', function ($q) {
                $q->published();
            })
            ->orderBy('name')
            ->get(['id', 'name', 'slug']);

        return response()->json([
            'success' => true,
            'data' => JournalPostResource::collection($paginator->items()),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
            ],
            'categories' => $categories,
            'current' => [ 'category' => (string) $category ]
        ]);
    }

    public function show(string $slug)
    {
        $post = JournalPost::with('category')->published()->where('slug', $slug)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new JournalPostResource($post)
        ]);
    }
}
