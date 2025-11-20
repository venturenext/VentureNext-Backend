<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePerkRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'title' => 'required|string|max:255|unique:perks,title',
            'slug' => 'nullable|string|max:255|unique:perks,slug',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'partner_name' => 'required|string|max:255',
            'partner_logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:2048',
            'redeem_type' => 'required|in:external_link,coupon_code,lead_form',
            'coupon_code' => 'required_if:redeem_type,coupon_code|nullable|string|max:100',
            'external_url' => 'required_if:redeem_type,external_link|nullable|url|max:500',
            'location' => 'required|exists:locations,slug',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after:valid_from',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'status' => 'required|in:draft,published',
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

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.unique' => 'A perk with this title already exists.',
            'coupon_code.required_if' => 'Coupon code is required when redeem type is coupon code.',
            'external_url.required_if' => 'External URL is required when redeem type is external link.',
            'valid_until.after' => 'Valid until date must be after valid from date.',
            'partner_logo.max' => 'Partner logo must not exceed 2MB.',
            'media_banner.max' => 'Banner image must not exceed 5MB.',
            'media_gallery.*.max' => 'Each gallery image must not exceed 5MB.',
        ];
    }
}
