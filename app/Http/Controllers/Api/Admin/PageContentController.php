<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PageContentController extends Controller
{
    /**
     * Get all page contents grouped by page
     */
    public function index()
    {
        $contents = PageContent::ordered()->get();

        $groupedByPage = $contents->groupBy('page_name');

        return response()->json([
            'success' => true,
            'data' => $groupedByPage,
            'message' => 'Page contents retrieved successfully',
        ]);
    }

    /**
     * Get content for a specific page
     */
    public function show($pageName)
    {
        $contents = PageContent::getPageSections($pageName, false);

        return response()->json([
            'success' => true,
            'data' => $contents,
            'message' => 'Page content retrieved successfully',
        ]);
    }

    /**
     * Get available pages structure
     */
    public function getPages()
    {
        $pages = [
            [
                'name' => 'homepage',
                'label' => 'Homepage',
                'sections' => [
                    ['key' => 'homepage_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                    ['key' => 'homepage_how_it_works', 'type' => 'cards', 'label' => 'How It Works'],
                    ['key' => 'homepage_latest_perks', 'type' => 'list_settings', 'label' => 'Latest Perks'],
                    ['key' => 'homepage_journal', 'type' => 'list_settings', 'label' => 'From The Journal'],
                ],
            ],
            [
                'name' => 'perks',
                'label' => 'Perks Page',
                'sections' => [
                    ['key' => 'perks_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                ],
            ],
            [
                'name' => 'journal',
                'label' => 'Journal Page',
                'sections' => [
                    ['key' => 'journal_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                ],
            ],
            [
                'name' => 'partner',
                'label' => 'Partner Page',
                'sections' => [
                    ['key' => 'partner_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                    ['key' => 'partner_why_partner', 'type' => 'cards', 'label' => 'Why Partner With Us'],
                    ['key' => 'partner_how_it_works', 'type' => 'cards', 'label' => 'How It Works'],
                    ['key' => 'partner_faq', 'type' => 'faq', 'label' => 'FAQ'],
                ],
            ],
            [
                'name' => 'about',
                'label' => 'About Page',
                'sections' => [
                    ['key' => 'about_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                    ['key' => 'about_what_we_do', 'type' => 'cards', 'label' => 'What We Do'],
                    ['key' => 'about_who_we_serve', 'type' => 'cards', 'label' => 'Who We Serve'],
                    ['key' => 'about_faq', 'type' => 'faq', 'label' => 'FAQ'],
                ],
            ],
            [
                'name' => 'contact',
                'label' => 'Contact Page',
                'sections' => [
                    ['key' => 'contact_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                ],
            ],
            [
                'name' => 'terms',
                'label' => 'Terms of Service',
                'sections' => [
                    ['key' => 'terms_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                    ['key' => 'terms_content', 'type' => 'content', 'label' => 'Terms Content'],
                ],
            ],
            [
                'name' => 'privacy',
                'label' => 'Privacy Policy',
                'sections' => [
                    ['key' => 'privacy_hero', 'type' => 'hero', 'label' => 'Hero Section'],
                    ['key' => 'privacy_content', 'type' => 'content', 'label' => 'Privacy Content'],
                ],
            ],
            [
                'name' => 'topbar',
                'label' => 'Top Bar',
                'sections' => [
                    ['key' => 'topbar_logo', 'type' => 'logo_title', 'label' => 'Logo & Title'],
                ],
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $pages,
            'message' => 'Pages structure retrieved successfully',
        ]);
    }

    /**
     * Update or create a single page content section
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string',
            'section_type' => 'required|string',
            'section_key' => 'required|string',
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
            'content' => 'nullable',
            'image_url' => 'nullable|string',
            'display_order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $request->only([
            'page_name',
            'section_type',
            'section_key',
            'title',
            'subtitle',
            'content',
            'image_url',
            'display_order',
            'is_active',
        ]);

        $pageContent = PageContent::updateOrCreateSection($data);

        return response()->json([
            'success' => true,
            'data' => $pageContent,
            'message' => 'Page content updated successfully',
        ]);
    }

    /**
     * Bulk update multiple sections
     */
    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*.page_name' => 'required|string',
            'sections.*.section_type' => 'required|string',
            'sections.*.section_key' => 'required|string',
            'sections.*.title' => 'nullable|string',
            'sections.*.subtitle' => 'nullable|string',
            'sections.*.content' => 'nullable',
            'sections.*.image_url' => 'nullable|string',
            'sections.*.display_order' => 'nullable|integer',
            'sections.*.is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $sections = $request->input('sections');
        $updated = [];

        foreach ($sections as $sectionData) {
            $pageContent = PageContent::updateOrCreateSection($sectionData);
            $updated[] = $pageContent;
        }

        return response()->json([
            'success' => true,
            'data' => $updated,
            'message' => 'Page contents updated successfully',
        ]);
    }

    /**
     * Upload image for page content
     */
    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'section_key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $image = $request->file('image');
        $sectionKey = $request->input('section_key');

        // Generate unique filename
        $filename = time() . '_' . $sectionKey . '.' . $image->getClientOriginalExtension();

        // Store in public/page-contents directory
        $path = $image->storeAs('page-contents', $filename, 'public');

        $imageUrl = Storage::url($path);

        return response()->json([
            'success' => true,
            'data' => [
                'url' => $imageUrl,
                'path' => $path,
            ],
            'message' => 'Image uploaded successfully',
        ]);
    }

    /**
     * Delete a page content section
     */
    public function destroy($id)
    {
        // Only super admin can delete
        if (auth()->user()->role !== 'super_admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only super admins can delete content.',
            ], 403);
        }

        $pageContent = PageContent::find($id);

        if (!$pageContent) {
            return response()->json([
                'success' => false,
                'message' => 'Page content not found',
            ], 404);
        }

        // Delete associated image if exists
        if ($pageContent->image_url) {
            $path = str_replace('/storage/', '', $pageContent->image_url);
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        $pageContent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page content deleted successfully',
        ]);
    }
}
