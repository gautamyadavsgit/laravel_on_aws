<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Concerns\ThrottlesLoginAttempts;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminLoginRequest extends FormRequest
{
    use ThrottlesLoginAttempts;

    public function authorize(): bool
    {
        return ! Auth::guard('admin')->check();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ];
    }

    /**
     * Kept separate from the investor login limiter.
     */
    public function throttleKey(): string
    {
        return Str::transliterate('admin|'.Str::lower((string) $this->input('email')).'|'.$this->ip());
    }
}
