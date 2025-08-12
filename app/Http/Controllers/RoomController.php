<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $rooms = Room::orderBy('name')->get();
        
        return response()->json([
            'message' => 'Rooms retrieved successfully',
            'data' => $rooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name',
            'description' => 'nullable|string|max:1000',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $room = Room::create($validator->validated());

        return response()->json([
            'message' => 'Room created successfully',
            'data' => $room
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        $room->load('bookings.user');
        
        return response()->json([
            'message' => 'Room retrieved successfully',
            'data' => $room
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:rooms,name,' . $room->id,
            'description' => 'nullable|string|max:1000',
            'capacity' => 'required|integer|min:1|max:1000',
            'location' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $room->update($validator->validated());

        return response()->json([
            'message' => 'Room updated successfully',
            'data' => $room
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        // Check if room has active bookings
        $activeBookings = $room->bookings()->where('end_time', '>', now())->count();
        
        if ($activeBookings > 0) {
            return response()->json([
                'message' => 'Cannot delete room with active bookings',
                'error' => 'Room has active bookings'
            ], 409);
        }

        $room->delete();

        return response()->json([
            'message' => 'Room deleted successfully'
        ]);
    }
}
