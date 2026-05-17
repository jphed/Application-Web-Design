<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMilestoneRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'due_date' => ['nullable', 'date'],
            'order' => ['nullable', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
            'completed' => ['sometimes', 'boolean'],
        ];
    }
}
