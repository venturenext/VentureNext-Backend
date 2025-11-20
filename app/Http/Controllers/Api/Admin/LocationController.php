<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) $request->input('per_page', 20);
        $search = $request->input('search');

        $query = Location::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $locations = $query->orderBy('name')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => LocationResource::collection($locations->items()),
            'meta' => [
                'current_page' => $locations->currentPage(),
                'per_page' => $locations->perPage(),
                'total' => $locations->total(),
                'last_page' => $locations->lastPage(),
            ],
        ]);
    }

    public function store(StoreLocationRequest $request)
    {
        $location = Location::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Location created successfully',
            'data' => new LocationResource($location),
        ], 201);
    }

    public function update(UpdateLocationRequest $request, $id)
    {
        $location = Location::findOrFail($id);
        $location->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => new LocationResource($location),
        ]);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);

        if ($location->perks()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete location with assigned perks',
            ], 400);
        }

        $location->delete();

        return response()->json([
            'success' => true,
            'message' => 'Location deleted successfully',
        ]);
    }
}
