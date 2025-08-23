{{-- resources/views/rooms/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Room Details - BookingApp')

@section('content')
@php
// Mock room data - in real app this would come from route parameter
$room = [
    'id' => 1,
    'name' => 'Deluxe Suite',
    'type' => 'Suite',
    'capacity' => 4,
    'price' => 450,
    'status' => 'Available',
    'amenities' => 'WiFi, TV, Mini Bar, Balcony',
    'description' => 'Spacious suite with stunning city views, perfect for business travelers and couples seeking luxury accommodation.',
    'images' => [
        'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=800',
        'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=800'
    ]
];

$recentBookings = [
    ['guest' => 'John Smith', 'check_in' => '2024-08-25', 'check_out' => '2024-08-27', 'status' => 'Confirmed'],
    ['guest' => 'Sarah Johnson', 'check_in' => '2024-08-20', 'check_out' => '2024-08-22', 'status' => 'Completed'],
    ['guest' => 'Michael Brown', 'check_in' => '2024-08-15', 'check_out' => '2024-08-17', 'status' => 'Completed'],
];
@endphp

<div class="px-4 sm:px-6 lg:px-8" x-data="{ userRole: $store.auth.role }">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Rooms', 'href' => '/rooms'],
        ['label' => $room['name'], 'href' => '/rooms/' . $room['id']]
    ]" />

    <!-- Check if user is admin -->
    <div x-show="!$store.auth.isAuthed || userRole !== 'admin'" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Admin Access Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need admin privileges to view room details.</p>
            <div class="mt-6">
                <a href="/rooms" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Back to Rooms
                </a>
            </div>
        </div>
    </div>

    <!-- Admin Content -->
    <div x-show="$store.auth.isAuthed && userRole === 'admin'">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">{{ $room['name'] }}</h1>
                <p class="mt-2 text-sm text-gray-700">Detailed information and booking history for this room.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                <a href="/rooms/{{ $room['id'] }}/edit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Edit Room
                </a>
                <button 
                    type="button"
                    onclick="alert('Delete room functionality (mock)')"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"
                >
                    Delete Room
                </button>
            </div>
        </div>

        <!-- Room Details -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Info -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Room Information</h3>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $room['status'] === 'Available' ? 'bg-green-100 text-green-800' : ($room['status'] === 'Occupied' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                {{ $room['status'] }}
                            </span>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Room Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room['type'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Guest Capacity</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room['capacity'] }} guests</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Price per Night</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ $room['price'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Room ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ $room['id'] }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Amenities</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room['amenities'] }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $room['description'] }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Recent Bookings -->
                <div class="mt-6 bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bookings</h3>
                        <div class="flow-root">
                            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($recentBookings as $booking)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $booking['guest'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking['check_in'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking['check_out'] }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $booking['status'] === 'Confirmed' ? 'bg-green-100 text-green-800' : ($booking['status'] === 'Completed' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                        {{ $booking['status'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button 
                                type="button"
                                onclick="alert('Change status functionality (mock)')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Change Status
                            </button>
                            <button 
                                type="button"
                                onclick="alert('View calendar functionality (mock)')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                View Calendar
                            </button>
                            <a 
                                href="/bookings/create?room={{ $room['id'] }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                            >
                                Create Booking
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Room Stats -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Occupancy Rate</span>
                                <span class="text-sm font-medium text-gray-900">75%</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Total Bookings</span>
                                <span class="text-sm font-medium text-gray-900">{{ count($recentBookings) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Revenue (Month)</span>
                                <span class="text-sm font-medium text-gray-900">${{ number_format($room['price'] * 15) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Avg. Stay</span>
                                <span class="text-sm font-medium text-gray-900">2.3 days</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
