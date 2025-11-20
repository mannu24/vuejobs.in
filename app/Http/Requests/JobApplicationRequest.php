<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplicationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cover_letter' => ['nullable', 'string'],
            'resume_url' => ['nullable', 'url'],
        ];
    }
}

