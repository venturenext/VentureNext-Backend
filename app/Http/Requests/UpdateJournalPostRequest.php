<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJournalPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('journal') ?? $this->route('id');
        return [
            'title' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:255', Rule::unique('journal_posts', 'slug')->ignore($postId)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'author_name' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_published' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ];
    }

    protected function prepareForValidation()
    {
        // Handle checkbox value - if not present in request, set to false (0)
        if (!$this->has('is_published')) {
            $this->merge(['is_published' => 0]);
        }

        // Handle empty category_id
        if ($this->has('category_id') && $this->input('category_id') === '') {
            $this->merge(['category_id' => null]);
        }
    }
}
