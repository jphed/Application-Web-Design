<?php

namespace App\Http\Requests;

use App\Models\Goal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateGoalRequest extends FormRequest
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
            'progress' => ['required', 'integer', 'min:0', 'max:100'],
        ];
    }
}
