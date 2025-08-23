{{-- resources/views/bookings/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Booking Details - BookingApp')

@section('content')
@php
// Mock booking data - in real app this would come from route parameter
$booking = [
    'id' => 1,
    'room_name' => 'Deluxe Suite',
    'room_type' => 'Suite',
    'room_id' => 1,
    'guest_name' => 'John Smith',
    'guest_email' => 'john.smith@example.com',
    'guest_phone' => '+1 (555) 123-4567',
    'check_in' => '2024-08-25',
    'check_out' => '2024-08-27',
    'guests' => 2,
    'total_price' => 900,
    'status' => 'Confirmed',
    'special_requests' => 'Late check-in requested. Non-smoking room preferred.',
    'created_at' => '2024-08-20 14:30:00',
    'confirmation_code' => 'BK-2024-001'
];
@endphp

<div class="px-4 sm:px-6 lg:px-8" x-data="{ userRole: $store.auth.role }">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Bookings', 'href' => '/bookings'],
        ['label' => 'Booking #' . $booking['id'], 'href' => '/bookings/' . $booking['id']]
    ]" />

    <!-- Check if user is authenticated -->
    <div x-show="!$store.auth.isAuthed" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Authentication Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need to be logged in to view booking details.</p>
            <div class="mt-6">
                <a href="/login" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Sign In
                </a>
            </div>
        </div>
    </div>

    <!-- Authenticated Content -->
    <div x-show="$store.auth.isAuthed">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Booking Details</h1>
                <p class="mt-2 text-sm text-gray-700">Confirmation code: {{ $booking['confirmation_code'] }}</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                <a href="/bookings/{{ $booking['id'] }}/edit" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Edit Booking
                </a>
                <button 
                    type="button"
                    onclick="alert('Cancel booking functionality (mock)')"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700"
                >
                    Cancel Booking
                </button>
            </div>
        </div>

        <!-- Booking Details -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Booking Information -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Booking Information</h3>
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full {{ $booking['status'] === 'Confirmed' ? 'bg-green-100 text-green-800' : ($booking['status'] === 'Pending' ? 'bg-yellow-100 text-yellow-800' : ($booking['status'] === 'Completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                {{ $booking['status'] }}
                            </span>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Booking ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">#{{ $booking['id'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Confirmation Code</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking['confirmation_code'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Room</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking['room_name'] }} ({{ $booking['room_type'] }})</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Number of Guests</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking['guests'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Check-in Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ date('F j, Y', strtotime($booking['check_in'])) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Check-out Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ date('F j, Y', strtotime($booking['check_out'])) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Nights</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ (strtotime($booking['check_out']) - strtotime($booking['check_in'])) / (60*60*24) }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Total Price</dt>
                                <dd class="mt-1 text-sm text-gray-900">${{ number_format($booking['total_price']) }}</dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Booking Date</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ date('F j, Y \a\t g:i A', strtotime($booking['created_at'])) }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Guest Information -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Guest Information</h3>
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Guest Name</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $booking['guest_name'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="mailto:{{ $booking['guest_email'] }}" class="text-blue-600 hover:text-blue-500">
                                        {{ $booking['guest_email'] }}
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="tel:{{ $booking['guest_phone'] }}" class="text-blue-600 hover:text-blue-500">
                                        {{ $booking['guest_phone'] }}
                                    </a>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Special Requests -->
                @if($booking['special_requests'])
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Special Requests</h3>
                        <p class="text-sm text-gray-700">{{ $booking['special_requests'] }}</p>
                    </div>
                </div>
                @endif
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
                                onclick="window.print()"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Confirmation
                            </button>
                            <button 
                                type="button"
                                onclick="alert('Send confirmation email functionality (mock)')"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Email Confirmation
                            </button>
                            <a 
                                href="/rooms/{{ $booking['room_id'] }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                x-show="userRole === 'admin'"
                            >
                                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                View Room Details
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Booking Timeline -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Timeline</h3>
                        <div class="flow-root">
                            <ul class="-mb-8">
                                <li>
                                    <div class="relative pb-8">
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">Booking created</p>
                                                    <p class="text-xs text-gray-400">{{ date('M j, Y \a\t g:i A', strtotime($booking['created_at'])) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="relative pb-8">
                                        <div class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"></div>
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">Booking confirmed</p>
                                                    <p class="text-xs text-gray-400">{{ date('M j, Y \a\t g:i A', strtotime($booking['created_at'] . ' +1 hour')) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="relative">
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5">
                                                <div>
                                                    <p class="text-sm text-gray-500">Check-in scheduled</p>
                                                    <p class="text-xs text-gray-400">{{ date('M j, Y', strtotime($booking['check_in'])) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Room rate:</span>
                                <span class="text-gray-900">${{ number_format($booking['total_price'] / ((strtotime($booking['check_out']) - strtotime($booking['check_in'])) / (60*60*24))) }}/night</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Nights:</span>
                                <span class="text-gray-900">{{ (strtotime($booking['check_out']) - strtotime($booking['check_in'])) / (60*60*24) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Subtotal:</span>
                                <span class="text-gray-900">${{ number_format($booking['total_price']) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Taxes & fees:</span>
                                <span class="text-gray-900">$0</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-medium text-gray-900">Total:</span>
                                    <span class="text-base font-medium text-gray-900">${{ number_format($booking['total_price']) }}</span>
                                </div>
                            </div>
                            <div class="mt-4">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    Payment Confirmed
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
