<?php

namespace App\Http\Requests\User;

use App\Enums\GenderEnum;
use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'max:255', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'surname' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user, 'id')->whereNull('deleted_at')],
            'password' => ['required', 'confirmed', Password::min(6)->max(20)->numbers()],
            'phone_number' => ['required', 'regex:/^(\+994|994|0)(50|51|55|60|70|77)\d{7}$/', 'min:9', 'max:15', Rule::unique('users', 'phone_number')->ignore($this->user, 'id')->whereNull('deleted_at')],
            'status' => ['sometimes', new Enum(StatusEnum::class)],
            'trainer_id' => ['sometimes', Rule::exists('users', 'id')],
            'file' => ['required', 'mimes:jpg,jpeg,png', 'max:2048'],
            'birth_date' => ['required_unless:role,' . RoleEnum::ADMIN->value, 'date'],
            'gender' => ['required_unless:role,' . RoleEnum::ADMIN->value, new Enum(GenderEnum::class)],
            'role' => ['required', new Enum(RoleEnum::class)],
            'weight' => ['required_unless:role,' . RoleEnum::ADMIN->value, 'numeric'],
            'height' => ['required_unless:role,' . RoleEnum::ADMIN->value, 'numeric'],
            'about' => ['required_if:role,' . RoleEnum::TRAINER->value, 'string'],
            'ideal_weight' => ['required_if:role,' . RoleEnum::USER->value, 'numeric'],
            'target_weight' => ['required_if:role,' . RoleEnum::USER->value, 'numeric'],
        ];
    }
}
