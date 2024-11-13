<?php

namespace App\Http\Requests\Category;

use App\Enums\DirectionEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchCategoryRequest extends FormRequest
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
            'sort_by' => ['nullable', 'string', 'max:25'],
            'direction' => ['nullable', new Enum(DirectionEnum::class)],
        ];
    }
}
