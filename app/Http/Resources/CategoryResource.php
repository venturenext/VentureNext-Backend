<?php
namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "slug" => $this->slug,
            "description" => $this->description,
            "icon" => $this->icon,
            "is_active" => $this->is_active,
            "perks_count" => $this->perks()->count(),
        ];
    }
}
