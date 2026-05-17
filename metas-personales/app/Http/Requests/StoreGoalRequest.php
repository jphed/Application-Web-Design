<?php

namespace App\Http\Requests;

use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['required', 'string', Rule::in(Goal::categories())],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['active', 'paused', 'done'])],
            'progress' => ['nullable', 'integer', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'category.required' => 'Selecciona una categoría.',
            'category.in' => 'La categoría no es válida.',
        ];
    }
}
