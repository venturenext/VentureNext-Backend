<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PerkClaimRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'perk_id' => 'required|exists:perks,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'phone' => 'nullable|numeric',
            'message' => 'nullable|string|max:1000',
            'utm_source' => 'nullable|string|max:100',
            'utm_medium' => 'nullable|string|max:100',
            'utm_campaign' => 'nullable|string|max:100',
        ];
    }
}
