<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Airplane;
use App\Models\Flight;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flights = Flight::with('airplane', 'cities')->get();

        return response()->json($flights, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'price' => 'required | numeric | regex:"/^\d+(\.\d{1,2})?$/"',
            'airplane_id' => 'required | exists:airplanes,id',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $flight = Flight::create([
            'date' => $validated['date'],
            'price' => $validated['price'],
            'airplane_id' => $validated['airplane_id'],
        ]);
        $flight->save();

        return response()->json($flight, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flight = Flight::with('airplane', 'cities')->findOrFail($id);

        return response()->json($flight, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $flight = Flight::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'price' => 'required | numeric | regex:"/^\d+(\.\d{1,2})?$/"',
            'airplane_id' => 'required | exists:airplanes,id',
        ]);

        if($validator->fails()) {
            return response()->json([
                'message' => 'Introduced data is not correct',
                'errors' => $validator->errors(),
            ], 400);
        }

        $validated = $validator->validate();

        $flight->update([
            'date' => $validated['date'],
            'price' => $validated['price'],
            'airplane_id' => $validated['airplane_id'],
        ]);
        $flight->save();

        return response()->json($flight, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $flight = Flight::with('airplane', 'cities')->findOrFail($id);
        $flight->delete();

        return response()->json([
            'message' => 'Flight deleted',
            'flight' => $flight,
        ]);
    }

    public function bookFlight(string $id) {
        $user = JWTAuth::user();
        $flight = Flight::findOrFail($id);

        //hay que controlar si el vuelo no está completo

        if($user->flights()->where('flight_id', $id)->exists()) {
            return response()->json([
                'message' => 'You have already booked this flight',
            ], 400);
        }

        $user->flights()->attach($flight);

        return response()->json([
            'message' => 'Flight booked successfully',
        ], 200);
    }

    public function cancelFlight(string $id) {
        $user = JWTAuth::user();
        $flight = Flight::findOrFail($id);

        if(!$user->flights()->where('flight_id', $id)->exists()) {
            return response()->json([
                'message' => "You haven't booked this flight",
            ], 400);
        }

        $user->flights()->detach($flight);

        return response()->json([
            'message' => 'Flight canceled successfully',
        ], 200);
    }
}
