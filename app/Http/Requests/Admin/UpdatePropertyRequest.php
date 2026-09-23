<?php

namespace App\Http\Requests\Admin;

use App\Enums\PropertyAvailability;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availability' => ['required', Rule::enum(PropertyAvailability::class)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:20000'],
            'management_company' => ['required', 'string', 'max:255'],
            'property_images' => ['nullable', 'array', 'max:20'],
            'property_images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }
}
