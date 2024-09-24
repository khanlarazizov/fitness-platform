<?php

namespace App\Http\Requests\Auth;

use App\Enums\StatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;

class RegisteredUserRequest extends FormRequest
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
    public function rules(): array//: todo check phone number and email
    {
        return [
            'name' => [
                'required',
                'min:3',
                'max:20',
                'regex:/(^([a-zA-Z]+)(\d+)?$)/u'
            ],
            'surname' => [
                'required',
                'min:3',
                'max:20'],
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->whereNull('deleted_at')
            ],//todo 'email:rfc,dns'
            'phone_number' => [
                'required',
                'regex:/^(\+994|994|0)(50|51|55|60|70|77)\d{7}$/',
                'min:9',
                'max:15',
                Rule::unique('users', 'phone_number')
                    ->whereNull('deleted_at')],
            'password' => [
                'required',
                'confirmed',
                Password::min(6)
                    ->max(20)
                    ->numbers()
            ],
            'status' => ['required', new Enum(StatusEnum::class)],
            'trainer_id' => ['sometimes', Rule::exists('users', 'id')],
            'weight' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
            'file' => ['nullable', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

//    public function attributes(): array
//    {
//        return [
//            'trainer_id' => 'Trainer',
//        ];
//    }
}
