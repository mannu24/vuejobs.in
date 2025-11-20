<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url'],
            'logo_url' => ['nullable', 'url'],
            'size' => ['nullable', 'string', 'max:100'],
            'industry' => ['nullable', 'string', 'max:100'],
            'about' => ['nullable', 'string'],
            'hiring_regions' => ['nullable', 'array'],
            'hiring_regions.*' => ['string', 'max:100'],
            'tech_stack' => ['nullable', 'array'],
            'tech_stack.*' => ['string', 'max:50'],
            'is_public' => ['nullable', 'boolean'],
        ];
    }
}

