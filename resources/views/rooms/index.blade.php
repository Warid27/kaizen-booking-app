{{-- resources/views/rooms/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Rooms Management - BookingApp')

@section('content')
@php
$rooms = [
    ['id' => 1, 'name' => 'Deluxe Suite', 'type' => 'Suite', 'capacity' => 4, 'price' => 450, 'status' => 'Available', 'amenities' => 'WiFi, TV, Mini Bar, Balcony'],
    ['id' => 2, 'name' => 'Standard Room', 'type' => 'Standard', 'capacity' => 2, 'price' => 200, 'status' => 'Occupied', 'amenities' => 'WiFi, TV'],
    ['id' => 3, 'name' => 'Executive Suite', 'type' => 'Suite', 'capacity' => 6, 'price' => 600, 'status' => 'Available', 'amenities' => 'WiFi, TV, Mini Bar, Balcony, Jacuzzi'],
    ['id' => 4, 'name' => 'Family Room', 'type' => 'Family', 'capacity' => 8, 'price' => 350, 'status' => 'Maintenance', 'amenities' => 'WiFi, TV, Kitchenette'],
    ['id' => 5, 'name' => 'Economy Room', 'type' => 'Economy', 'capacity' => 2, 'price' => 150, 'status' => 'Available', 'amenities' => 'WiFi'],
    ['id' => 6, 'name' => 'Presidential Suite', 'type' => 'Suite', 'capacity' => 10, 'price' => 1200, 'status' => 'Available', 'amenities' => 'WiFi, TV, Mini Bar, Balcony, Jacuzzi, Butler Service'],
];
@endphp

<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    searchTerm: '', 
    filteredRooms: @js($rooms),
    allRooms: @js($rooms),
    filterRooms() {
        this.filteredRooms = this.allRooms.filter(room => {
            const searchLower = this.searchTerm.toLowerCase();
            return room.name.toLowerCase().includes(searchLower) ||
                   room.type.toLowerCase().includes(searchLower) ||
                   room.status.toLowerCase().includes(searchLower);
        });
    }
}">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Rooms', 'href' => '/rooms']
    ]" />

    <!-- Check if user is admin -->
    <div x-show="!$store.auth.isAuthed || userRole !== 'admin'" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Admin Access Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need admin privileges to manage rooms.</p>
            <div class="mt-6">
                <a href="/dashboard" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                    Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Admin Content -->
    <div x-show="$store.auth.isAuthed && userRole === 'admin'">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Rooms Management</h1>
                <p class="mt-2 text-sm text-gray-700">Manage all rooms, their availability, and pricing.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="/rooms/create" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Add Room
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mt-6">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search rooms</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                        </div>
                        <input 
                            type="search" 
                            x-model="searchTerm"
                            @input="filterRooms()"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            placeholder="Search by name, type, or status..."
                        >
                    </div>
                </div>
            </div>

            <!-- Rooms Grid -->
            <x-card>
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <template x-for="room in filteredRooms" :key="room.id">
                            <div class="bg-gray-50 overflow-hidden shadow-sm rounded-lg border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                <div class="px-4 py-5 sm:p-6">
                                    <div class="flex items-center justify-between">
                                        <h3 class="text-lg font-medium text-gray-900" x-text="room.name"></h3>
                                        <span 
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                            :class="{
                                                'bg-green-100 text-green-800': room.status === 'Available',
                                                'bg-red-100 text-red-800': room.status === 'Occupied',
                                                'bg-yellow-100 text-yellow-800': room.status === 'Maintenance'
                                            }"
                                            x-text="room.status">
                                        </span>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <span x-text="room.type"></span> • 
                                            <span x-text="room.capacity"></span> guests • 
                                            $<span x-text="room.price"></span>/night
                                        </p>
                                        <p class="mt-1 text-sm text-gray-600" x-text="room.amenities"></p>
                                    </div>
                                    <div class="mt-4 flex space-x-2">
                                        <a 
                                            :href="`/rooms/${room.id}`"
                                            class="inline-flex items-center space-x-1 px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 hover:scale-105"
                                        >
                                            <i data-lucide="eye" class="h-3 w-3"></i>
                                            <span>View</span>
                                        </a>
                                        <a 
                                            :href="`/rooms/${room.id}/edit`"
                                            class="inline-flex items-center space-x-1 px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 hover:scale-105"
                                        >
                                            <i data-lucide="edit" class="h-3 w-3"></i>
                                            <span>Edit</span>
                                        </a>
                                        <button 
                                            type="button"
                                            class="inline-flex items-center space-x-1 px-3 py-1.5 border border-transparent text-xs font-medium rounded text-white bg-red-600 hover:bg-red-700 transition-all duration-200 hover:scale-105"
                                            @click="$store.ui.showModal(
                                                'Delete Room', 
                                                `Are you sure you want to delete ${room.name}? This action cannot be undone. (This is a demo - no actual deletion will occur)`,
                                                () => $store.ui.addToast('success', `Room ${room.name} deleted successfully! (Mock operation)`),
                                                null,
                                                'Delete',
                                                'Cancel'
                                            )"
                                        >
                                            <i data-lucide="trash-2" class="h-3 w-3"></i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </x-card>

            <!-- Empty State -->
            <div x-show="filteredRooms.length === 0" class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No rooms found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
            </div>

            <!-- Summary Stats -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Room Statistics</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900" x-text="allRooms.length"></div>
                        <div class="text-sm text-gray-500">Total Rooms</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600" x-text="allRooms.filter(r => r.status === 'Available').length"></div>
                        <div class="text-sm text-gray-500">Available</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600" x-text="allRooms.filter(r => r.status === 'Occupied').length"></div>
                        <div class="text-sm text-gray-500">Occupied</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600" x-text="allRooms.filter(r => r.status === 'Maintenance').length"></div>
                        <div class="text-sm text-gray-500">Maintenance</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
