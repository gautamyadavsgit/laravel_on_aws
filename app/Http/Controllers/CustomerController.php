<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('register');
    }
    public function investor()
    {
        $properties = \App\Models\PropertyModel::with(['propertyImage', 'propertyAddress', 'propertyAacf', 'propertyShare'])
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('frontend.properties', compact('properties'));
    }

    public function property_singlepage(Request $request)
    {
        $propertyId = $request->query('id');
        $query = \App\Models\PropertyModel::with([
            'propertyImage',
            'propertyFloorplan',
            'propertyDocumentModel',
            'propertyAddress',
            'propertyDetails',
            'propertyAacf',
            'propertyShare',
            'propertyAmenities'
        ]);

        $featuredProperty = $propertyId ? $query->find($propertyId) : $query->first();

        return view('frontend.property_singlepage', compact('featuredProperty'));
    }
    public function show($id)
    {
        // Logic to show a specific customer by ID
    }

    public function create()
    {
        // Logic to show a form for creating a new customer
    }

    public function store(Request $request)
    {
        // Logic to store a new customer in the database
    }

    public function edit($id)
    {
        // Logic to show a form for editing a specific customer
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific customer in the database
    }

    public function destroy($id)
    {
        // Logic to delete a specific customer from the database
    }
}
