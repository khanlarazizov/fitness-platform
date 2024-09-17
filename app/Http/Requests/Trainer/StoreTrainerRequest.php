<?php

namespace App\Http\Requests\Trainer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreTrainerRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:25'],
            'surname' => ['required', 'string', 'max:25'],
            'email' => ['required', 'string', 'email', 'max:25', Rule::unique('trainers', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string',
                Password::min(6)
                ->max(20)
                ->numbers()
                ->uncompromised(),
            ],
            'phone_number' => ['required', 'string', 'max:25'],
            'gender' => ['required', 'string', 'max:25'],
            'birth_date' => ['required', 'date', 'max:25'],
            'file' => ['nullable', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];
    }
}
