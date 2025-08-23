{{-- resources/views/rooms/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Room - BookingApp')

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
    'description' => 'Spacious suite with stunning city views, perfect for business travelers and couples seeking luxury accommodation.'
];
@endphp

<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    formData: @js($room),
    updateRoom() {
        // Mock update functionality
        alert('Room updated successfully! (Mock operation)');
        window.location.href = '/rooms/' + this.formData.id;
    }
}">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Rooms', 'href' => '/rooms'],
        ['label' => $room['name'], 'href' => '/rooms/' . $room['id']],
        ['label' => 'Edit', 'href' => '/rooms/' . $room['id'] . '/edit']
    ]" />

    <!-- Check if user is admin -->
    <div x-show="!$store.auth.isAuthed || userRole !== 'admin'" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Admin Access Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need admin privileges to edit rooms.</p>
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
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Edit Room</h1>
                <p class="mt-2 text-sm text-gray-700">Update room details and pricing information.</p>
            </div>
        </div>

        <!-- Form -->
        <div class="mt-6">
            <div class="bg-white shadow sm:rounded-lg">
                <form action="#" class="space-y-6 p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Room Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium leading-6 text-gray-900">Room Name</label>
                            <div class="mt-2">
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    x-model="formData.name"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                            </div>
                        </div>

                        <!-- Room Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium leading-6 text-gray-900">Room Type</label>
                            <div class="mt-2">
                                <select 
                                    id="type" 
                                    name="type" 
                                    x-model="formData.type"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option value="Economy">Economy</option>
                                    <option value="Standard">Standard</option>
                                    <option value="Deluxe">Deluxe</option>
                                    <option value="Suite">Suite</option>
                                    <option value="Family">Family</option>
                                </select>
                            </div>
                        </div>

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium leading-6 text-gray-900">Guest Capacity</label>
                            <div class="mt-2">
                                <input 
                                    type="number" 
                                    name="capacity" 
                                    id="capacity" 
                                    min="1" 
                                    max="20"
                                    x-model="formData.capacity"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                            </div>
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="price" class="block text-sm font-medium leading-6 text-gray-900">Price per Night ($)</label>
                            <div class="mt-2">
                                <input 
                                    type="number" 
                                    name="price" 
                                    id="price" 
                                    min="0" 
                                    step="0.01"
                                    x-model="formData.price"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium leading-6 text-gray-900">Room Status</label>
                            <div class="mt-2">
                                <select 
                                    id="status" 
                                    name="status" 
                                    x-model="formData.status"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                                    <option value="Available">Available</option>
                                    <option value="Occupied">Occupied</option>
                                    <option value="Maintenance">Maintenance</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div>
                        <label for="amenities" class="block text-sm font-medium leading-6 text-gray-900">Amenities</label>
                        <div class="mt-2">
                            <input 
                                type="text" 
                                name="amenities" 
                                id="amenities" 
                                x-model="formData.amenities"
                                class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                            >
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Separate amenities with commas</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                        <div class="mt-2">
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4" 
                                x-model="formData.description"
                                class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <a 
                            :href="`/rooms/${formData.id}`"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                        >
                            Cancel
                        </a>
                        <button 
                            type="button" 
                            @click="updateRoom()"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                        >
                            Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Preview</h3>
            <div class="bg-white overflow-hidden shadow rounded-lg max-w-sm">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900" x-text="formData.name"></h3>
                        <span 
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                            :class="{
                                'bg-green-100 text-green-800': formData.status === 'Available',
                                'bg-red-100 text-red-800': formData.status === 'Occupied',
                                'bg-yellow-100 text-yellow-800': formData.status === 'Maintenance'
                            }"
                            x-text="formData.status">
                        </span>
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            <span x-text="formData.type"></span> • 
                            <span x-text="formData.capacity"></span> guests • 
                            $<span x-text="formData.price"></span>/night
                        </p>
                        <p class="mt-1 text-sm text-gray-600" x-text="formData.amenities"></p>
                        <p class="mt-2 text-sm text-gray-700" x-text="formData.description"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
