<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PageContent;

class PageContentController extends Controller
{
    /**
     * Get content for a specific page (public endpoint)
     */
    public function show($pageName)
    {
        $contents = PageContent::getPageSections($pageName, true);

        return response()->json([
            'success' => true,
            'data' => $contents,
            'message' => 'Page content retrieved successfully',
        ]);
    }

    /**
     * Get specific section by key (public endpoint)
     */
    public function getSection($sectionKey)
    {
        $section = PageContent::where('section_key', $sectionKey)
            ->where('is_active', true)
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Section not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $section,
            'message' => 'Section retrieved successfully',
        ]);
    }
}
