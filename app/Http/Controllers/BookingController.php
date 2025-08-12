<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['schedule']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        
        if ($user->role === 'admin') {
            $bookings = Booking::with(['user', 'room'])
                ->orderBy('start_time', 'desc')
                ->paginate(15);
        } else {
            $bookings = $user->bookings()
                ->with('room')
                ->orderBy('start_time', 'desc')
                ->paginate(15);
        }
        
        return response()->json($bookings);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rooms = Room::orderBy('name')->get();
        
        return response()->json([
            'rooms' => $rooms
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = auth()->id();

        // Check for booking conflicts
        $conflicts = Booking::where('room_id', $validated['room_id'])
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    // New booking starts before existing ends AND new booking ends after existing starts
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($conflicts) {
            throw ValidationException::withMessages([
                'time_conflict' => 'The selected time slot conflicts with an existing booking. Please choose a different time.'
            ]);
        }

        Booking::create($validated);

        return response()->json([
            'message' => 'Booking created successfully.',
            'booking' => Booking::with(['user', 'room'])->find(Booking::latest()->first()->id)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        // Users can only view their own bookings unless they're admin
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        $booking->load(['user', 'room']);
        
        return response()->json($booking);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        // Users can only edit their own bookings unless they're admin
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        // Don't allow editing past bookings
        if ($booking->start_time < now()) {
            return response()->json(['error' => 'Cannot edit past bookings.'], 422);
        }

        $rooms = Room::orderBy('name')->get();
        
        return response()->json([
            'booking' => $booking,
            'rooms' => $rooms
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBookingRequest $request, Booking $booking)
    {
        // Users can only update their own bookings unless they're admin
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            abort(403, 'Access denied.');
        }

        // Don't allow editing past bookings
        if ($booking->start_time < now()) {
            return redirect()->route('bookings.index')
                ->with('error', 'Cannot edit past bookings.');
        }

        $validated = $request->validated();

        // Check for booking conflicts (excluding current booking)
        $conflicts = Booking::where('room_id', $validated['room_id'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($validated) {
                $query->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                      ->where('end_time', '>', $validated['start_time']);
                });
            })
            ->exists();

        if ($conflicts) {
            throw ValidationException::withMessages([
                'time_conflict' => 'The selected time slot conflicts with an existing booking. Please choose a different time.'
            ]);
        }

        $booking->update($validated);

        return response()->json([
            'message' => 'Booking updated successfully.',
            'booking' => $booking->load(['user', 'room'])
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        // Users can only delete their own bookings unless they're admin
        if (auth()->user()->role !== 'admin' && $booking->user_id !== auth()->id()) {
            return response()->json(['error' => 'Access denied.'], 403);
        }

        // Don't allow deleting past bookings
        if ($booking->start_time < now()) {
            return response()->json(['error' => 'Cannot delete past bookings.'], 422);
        }

        $booking->delete();

        return response()->json([
            'message' => 'Booking cancelled successfully.'
        ]);
    }

    /**
     * Display public schedule (no authentication required).
     */
    public function schedule()
    {
        $bookings = Booking::with(['user:id,name', 'room'])
            ->where('start_time', '>=', now())
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'title' => $booking->title,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'room' => $booking->room->name,
                    'user' => $booking->user->name,
                ];
            });

        return response()->json([
            'schedule' => $bookings
        ]);
    }

    /**
     * Check room availability for a specific time period.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'booking_id' => 'nullable|exists:bookings,id',
        ]);

        $query = Booking::where('room_id', $request->room_id)
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            });

        // Exclude current booking if updating
        if ($request->booking_id) {
            $query->where('id', '!=', $request->booking_id);
        }

        $conflicts = $query->exists();

        return response()->json([
            'available' => !$conflicts,
            'message' => $conflicts ? 'Time slot is not available' : 'Time slot is available'
        ]);
    }
}
