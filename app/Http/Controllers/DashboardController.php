<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the dashboard with available rooms and booking schedules.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get all rooms with their current availability status
        $rooms = Room::withCount(['bookings as active_bookings_count' => function ($query) {
            $query->where('start_time', '<=', now())
                  ->where('end_time', '>=', now());
        }])->orderBy('name')->get();

        // Get upcoming bookings for the next 7 days
        $upcomingBookings = Booking::with(['user', 'room'])
            ->where('start_time', '>=', now())
            ->where('start_time', '<=', now()->addDays(7))
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        // Get user's upcoming bookings if not admin
        $userBookings = collect();
        if ($user->role !== 'admin') {
            $userBookings = $user->bookings()
                ->with('room')
                ->where('start_time', '>=', now())
                ->orderBy('start_time')
                ->limit(5)
                ->get();
        }

        // Get today's schedule
        $todayBookings = Booking::with(['user', 'room'])
            ->whereDate('start_time', today())
            ->orderBy('start_time')
            ->get();

        // Statistics
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::whereDoesntHave('bookings', function ($query) {
                $query->where('start_time', '<=', now())
                      ->where('end_time', '>=', now());
            })->count(),
            'total_bookings_today' => Booking::whereDate('start_time', today())->count(),
            'user_total_bookings' => $user->role === 'admin' ? Booking::count() : $user->bookings()->count(),
        ];

        return view('dashboard', compact(
            'rooms',
            'upcomingBookings',
            'userBookings',
            'todayBookings',
            'stats'
        ));
    }

    /**
     * Get room availability for a specific date.
     */
    public function roomAvailability(Request $request): View
    {
        $request->validate([
            'date' => 'nullable|date|after_or_equal:today',
        ]);

        $date = $request->date ? Carbon::parse($request->date) : today();
        
        $rooms = Room::with(['bookings' => function ($query) use ($date) {
            $query->whereDate('start_time', $date)
                  ->orderBy('start_time');
        }])->orderBy('name')->get();

        return view('dashboard.room-availability', compact('rooms', 'date'));
    }

    /**
     * Get booking schedule for a specific room.
     */
    public function roomSchedule(Room $room, Request $request): View
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date ? Carbon::parse($request->start_date) : today();
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : today()->addDays(7);

        $bookings = $room->bookings()
            ->with('user')
            ->where('start_time', '>=', $startDate->startOfDay())
            ->where('start_time', '<=', $endDate->endOfDay())
            ->orderBy('start_time')
            ->get();

        // Group bookings by date
        $bookingsByDate = $bookings->groupBy(function ($booking) {
            return $booking->start_time->format('Y-m-d');
        });

        return view('dashboard.room-schedule', compact(
            'room',
            'bookings',
            'bookingsByDate',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Get available time slots for a room on a specific date.
     */
    public function availableTimeSlots(Room $room, Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'duration' => 'nullable|integer|min:30|max:480', // 30 minutes to 8 hours
        ]);

        $date = Carbon::parse($request->date);
        $duration = $request->duration ?? 60; // Default 1 hour

        // Get existing bookings for the date
        $existingBookings = $room->bookings()
            ->whereDate('start_time', $date)
            ->orderBy('start_time')
            ->get(['start_time', 'end_time']);

        // Generate available time slots (9 AM to 6 PM)
        $workStart = $date->copy()->setTime(9, 0);
        $workEnd = $date->copy()->setTime(18, 0);
        $availableSlots = [];

        $currentTime = $workStart->copy();
        
        while ($currentTime->addMinutes($duration)->lte($workEnd)) {
            $slotStart = $currentTime->copy()->subMinutes($duration);
            $slotEnd = $currentTime->copy();
            
            // Check if this slot conflicts with existing bookings
            $hasConflict = $existingBookings->contains(function ($booking) use ($slotStart, $slotEnd) {
                return $slotStart->lt($booking->end_time) && $slotEnd->gt($booking->start_time);
            });

            if (!$hasConflict) {
                $availableSlots[] = [
                    'start_time' => $slotStart->format('H:i'),
                    'end_time' => $slotEnd->format('H:i'),
                    'start_datetime' => $slotStart->toISOString(),
                    'end_datetime' => $slotEnd->toISOString(),
                ];
            }

            $currentTime->addMinutes(30); // 30-minute intervals
        }

        return response()->json([
            'room' => $room->name,
            'date' => $date->format('Y-m-d'),
            'duration_minutes' => $duration,
            'available_slots' => $availableSlots,
        ]);
    }
}
