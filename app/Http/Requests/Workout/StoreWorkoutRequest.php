<?php

namespace App\Http\Requests\Workout;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreWorkoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:20', Rule::unique('workouts', 'name')->whereNull('deleted_at')],
            'category_id' => ['required', Rule::exists('categories', 'id')->whereNull('deleted_at')],
            'file' => ['nullable', 'mimes:png,jpg,jpeg,txt', 'max:2048'],
        ];
    }
}
