<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminAddPropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (Auth::guard('admin')->check()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availability' => ['required', 'string', 'in:Available,Not Available'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'management_company' => ['required', 'string', 'max:255'],
            'property_images' => ['required', 'array', 'min:1', 'max:20'],
            'property_images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }
}
