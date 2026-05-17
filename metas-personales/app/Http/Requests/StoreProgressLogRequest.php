<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgressLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'note' => ['required', 'string'],
            'progress_value' => ['required', 'integer', 'min:0', 'max:100'],
            'logged_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'note.required' => 'Escribe una reflexión o avance.',
            'progress_value.required' => 'Indica el porcentaje de progreso.',
        ];
    }
}
