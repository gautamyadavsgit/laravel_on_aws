<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyFavorite;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyFavoriteController extends Controller
{
    /**
     * Toggle a property favorite for the authenticated user.
     */
    public function toggle(string $propertyId, Request $request): JsonResponse
    {
        $user = $request->user();

        $property = Property::find($propertyId);
        if (! $property) {
            return response()->json([
                'success' => false,
                'message' => 'Property not found',
            ], 404);
        }

        $removed = PropertyFavorite::where('user_id', $user->id)
            ->where('property_id', $property->id)
            ->delete();

        if ($removed) {
            return response()->json([
                'success' => true,
                'message' => 'Property removed from favorites',
                'isFavorited' => false,
            ]);
        }

        try {
            PropertyFavorite::create([
                'user_id' => $user->id,
                'property_id' => $property->id,
            ]);
        } catch (UniqueConstraintViolationException) {
            // A concurrent request already added it; the end state is still "favorited"
        }

        return response()->json([
            'success' => true,
            'message' => 'Property added to favorites',
            'isFavorited' => true,
        ]);
    }
}
