<?php

namespace App\Http\Controllers;

use App\Models\PropertyInterest;
use App\Models\PropertyModel;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PropertyInterestController extends Controller
{
    /**
     * Record the authenticated investor's interest in a property.
     */
    public function store(Request $request, string $propertyId): RedirectResponse
    {
        $property = PropertyModel::findOrFail($propertyId);

        try {
            // firstOrCreate so a repeat submission never resets a status the admin team already advanced
            $interest = PropertyInterest::firstOrCreate(
                ['user_id' => $request->user()->id, 'property_id' => $property->id],
                ['status' => 'new']
            );
            $isNew = $interest->wasRecentlyCreated;
        } catch (UniqueConstraintViolationException) {
            $isNew = false;
        }

        return redirect()->route('investment.interest')->with(
            'success',
            $isNew
                ? 'Your investment interest has been submitted. Our team will connect with you soon.'
                : 'You have already expressed interest in this property. Our team will connect with you soon.'
        );
    }
}
