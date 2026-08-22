<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('user') instanceof \App\Models\User
            ? $this->route('user')->id
            : $this->route('user');

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'middile_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'suffix' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($userId)],
            'password' => ['nullable', 'string', 'min:8'],
            'phone' => ['nullable', 'string', 'max:30'],
            'alternate_phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'social_security_number' => ['nullable', 'string', 'max:50'],
            'hear_about_us' => ['nullable', 'integer', 'exists:hear_about_us,id'],
            'experiance_level' => ['nullable', 'integer', 'exists:experiance_level,id'],
            'investing_reason' => ['nullable', 'integer', 'exists:reason_for_investing,id'],
            'investment_sources' => ['nullable', 'integer', 'exists:investment_sources,id'],
            'investing_timeline' => ['nullable', 'integer', 'exists:investment_timeline,id'],
            'investment_goals' => ['nullable', 'integer', 'exists:investment_goals,id'],
            'investment_timelength' => ['nullable', 'integer', 'exists:investment_timelength,id'],
            'accreditation_status' => ['nullable', 'integer', 'exists:accreditation_status,id'],
            'users_net_worth' => ['nullable', 'integer', 'exists:users_net_worth,id'],
            'email_verified' => ['nullable', 'boolean'],
            'phone_verified' => ['nullable', 'boolean'],
            'app_connected' => ['nullable', 'boolean'],
        ];
    }
}
