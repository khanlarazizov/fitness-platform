<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminProfileRequest extends FormRequest
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
            'email' => ['required', 'string', 'email', 'max:25',
                Rule::unique('users', 'email')
                    ->ignore($this->admin, 'id')
                    ->whereNull('deleted_at')],
        ];
    }
}
