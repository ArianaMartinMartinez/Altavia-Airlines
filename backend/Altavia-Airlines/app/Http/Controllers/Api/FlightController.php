<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class FlightController extends Controller
{
    public function indexFutureFlights()
    {
        $flights = Flight::with('airplane', 'departure', 'arrival')
            ->withCount('users')
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($flights, 200);
    }

    public function indexPastFlights() 
    {
        $flights = Flight::with('airplane', 'departure', 'arrival')
            ->withCount('users')
            ->where('date', '<', Carbon::today())
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($flights, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'price' => 'required | numeric | regex:"/^\d+(\.\d{1,2})?$/"',
            'airplane_id' => 'required | exists:airplanes,id',
            'departure_id' => 'required | exists:cities,id',
            'arrival_id' => 'required | exists:cities,id',
        ]);

        if ($request->departure_id == $request->arrival_id) {
            return response()->json([
                'message' => 'Departure and arrival cities must be different',
            ], 400);
        }

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
            'departure_id' => $validated['departure_id'],
            'arrival_id' => $validated['arrival_id'],
        ]);
        $flight->save();

        return response()->json($flight, 201);
    }

    public function show(string $id)
    {
        $flight = Flight::with('airplane', 'departure', 'arrival', 'users')->withCount('users')->findOrFail($id);

        return response()->json($flight, 200);
    }

    public function update(Request $request, string $id)
    {
        $flight = Flight::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'date' => 'required | date',
            'price' => 'required | numeric | regex:"/^\d+(\.\d{1,2})?$/"',
            'airplane_id' => 'required | exists:airplanes,id',
            'departure_id' => 'required | exists:cities,id',
            'arrival_id' => 'required | exists:cities,id',
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
            'departure_id' => $validated['departure_id'],
            'arrival_id' => $validated['arrival_id'],
        ]);
        $flight->save();

        return response()->json($flight, 200);
    }

    public function destroy(string $id)
    {
        $flight = Flight::with('airplane', 'departure', 'arrival')->findOrFail($id);
        $flight->delete();

        return response()->json([
            'message' => 'Flight deleted',
            'flight' => $flight,
        ]);
    }

    public function indexBookings() {
        $user = JWTAuth::user();

        $bookings = $user->flights()->with('airplane', 'departure', 'arrival')->orderBy('date', 'asc')->get();

        $bookings->transform(function ($flight) {
            $flight->date = Carbon::parse($flight->date)->toDateString();
            return $flight;
        });

        $futureBookings = $bookings->where('date', '>=', Carbon::today())->values();
        $pastBookings = $bookings->where('date', '<', Carbon::today())->sortByDesc('date')->values();
        $nextBooking = $futureBookings->first();

        return response()->json([
            'next_booking' => $nextBooking,
            'future_bookings' => $futureBookings->slice(1)->values(),
            'past_bookings' => $pastBookings,
        ], 200);
    }

    public function bookFlight(string $id) {
        $user = JWTAuth::user();
        $flight = Flight::withCount('users')->findOrFail($id);

        $flightDate = Carbon::parse($flight->date);
        if($flightDate->lt(Carbon::today())) {
            return response()->json([
                'message' => "You can't book a past flight",
            ], 400);
        }

        $airplaneSeats = $flight->airplane->seats;
        $bookedSeats = $flight->users_count;

        if($bookedSeats >= $airplaneSeats) {
            return response()->json([
                'message' => 'No seats available for this flight',
            ], 400);
        }

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

        $flightDate = Carbon::parse($flight->date);
        if($flightDate->lt(Carbon::today())) {
            return response()->json([
                'message' => "You can't cancel a past flight",
            ], 400);
        }

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

    public function filterFutureFlights(Request $request) {
        $query = Flight::query();

        if($request->has('departure_id')) {
            $query->where('departure_id', '=', $request->input('departure_id'));
        }

        if($request->has('arrival_id')) {
            $query->where('arrival_id', '=', $request->input('arrival_id'));
        }

        if($request->has('date')) {
            $query->where('date', '=', $request->input('date'));
        }

        $flights = $query->with('airplane', 'departure', 'arrival')
            ->withCount('users')
            ->where('date', '>=', Carbon::today())
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($flights, 200);
    }

    public function filterPastFlights(Request $request) {
        $query = Flight::query();

        if($request->has('departure_id')) {
            $query->where('departure_id', '=', $request->input('departure_id'));
        }

        if($request->has('arrival_id')) {
            $query->where('arrival_id', '=', $request->input('arrival_id'));
        }

        if($request->has('date')) {
            $query->where('date', '=', $request->input('date'));
        }

        $flights = $query->with('airplane', 'departure', 'arrival')
            ->withCount('users')
            ->where('date', '<', Carbon::today())
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($flights, 200);
    }
}
