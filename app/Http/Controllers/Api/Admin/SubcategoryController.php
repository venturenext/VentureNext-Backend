<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search', '');

        $query = Subcategory::with('category')->withCount('perks');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $subcategories = $query->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => SubcategoryResource::collection($subcategories->items()),
            'meta' => [
                'current_page' => $subcategories->currentPage(),
                'per_page' => $subcategories->perPage(),
                'total' => $subcategories->total(),
                'last_page' => $subcategories->lastPage(),
                'from' => $subcategories->firstItem(),
                'to' => $subcategories->lastItem()
            ]
        ]);
    }

    public function store(StoreSubcategoryRequest $request)
    {
        $subcategory = Subcategory::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Subcategory created successfully',
            'data' => new SubcategoryResource($subcategory->load('category'))
        ], 201);
    }

    public function show($id)
    {
        $subcategory = Subcategory::with('category')
            ->withCount('perks')
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new SubcategoryResource($subcategory)
        ]);
    }

    public function update(UpdateSubcategoryRequest $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);
        $subcategory->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Subcategory updated successfully',
            'data' => new SubcategoryResource($subcategory->load('category'))
        ]);
    }

    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);

        // Check if subcategory has perks
        if ($subcategory->perks()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete subcategory with existing perks'
            ], 400);
        }

        $subcategory->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subcategory deleted successfully'
        ]);
    }
}
