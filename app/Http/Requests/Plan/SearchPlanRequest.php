<?php

namespace App\Http\Requests\Plan;

use App\Enums\DirectionEnum;
use App\Enums\StatusEnum;
use App\Rules\TrainerExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchPlanRequest extends FormRequest
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
            'status' => ['nullable', new Enum(StatusEnum::class)],
            'trainer_id' => ['nullable', new TrainerExists()],
            'sort_by' => ['nullable', 'string', 'max:25'],
            'direction' => ['nullable', new Enum(DirectionEnum::class)],
        ];
    }
}
