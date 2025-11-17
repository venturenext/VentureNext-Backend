<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function index()
    {
        $pages = StaticPage::orderBy('title')->get();

        return response()->json([
            'success' => true,
            'data' => StaticPageResource::collection($pages)
        ]);
    }

    public function store(StoreStaticPageRequest $request)
    {
        $page = StaticPage::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Page created successfully',
            'data' => new StaticPageResource($page)
        ], 201);
    }

    public function show($id)
    {
        $page = StaticPage::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new StaticPageResource($page)
        ]);
    }

    public function update(UpdateStaticPageRequest $request, $id)
    {
        $page = StaticPage::findOrFail($id);
        $page->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Page updated successfully',
            'data' => new StaticPageResource($page)
        ]);
    }

    public function destroy($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully'
        ]);
    }
}
