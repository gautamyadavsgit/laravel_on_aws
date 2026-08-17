<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyFloorPlanRequest extends FormRequest
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
            'floor_plan_1' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'floor_plan_2' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'floor_plan_3' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }
}
