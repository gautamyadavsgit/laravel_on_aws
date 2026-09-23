<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Letters (incl. accents), combining marks, spaces, apostrophes, periods and hyphens.
     */
    protected const NAME_PATTERN = "/^[\pL\pM][\pL\pM\s.'’\-]*$/u";

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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255', 'regex:'.self::NAME_PATTERN],
            'middle_name' => ['nullable', 'string', 'max:255', 'regex:'.self::NAME_PATTERN],
            'last_name' => ['required', 'string', 'max:255', 'regex:'.self::NAME_PATTERN],
            'suffix' => ['nullable', 'string', 'max:50', "regex:/^[\pL\pM\d\s.,'’\-]+$/u"],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', Password::defaults(), 'max:255'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9()\-.\s]{7,30}$/'],
            'referral' => ['nullable', 'string', 'in:search,referral,social,podcast'],
            'experience' => ['nullable', 'string', 'in:beginner,intermediate,advanced,accredited'],
            'goals' => ['nullable', 'array', 'max:4'],
            'goals.*' => ['string', 'distinct', 'in:cash_flow,appreciation,tax_benefits,diversification'],
            'termsAgree' => ['accepted'],
        ];
    }

    /**
     * Friendlier field names for validation messages.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'termsAgree' => 'terms and conditions',
            'phone' => 'phone number',
            'goals.*' => 'investment goal',
        ];
    }
}
