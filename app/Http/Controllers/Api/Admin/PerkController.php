<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePerkRequest;
use App\Http\Requests\UpdatePerkRequest;
use App\Http\Resources\PerkDetailResource;
use App\Http\Resources\PerkResource;
use App\Models\Perk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PerkController extends Controller
{
    public function index(Request $request)
    {
        $query = Perk::with(['category', 'subcategory', 'statistics', 'locationOption']);


        if ($request->has('status')) {
            $query->where('status', $request->status);
        }


        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }


        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ILIKE', "%{$search}%")
                  ->orWhere('partner_name', 'ILIKE', "%{$search}%");
            });
        }

        $perks = $query->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => PerkResource::collection($perks),
            'meta' => [
                'current_page' => $perks->currentPage(),
                'last_page' => $perks->lastPage(),
                'per_page' => $perks->perPage(),
                'total' => $perks->total(),
            ]
        ]);
    }

    public function store(StorePerkRequest $request)
    {
        $data = $request->validated();


        if ($request->hasFile('partner_logo')) {
            $data['partner_logo'] = $request->file('partner_logo')->store('perks/logos', 'public');
        }


        $perk = Perk::create($data);


        if ($request->hasFile('media_banner')) {
            $file = $request->file('media_banner');
            $bannerPath = $file->store('perks/banners', 'public');


            $perk->media()->create([
                'media_type' => 'banner',
                'file_path' => $bannerPath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }


        if ($request->hasFile('media_gallery')) {
            foreach ($request->file('media_gallery') as $index => $file) {
                $galleryPath = $file->store('perks/gallery', 'public');

                $perk->media()->create([
                    'media_type' => 'gallery',
                    'file_path' => $galleryPath,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'display_order' => $index,
                ]);
            }
        }


        if ($request->has('meta_title') || $request->has('meta_description') || $request->has('og_title')) {
            $seoData = [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'canonical_url' => $request->canonical_url,
                'og_title' => $request->og_title,
                'og_description' => $request->og_description,
                'twitter_title' => $request->twitter_title,
                'twitter_description' => $request->twitter_description,
                'keywords' => $request->keywords,
            ];


            if ($request->hasFile('og_image')) {
                $seoData['og_image'] = $request->file('og_image')->store('perks/og-images', 'public');
            }

            $perk->seo()->updateOrCreate(
                ['perk_id' => $perk->id],
                $seoData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Perk created successfully',
            'data' => new PerkDetailResource($perk->load(['category', 'subcategory', 'seo', 'statistics', 'media', 'locationOption']))
        ], 201);
    }

    public function show($id)
    {
        $perk = Perk::with(['category', 'subcategory', 'seo', 'statistics', 'media', 'leads'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => new PerkDetailResource($perk)
        ]);
    }

    public function update(UpdatePerkRequest $request, $id)
    {
        $perk = Perk::findOrFail($id);
        $data = $request->validated();


        if ($request->hasFile('partner_logo')) {
            if ($perk->partner_logo) {
                Storage::disk('public')->delete($perk->partner_logo);
            }
            $data['partner_logo'] = $request->file('partner_logo')->store('perks/logos', 'public');
        } else {
            // Jangan update partner_logo jika tidak ada file baru
            unset($data['partner_logo']);
        }

        $perk->update($data);


        if ($request->hasFile('media_banner')) {
            $file = $request->file('media_banner');
            $bannerPath = $file->store('perks/banners', 'public');

            $oldBanner = $perk->media()->where('media_type', 'banner')->first();
            if ($oldBanner) {
                Storage::disk('public')->delete($oldBanner->file_path);
                $oldBanner->delete();
            }

            $perk->media()->create([
                'media_type' => 'banner',
                'file_path' => $bannerPath,
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);
        }

        if ($request->hasFile('media_gallery')) {
            foreach ($request->file('media_gallery') as $index => $file) {
                $galleryPath = $file->store('perks/gallery', 'public');

                $perk->media()->create([
                    'media_type' => 'gallery',
                    'file_path' => $galleryPath,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'display_order' => $index,
                ]);
            }
        }

        if ($request->has('meta_title') || $request->has('meta_description') || $request->has('og_title')) {
            $seoData = [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'canonical_url' => $request->canonical_url,
                'og_title' => $request->og_title,
                'og_description' => $request->og_description,
                'twitter_title' => $request->twitter_title,
                'twitter_description' => $request->twitter_description,
                'keywords' => $request->keywords,
            ];

            if ($request->hasFile('og_image')) {
                // Hapus OG image lama jika ada
                $oldSeo = $perk->seo;
                if ($oldSeo && $oldSeo->og_image) {
                    Storage::disk('public')->delete($oldSeo->og_image);
                }
                $seoData['og_image'] = $request->file('og_image')->store('perks/og-images', 'public');
            }

            $perk->seo()->updateOrCreate(
                ['perk_id' => $perk->id],
                $seoData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Perk updated successfully',
            'data' => new PerkDetailResource($perk->load(['category', 'subcategory', 'seo', 'statistics', 'media', 'locationOption']))
        ]);
    }

    public function destroy($id)
    {
        $perk = Perk::findOrFail($id);

        if ($perk->partner_logo) {
            Storage::disk('public')->delete($perk->partner_logo);
        }

        $perk->delete();

        return response()->json([
            'success' => true,
            'message' => 'Perk deleted successfully'
        ]);
    }

    public function publish(Request $request, $id)
    {
        $perk = Perk::findOrFail($id);

        $perk->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Perk published successfully',
            'data' => new PerkDetailResource($perk)
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'perk_ids' => 'required|array',
            'perk_ids.*' => 'exists:perks,id',
            'action' => 'required|in:publish,unpublish,activate,deactivate,delete',
        ]);

        $perks = Perk::whereIn('id', $request->perk_ids);

        switch ($request->action) {
            case 'publish':
                $perks->update(['status' => 'published', 'published_at' => now()]);
                break;
            case 'unpublish':
                $perks->update(['status' => 'draft']);
                break;
            case 'activate':
                $perks->update(['is_active' => true]);
                break;
            case 'deactivate':
                $perks->update(['is_active' => false]);
                break;
            case 'delete':
                $perks->delete();
                break;
        }

        return response()->json([
            'success' => true,
            'message' => 'Bulk action completed successfully'
        ]);
    }
}
