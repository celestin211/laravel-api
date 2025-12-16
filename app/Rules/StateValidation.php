<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Shared validation rule for state values.
 * Ensures consistent state validation across the application.
 */
class StateValidation implements ValidationRule
{
    /**
     * Allowed state values.
     *
     * @var array<string>
     */
    private array $allowedStates;

    /**
     * @param  array<string>  $allowedStates
     */
    public function __construct(array $allowedStates)
    {
        $this->allowedStates = $allowedStates;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, string|null=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail('Le champ :attribute doit être une chaîne de caractères.', null);

            return;
        }

        if (! in_array($value, $this->allowedStates, true)) {
            $fail("Le champ :attribute doit être l'un des états suivants : ".implode(', ', $this->allowedStates).'.', null);
        }
    }
}
