<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'availability' => 'required',
            'name' => 'required',
            'description' => 'required',
            'management_company' => 'required',
            'property_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', //Assuming property_images are images
        ];
    }
}
