<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobAlertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'filters' => ['required', 'array'],
            'frequency' => ['nullable', 'string', 'in:daily,weekly,monthly'],
        ];
    }
}

