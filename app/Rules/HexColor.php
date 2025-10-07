<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\Rule;

class HexColor implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Checks if the value starts with '#' and is followed by exactly 6 hexadecimal characters (0-9, a-f, A-F).
        // The '#' is included in the validation pattern because the form uses an HTML color input which returns #RRGGBB.
        return preg_match('/^#[0-9a-fA-F]{6}$/', $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be a valid 6-digit hexadecimal color code (e.g., #FFFFFF).';
    }
}
