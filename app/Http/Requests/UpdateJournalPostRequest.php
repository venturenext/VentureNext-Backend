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
            'title' => 'sometimes|required|string|max:255',
            'slug' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('journal_posts', 'slug')->ignore($postId)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:120',
            'tags' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'author_name' => 'nullable|string|max:255',
            'author_avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_published' => 'boolean',
            'published_at' => 'nullable|date',
        ];
    }
}
