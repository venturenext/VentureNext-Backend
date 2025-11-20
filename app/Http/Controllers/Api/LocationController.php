<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Models\Location;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => LocationResource::collection($locations),
        ]);
    }
}
