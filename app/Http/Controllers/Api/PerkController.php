<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PerkDetailResource;
use App\Http\Resources\PerkResource;
use App\Models\Perk;
use Illuminate\Http\Request;

class PerkController extends Controller
{
    /**
     * Public list of perks with filters/sorting.
     */
    public function index(Request $request)
    {
        $query = Perk::with(['category', 'subcategory', 'statistics'])
            ->active()
            ->published();

        // Filters
        $query->byCategory($request->input('category'))
              ->bySubcategory($request->input('subcategory'))
              ->byLocation($request->input('location'))
              ->search($request->input('search'));

        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'popular') {
            $query->popular();
        } elseif ($sort === 'ending_soon') {
            $query->whereNotNull('valid_until')->orderBy('valid_until', 'asc');
        } else {
            $query->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');
        }

        $perPage = (int) $request->input('per_page', 12);
        $perks = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => PerkResource::collection($perks),
            'meta' => [
                'current_page' => $perks->currentPage(),
                'last_page' => $perks->lastPage(),
                'per_page' => $perks->perPage(),
                'total' => $perks->total(),
            ],
        ]);
    }

    /**
     * Public perk detail by slug.
     */
    public function show(string $slug)
    {
        $perk = Perk::with(['category', 'subcategory', 'seo', 'statistics', 'media'])
            ->active()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => new PerkDetailResource($perk),
        ]);
    }

    /**
     * Increment view count by slug (non-auth, non-blocking).
     */
    public function incrementView(string $slug)
    {
        $perk = Perk::where('slug', $slug)->firstOrFail();
        $perk->incrementViewCount();

        return response()->json([
            'success' => true,
            'message' => 'View count incremented',
        ]);
    }
}

