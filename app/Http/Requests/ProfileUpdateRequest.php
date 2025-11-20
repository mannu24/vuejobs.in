<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'headline' => ['nullable', 'string', 'max:255'],
            'about' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'linkedin_url' => ['nullable', 'url'],
            'github_url' => ['nullable', 'url'],
            'website_url' => ['nullable', 'url'],
            'portfolio_url' => ['nullable', 'url'],
            'resume_url' => ['nullable', 'url'],
            'avatar_url' => ['nullable', 'url'],
            'skill_tags' => ['nullable', 'array'],
            'skill_tags.*' => ['string', 'max:50'],
            'is_available' => ['nullable', 'boolean'],
            'resume_visibility' => ['nullable', 'string', 'in:private,public,network'],
            'settings' => ['nullable', 'array'],
        ];
    }
}

