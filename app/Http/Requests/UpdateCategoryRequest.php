<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        $id = $this->route('category') ?? $this->route('id');
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('categories')->ignore($id)],
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ];
    }
}
