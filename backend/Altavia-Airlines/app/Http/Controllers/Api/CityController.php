<?php

namespace App\Http\Controllers\Api;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::all();

        return response()->json($cities, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $city = City::create([
            'name' => $validated['name'],
        ]);
        $city->save();

        return response()->json($city, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $city= City::findOrFail($id);

        return response()->json($city, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $city = City::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $city->update([
            'name' => $validated['name'],
        ]);
        $city->save();

        return response()->json($city, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $city = City::findOrFail($id);
        $city->delete();

        return response()->json([
            'message' => 'City deleted',
            'city' => $city,
        ], 200);
    }
}
