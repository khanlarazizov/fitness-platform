<?php

namespace App\Http\Requests\User;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class UserProfileRequest extends FormRequest
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
        $user = auth()->user();
        return [
            'name' => ['required', 'min:3', 'max:255', 'regex:/(^([a-zA-Z]+)(\d+)?$)/u'],
            'surname' => ['required', 'min:3', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')
                ->ignore($user, 'id')
                ->whereNull('deleted_at')
            ],
            'phone_number' => [
                'required',
                'regex:/^(\+994|994|0)(50|51|55|60|70|77)\d{7}$/',
                'min:9',
                'max:15',
                Rule::unique('users', 'phone_number')
                    ->ignore($user, 'id')
                    ->whereNull('deleted_at')],
            'status' => ['required', new Enum(StatusEnum::class)],
            'trainer_id' => ['sometimes', 'exists:users,id'],
            'file' => ['nullable', 'mimes:jpg,jpeg,png,txt', 'max:2048'],
            'weight' => ['required_unless:role,' . RoleEnum::ADMIN->value, 'numeric'],
            'height' => ['required_unless:role,' . RoleEnum::ADMIN->value, 'numeric'],
            'about' => ['required_if:role,' . RoleEnum::TRAINER->value, 'string'],
        ];
    }
}
