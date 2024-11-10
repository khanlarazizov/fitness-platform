<?php

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class TrainerExists implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exists = User::query()->trainer()->where('id', $value)->exists();

        // If no such trainer exists, invoke the fail callback with a custom message.
        if (!$exists) {
            $fail('The selected ' . $attribute . ' is invalid or the user is not a trainer.');
        }
    }
}
