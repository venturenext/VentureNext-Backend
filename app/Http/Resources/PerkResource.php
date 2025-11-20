<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class PerkResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "short_description" => $this->short_description,
            "partner_name" => $this->partner_name,
            "partner_logo" => $this->partner_logo ? asset("storage/" . $this->partner_logo) : null,
            "redeem_type" => $this->redeem_type,
            "location" => $this->location,
            "location_label" => $this->locationOption?->name,
            "valid_from" => $this->valid_from?->format("Y-m-d"),
            "valid_until" => $this->valid_until?->format("Y-m-d"),
            "is_featured" => $this->is_featured,
            "is_active" => $this->is_active,
            "status" => $this->status,
            "is_valid" => $this->is_valid,
            "is_expired" => $this->is_expired,
            "category" => [
                "id" => $this->category?->id,
                "name" => $this->category?->name,
                "slug" => $this->category?->slug,
            ],
            "subcategory" => $this->subcategory ? [
                "id" => $this->subcategory->id,
                "name" => $this->subcategory->name,
                "slug" => $this->subcategory->slug,
            ] : null,
            "statistics" => $this->statistics ? [
                "view_count" => $this->statistics->view_count,
                "claim_count" => $this->statistics->claim_count,
            ] : null,
            "media" => [
                "logo" => $this->partner_logo ? asset("storage/" . $this->partner_logo) : null,
                "banner" => $this->media->where('media_type', 'banner')->first()?->getFullUrl(),
                "gallery" => $this->media->where('media_type', 'gallery')->sortBy('display_order')->map(fn($m) => $m->getFullUrl())->values()->toArray(),
            ],
            "published_at" => $this->published_at?->toIso8601String(),
        ];
    }
}
