<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PerkDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "short_description" => $this->short_description,
            "description" => $this->description,
            "partner_name" => $this->partner_name,
            "partner_logo" => $this->partner_logo ? asset("storage/" . $this->partner_logo) : null,
            "partner_url" => $this->partner_url,
            "redeem_type" => $this->redeem_type,
            "coupon_code" => $this->coupon_code,
            "external_url" => $this->external_url,
            "location" => $this->location,
            "valid_from" => $this->valid_from?->format("Y-m-d"),
            "valid_until" => $this->valid_until?->format("Y-m-d"),
            "is_featured" => $this->is_featured,
            "is_active" => $this->is_active,
            "status" => $this->status,
            "is_valid" => $this->is_valid,
            "is_expired" => $this->is_expired,
            "category" => $this->category ? [
                "id" => $this->category->id,
                "name" => $this->category->name,
                "slug" => $this->category->slug,
            ] : null,
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
            "meta" => $this->seo ? [
            "meta_title" => $this->seo->meta_title,
            "meta_description" => $this->seo->meta_description,
            "canonical_url" => $this->seo->canonical_url,
            "og_image" => $this->seo->og_image,
            "og_title" => $this->seo->og_title,
            "og_description" => $this->seo->og_description,
            "twitter_title" => $this->seo->twitter_title,
            "twitter_description" => $this->seo->twitter_description,
            "keywords" => $this->seo->keywords,
            ] : null,
            "published_at" => $this->published_at?->toIso8601String(),
            "created_at" => $this->created_at?->toIso8601String(),
            "updated_at" => $this->updated_at?->toIso8601String(),
        ];
    }
}
