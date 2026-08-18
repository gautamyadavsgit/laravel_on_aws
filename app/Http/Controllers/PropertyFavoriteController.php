<?php

namespace App\Http\Controllers;

use App\Models\PropertyFavorite;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PropertyFavoriteController extends Controller
{
    /**
     * Toggle a property favorite for the authenticated user.
     */
    public function toggle($propertyId, Request $request): JsonResponse
    {
        $user = auth()->user();

        $favorite = PropertyFavorite::where('user_id', $user->id)
            ->where('property_id', $propertyId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'success' => true,
                'message' => 'Property removed from favorites',
                'isFavorited' => false,
            ]);
        } else {
            PropertyFavorite::create([
                'user_id' => $user->id,
                'property_id' => $propertyId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Property added to favorites',
                'isFavorited' => true,
            ]);
        }
    }
}
