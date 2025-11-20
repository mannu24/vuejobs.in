<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'receiver_id' => ['required', 'exists:users,id'],
            'job_id' => ['nullable', 'exists:jobs,id'],
            'body' => ['required', 'string'],
            'attachments' => ['nullable', 'array'],
        ];
    }
}

