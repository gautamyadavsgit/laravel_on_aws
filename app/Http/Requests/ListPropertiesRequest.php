<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Listing filters are optional and untrusted: anything malformed (arrays, non-numeric numbers,
 * unknown sort keys) is dropped rather than rejected, so the public listing never errors.
 */
class ListPropertiesRequest extends FormRequest
{
    private const TEXT_FILTERS = ['q' => 100, 'location' => 100, 'property_type' => 100];

    private const NUMERIC_FILTERS = ['min_price' => 100_000_000_000, 'max_price' => 100_000_000_000, 'bedrooms' => 100, 'bathrooms' => 100];

    private const SORT_OPTIONS = ['latest', 'oldest', 'price_asc', 'price_desc'];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    /**
     * @return array<string, string>
     */
    public function filters(): array
    {
        $filters = [];

        foreach (self::TEXT_FILTERS as $key => $maxLength) {
            $value = $this->query($key);
            if (is_string($value) && ($value = trim($value)) !== '') {
                $filters[$key] = mb_substr($value, 0, $maxLength);
            }
        }

        foreach (self::NUMERIC_FILTERS as $key => $max) {
            $value = $this->query($key);
            if (is_string($value) && is_numeric($value) && (float) $value > 0) {
                $filters[$key] = (string) (int) min((float) $value, $max);
            }
        }

        $sortBy = $this->query('sort_by');
        if (is_string($sortBy) && in_array($sortBy, self::SORT_OPTIONS, true)) {
            $filters['sort_by'] = $sortBy;
        }

        return $filters;
    }

    /**
     * A page number is only kept when it is a plain positive integer.
     */
    public function page(): ?string
    {
        $page = $this->query('page');

        return is_string($page) && ctype_digit($page) && (int) $page >= 1 && strlen($page) <= 6 ? $page : null;
    }
}
