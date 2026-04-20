<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_id' => ['required', 'exists:companies,id'],
            'title' => ['required', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'location_type' => ['required', 'string', 'in:remote,hybrid,on_site'],
            'location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'contract_type' => ['required', 'string', 'in:full_time,part_time,contract,freelance,internship'],
            'experience_level' => ['nullable', 'string', 'in:junior,mid,senior,lead'],
            'vue_version' => ['nullable', 'string', 'max:20'],
            'nuxt_version' => ['nullable', 'string', 'max:20'],
            'requires_typescript' => ['boolean'],
            'salary_min' => ['nullable', 'integer', 'min:0'],
            'salary_max' => ['nullable', 'integer', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'salary_interval' => ['required', 'string', 'in:yearly,monthly,weekly,daily,hourly'],
            'skills' => ['nullable', 'array'],
            'skills.*' => ['string', 'max:50'],
            'benefits' => ['nullable', 'array'],
            'benefits.*' => ['string', 'max:100'],
            'description' => ['required', 'string'],
            'apply_url' => ['nullable', 'url'],
            'source' => ['nullable', 'string', 'in:manual,scraped'],
            'source_url' => ['nullable', 'url'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'expires_at' => ['nullable', 'date'],
        ];
    }
}

