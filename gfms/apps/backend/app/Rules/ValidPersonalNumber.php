<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidPersonalNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * UPN Format:
     * - Alphanumeric (letters and numbers)
     * - 3-10 characters
     * - Examples: CS001, ADM001, 123456, 0258743
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if it's alphanumeric
        if (! preg_match('/^[a-zA-Z0-9]+$/', $value)) {
            $fail('The :attribute must contain only letters and numbers.');

            return;
        }

        // Check length (3-10 characters)
        $length = strlen($value);
        if ($length < 3 || $length > 10) {
            $fail('The :attribute must be between 3 and 10 characters.');

            return;
        }

        // Ensure no leading/trailing spaces
        if ($value !== trim($value)) {
            $fail('The :attribute must not contain spaces.');

            return;
        }
    }
}
