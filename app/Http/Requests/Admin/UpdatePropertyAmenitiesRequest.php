<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyAmenitiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    /**
     * The form posts one comma separated string; normalise it (and any array input)
     * into a trimmed, de-duplicated list before validation.
     */
    protected function prepareForValidation(): void
    {
        $raw = $this->input('property_amenities');

        if (is_string($raw)) {
            $raw = explode(',', $raw);
        }

        if (! is_array($raw)) {
            return;
        }

        $seen = [];
        $items = [];
        foreach ($raw as $item) {
            if (! is_string($item)) {
                $items[] = $item; // left untouched so `string` validation rejects it

                continue;
            }

            $item = trim($item);
            $key = mb_strtolower($item);
            if ($item === '' || isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $items[] = $item;
        }

        $this->merge(['property_amenities' => $items]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_amenities' => ['nullable', 'array', 'max:100'],
            'property_amenities.*' => ['string', 'max:255'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'property_amenities' => 'amenities',
            'property_amenities.*' => 'amenity',
        ];
    }
}
