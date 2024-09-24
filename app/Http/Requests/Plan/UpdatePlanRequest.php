<?php

namespace App\Http\Requests\Plan;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePlanRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:20',
                Rule::unique('plans', 'name')
                    ->ignore($this->plan, 'id')
                    ->whereNull('deleted_at')
            ],
            'workouts' => [
                'required',
                'array',
                Rule::exists('workouts', 'id')
                    ->whereNull('deleted_at')
            ],
            'description' => [
                'required',
                'string'
            ],
            'status' => [
                'required',
                new Enum(StatusEnum::class)
            ],
        ];
    }
}
