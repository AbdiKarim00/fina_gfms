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
     * - Numeric only (no letters)
     * - 6-8 digits
     * - Examples: 123456, 0258743, 87451210, 054321
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if it's numeric
        if (!is_numeric($value)) {
            $fail('The :attribute must contain only numbers.');
            return;
        }

        // Check length (6-8 digits)
        $length = strlen($value);
        if ($length < 6 || $length > 8) {
            $fail('The :attribute must be between 6 and 8 digits.');
            return;
        }

        // Ensure no leading/trailing spaces
        if ($value !== trim($value)) {
            $fail('The :attribute must not contain spaces.');
            return;
        }
    }
}
