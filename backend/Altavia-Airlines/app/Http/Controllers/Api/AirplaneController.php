<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AirplaneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $airplanes = Airplane::all();

        return response()->json($airplanes, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
            'seats' => 'required | integer | min:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $airplane = Airplane::create([
            'name' => $validated['name'],
            'seats' => $validated['seats'],
        ]);
        $airplane->save();

        return response()->json($airplane, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $airplane = Airplane::findOrFail($id);

        return response()->json($airplane, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $airplane = Airplane::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required | string',
            'seats' => 'required | integer | min:1',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $airplane->update([
            'name' => $validated['name'],
            'seats' => $validated['seats'],
        ]);
        $airplane->save();

        return response()->json($airplane, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $airplane = Airplane::findOrFail($id);
        $airplane->delete();

        return response()->json([
            'message' => 'Airplane deleted',
            'airplane' => $airplane,
        ], 200);
    }
}
