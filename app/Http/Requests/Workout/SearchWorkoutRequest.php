<?php

namespace App\Http\Requests\Workout;

use App\Enums\DirectionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchWorkoutRequest extends FormRequest
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
            'name' => ['nullable', 'string', 'max:25'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'sort_by' => ['nullable', 'in:name,created_at,updated_at'],
            'direction' => ['nullable', new Enum(DirectionEnum::class)],
        ];
    }
}
