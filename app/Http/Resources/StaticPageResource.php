<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class StaticPageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "slug" => $this->slug,
            "content" => $this->content,
            "excerpt" => $this->excerpt,
            "meta_title" => $this->meta_title,
            "meta_description" => $this->meta_description,
            "og_image" => $this->og_image ? asset('storage/' . $this->og_image) : null,
            "is_active" => $this->is_active,
            "reading_time" => method_exists($this->resource, 'getReadingTime') ? $this->getReadingTime() : null,
            "created_at" => $this->created_at?->toIso8601String(),
        ];
    }
}
