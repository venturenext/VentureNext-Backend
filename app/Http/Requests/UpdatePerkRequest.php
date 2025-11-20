<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePerkRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $perkId = $this->route('perk') ?? $this->route('id');
        return [
            'category_id' => 'sometimes|required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'title' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('perks', 'title')->ignore($perkId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('perks', 'slug')->ignore($perkId)],
            'description' => 'sometimes|required|string',
            'short_description' => 'nullable|string|max:500',
            'partner_name' => 'sometimes|required|string|max:255',
            'partner_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'redeem_type' => 'sometimes|required|in:external_link,coupon_code,lead_form',
            'coupon_code' => 'required_if:redeem_type,coupon_code|nullable|string|max:100',
            'external_url' => 'required_if:redeem_type,external_link|nullable|url|max:500',
            'location' => 'sometimes|required|exists:locations,slug',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'sometimes|required|in:draft,published',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'canonical_url' => 'nullable|url|max:500',
            'og_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string',
            'twitter_title' => 'nullable|string|max:255',
            'twitter_description' => 'nullable|string',
            'keywords' => 'nullable|string',
            'media_banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'media_gallery' => 'nullable|array',
            'media_gallery.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }
}
