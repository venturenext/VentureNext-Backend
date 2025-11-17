<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class PartnerInquiryRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'message' => 'required|string|max:2000',
            'company_website' => 'nullable|url|max:500',
        ];
    }
}
