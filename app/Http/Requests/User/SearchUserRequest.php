<?php

namespace App\Http\Requests\User;

use App\Enums\DirectionEnum;
use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Rules\TrainerExists;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class SearchUserRequest extends FormRequest
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
            'surname' => ['nullable', 'string', 'max:25'],
            'gender' => ['nullable', new Enum(GenderEnum::class)],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'status' => ['nullable', new Enum(StatusEnum::class)],
            'trainer_id' => ['nullable', new TrainerExists()],
            'start_weight' => ['nullable', 'numeric'],
            'end_weight' => ['nullable', 'numeric'],
            'direction' => ['nullable', new Enum(DirectionEnum::class)],
            'sort_by' => ['nullable', 'in:name,surname,status,gender,weight,height,created_at'],
        ];
    }
}
