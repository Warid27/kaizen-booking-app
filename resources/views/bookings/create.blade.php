{{-- resources/views/bookings/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Booking - BookingApp')

@section('content')
@php
$availableRooms = [
    ['id' => 1, 'name' => 'Deluxe Suite', 'type' => 'Suite', 'capacity' => 4, 'price' => 450],
    ['id' => 2, 'name' => 'Standard Room', 'type' => 'Standard', 'capacity' => 2, 'price' => 200],
    ['id' => 3, 'name' => 'Executive Suite', 'type' => 'Suite', 'capacity' => 6, 'price' => 600],
    ['id' => 4, 'name' => 'Family Room', 'type' => 'Family', 'capacity' => 8, 'price' => 350],
    ['id' => 5, 'name' => 'Economy Room', 'type' => 'Economy', 'capacity' => 2, 'price' => 150],
];
@endphp

<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    formData: {
        room_id: '',
        guest_name: '',
        guest_email: '',
        guest_phone: '',
        check_in: '',
        check_out: '',
        guests: 1,
        special_requests: ''
    },
    selectedRoom: null,
    totalNights: 0,
    totalPrice: 0,
    availableRooms: @js($availableRooms),
    
    init() {
        // Set default dates (today + 1 and today + 3)
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(today.getDate() + 1);
        const dayAfter = new Date(today);
        dayAfter.setDate(today.getDate() + 3);
        
        this.formData.check_in = tomorrow.toISOString().split('T')[0];
        this.formData.check_out = dayAfter.toISOString().split('T')[0];
        this.calculateTotal();
    },
    
    selectRoom() {
        this.selectedRoom = this.availableRooms.find(room => room.id == this.formData.room_id);
        this.calculateTotal();
    },
    
    calculateTotal() {
        if (this.formData.check_in && this.formData.check_out && this.selectedRoom) {
            const checkIn = new Date(this.formData.check_in);
            const checkOut = new Date(this.formData.check_out);
            const timeDiff = checkOut.getTime() - checkIn.getTime();
            this.totalNights = Math.ceil(timeDiff / (1000 * 3600 * 24));
            this.totalPrice = this.totalNights * this.selectedRoom.price;
        } else {
            this.totalNights = 0;
            this.totalPrice = 0;
        }
    },
    
    createBooking() {
        // Show loading
        $store.ui.showLoading();
        
        // Simulate API call delay
        setTimeout(() => {
            $store.ui.hideLoading();
            $store.ui.addToast('success', `Booking created successfully for ${this.formData.guest_name}! Total: $${this.totalPrice}`);
            
            // Redirect after a short delay to show the toast
            setTimeout(() => {
                window.location.href = '/bookings';
            }, 1500);
        }, 1000);
    }
}">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Bookings', 'href' => '/bookings'],
        ['label' => 'Create Booking', 'href' => '/bookings/create']
    ]" />

    <!-- Check if user is authenticated -->
    <div x-show="!$store.auth.isAuthed" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Authentication Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need to be logged in to create bookings.</p>
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
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Create New Booking</h1>
                <p class="mt-2 text-sm text-gray-700">Reserve a room for your stay.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Form -->
            <div class="lg:col-span-2">
                <div class="bg-white shadow sm:rounded-lg">
                    <form action="#" class="space-y-6 p-6">
                        <!-- Room Selection -->
                        <div>
                            <label for="room_id" class="block text-sm font-medium leading-6 text-gray-900">Select Room</label>
                            <div class="mt-2">
                                <select 
                                    id="room_id" 
                                    name="room_id" 
                                    x-model="formData.room_id"
                                    @change="selectRoom()"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    required
                                >
                                    <option value="">Choose a room...</option>
                                    <template x-for="room in availableRooms" :key="room.id">
                                        <option :value="room.id" x-text="`${room.name} - ${room.type} (${room.capacity} guests) - $${room.price}/night`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Guest Information -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="guest_name" class="block text-sm font-medium leading-6 text-gray-900">Guest Name</label>
                                <div class="mt-2">
                                    <input 
                                        type="text" 
                                        name="guest_name" 
                                        id="guest_name" 
                                        x-model="formData.guest_name"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="Full name"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="guest_email" class="block text-sm font-medium leading-6 text-gray-900">Email Address</label>
                                <div class="mt-2">
                                    <input 
                                        type="email" 
                                        name="guest_email" 
                                        id="guest_email" 
                                        x-model="formData.guest_email"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="email@example.com"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="guest_phone" class="block text-sm font-medium leading-6 text-gray-900">Phone Number</label>
                                <div class="mt-2">
                                    <input 
                                        type="tel" 
                                        name="guest_phone" 
                                        id="guest_phone" 
                                        x-model="formData.guest_phone"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="+1 (555) 123-4567"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="guests" class="block text-sm font-medium leading-6 text-gray-900">Number of Guests</label>
                                <div class="mt-2">
                                    <input 
                                        type="number" 
                                        name="guests" 
                                        id="guests" 
                                        min="1" 
                                        :max="selectedRoom ? selectedRoom.capacity : 10"
                                        x-model="formData.guests"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                                <p class="mt-1 text-sm text-gray-500" x-show="selectedRoom">
                                    Maximum capacity: <span x-text="selectedRoom.capacity"></span> guests
                                </p>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="check_in" class="block text-sm font-medium leading-6 text-gray-900">Check-in Date</label>
                                <div class="mt-2">
                                    <input 
                                        type="date" 
                                        name="check_in" 
                                        id="check_in" 
                                        x-model="formData.check_in"
                                        @change="calculateTotal()"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="check_out" class="block text-sm font-medium leading-6 text-gray-900">Check-out Date</label>
                                <div class="mt-2">
                                    <input 
                                        type="date" 
                                        name="check_out" 
                                        id="check_out" 
                                        x-model="formData.check_out"
                                        @change="calculateTotal()"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div>
                            <label for="special_requests" class="block text-sm font-medium leading-6 text-gray-900">Special Requests</label>
                            <div class="mt-2">
                                <textarea 
                                    id="special_requests" 
                                    name="special_requests" 
                                    rows="3" 
                                    x-model="formData.special_requests"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    placeholder="Any special requests or requirements..."
                                ></textarea>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a 
                                href="/bookings" 
                                class="inline-flex items-center space-x-2 px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors"
                            >
                                <i data-lucide="x" class="h-4 w-4"></i>
                                <span>Cancel</span>
                            </a>
                            <button 
                                type="button" 
                                @click="createBooking()"
                                class="inline-flex items-center space-x-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 hover:scale-105"
                                :disabled="!formData.room_id || !formData.guest_name || !formData.guest_email || !formData.check_in || !formData.check_out"
                            >
                                <i data-lucide="calendar-plus" class="h-4 w-4"></i>
                                <span>Create Booking</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Booking Summary -->
            <div class="space-y-6">
                <!-- Selected Room -->
                <div class="bg-white shadow sm:rounded-lg" x-show="selectedRoom">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Selected Room</h3>
                        <div class="space-y-3">
                            <div>
                                <div class="text-sm font-medium text-gray-900" x-text="selectedRoom?.name"></div>
                                <div class="text-sm text-gray-500" x-text="selectedRoom?.type"></div>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Capacity:</span>
                                <span class="text-gray-900" x-text="`${selectedRoom?.capacity} guests`"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Price per night:</span>
                                <span class="text-gray-900">$<span x-text="selectedRoom?.price"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="bg-white shadow sm:rounded-lg" x-show="totalNights > 0">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Check-in:</span>
                                <span class="text-gray-900" x-text="formData.check_in"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Check-out:</span>
                                <span class="text-gray-900" x-text="formData.check_out"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Nights:</span>
                                <span class="text-gray-900" x-text="totalNights"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Guests:</span>
                                <span class="text-gray-900" x-text="formData.guests"></span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="text-base font-medium text-gray-900">Total:</span>
                                    <span class="text-base font-medium text-gray-900">$<span x-text="totalPrice"></span></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Help -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Need Help?
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>This is a demo booking form. No real reservation will be made. All data is for demonstration purposes only.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
