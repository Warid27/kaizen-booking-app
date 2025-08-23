{{-- resources/views/schedule/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Public Schedule - BookingApp')

@section('content')
@php
$bookings = [
    ['id' => 1, 'room_name' => 'Deluxe Suite', 'user_name' => 'John Smith', 'check_in' => '2024-08-25', 'check_out' => '2024-08-27', 'status' => 'Confirmed'],
    ['id' => 2, 'room_name' => 'Standard Room', 'user_name' => 'Sarah Johnson', 'check_in' => '2024-08-26', 'check_out' => '2024-08-28', 'status' => 'Pending'],
    ['id' => 3, 'room_name' => 'Executive Suite', 'user_name' => 'Michael Brown', 'check_in' => '2024-08-28', 'check_out' => '2024-08-30', 'status' => 'Confirmed'],
    ['id' => 4, 'room_name' => 'Family Room', 'user_name' => 'Emily Davis', 'check_in' => '2024-08-29', 'check_out' => '2024-09-01', 'status' => 'Confirmed'],
    ['id' => 5, 'room_name' => 'Standard Room', 'user_name' => 'David Wilson', 'check_in' => '2024-09-02', 'check_out' => '2024-09-04', 'status' => 'Pending'],
    ['id' => 6, 'room_name' => 'Deluxe Suite', 'user_name' => 'Lisa Anderson', 'check_in' => '2024-09-05', 'check_out' => '2024-09-07', 'status' => 'Confirmed'],
    ['id' => 7, 'room_name' => 'Executive Suite', 'user_name' => 'Robert Taylor', 'check_in' => '2024-09-08', 'check_out' => '2024-09-10', 'status' => 'Cancelled'],
    ['id' => 8, 'room_name' => 'Family Room', 'user_name' => 'Jennifer Martinez', 'check_in' => '2024-09-12', 'check_out' => '2024-09-15', 'status' => 'Confirmed']
];
@endphp

<div class="px-4 sm:px-6 lg:px-8">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Schedule', 'href' => '/schedule']
    ]" />

    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-2xl font-semibold leading-6 text-gray-900">Public Booking Schedule</h1>
            <p class="mt-2 text-sm text-gray-700">View all confirmed and pending bookings across all rooms.</p>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="mt-6" x-data="{ 
        searchTerm: '', 
        filteredBookings: @js($bookings),
        allBookings: @js($bookings),
        filterBookings() {
            this.filteredBookings = this.allBookings.filter(booking => {
                const searchLower = this.searchTerm.toLowerCase();
                return booking.room_name.toLowerCase().includes(searchLower) ||
                       booking.user_name.toLowerCase().includes(searchLower) ||
                       booking.status.toLowerCase().includes(searchLower);
            });
        },
        applyFilter() {
            this.filterBookings();
            $store.ui.addToast('info', `Found ${this.filteredBookings.length} bookings matching your search.`);
        }
    }">
        <x-card>
            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <label for="search" class="sr-only">Search bookings</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="h-5 w-5 text-gray-400"></i>
                            </div>
                            <input 
                                type="search" 
                                x-model="searchTerm"
                                @input="filterBookings()"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="Search by room, guest, or status..."
                            >
                        </div>
                    </div>
                    <div>
                        <button 
                            @click="applyFilter()"
                            class="inline-flex items-center space-x-2 px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:scale-105"
                        >
                            <i data-lucide="filter" class="h-4 w-4"></i>
                            <span>Apply Filter</span>
                        </button>
                    </div>
                </div>
            </div>
        </x-card>

        <!-- Bookings Table -->
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guest</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <template x-for="booking in filteredBookings" :key="booking.id">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="booking.room_name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.user_name"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.check_in"></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.check_out"></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span 
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                                :class="{
                                                    'bg-green-100 text-green-800': booking.status === 'Confirmed',
                                                    'bg-yellow-100 text-yellow-800': booking.status === 'Pending',
                                                    'bg-red-100 text-red-800': booking.status === 'Cancelled',
                                                    'bg-blue-100 text-blue-800': booking.status === 'Completed'
                                                }"
                                                x-text="booking.status">
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>

                        <!-- Empty State -->
                        <div x-show="filteredBookings.length === 0" class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No bookings found</h3>
                            <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fake Pagination -->
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-not-allowed opacity-50">
                    Previous
                </button>
                <button class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 cursor-not-allowed opacity-50">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium">1</span> to <span class="font-medium" x-text="filteredBookings.length"></span> of <span class="font-medium" x-text="allBookings.length"></span> results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-not-allowed opacity-50">
                            <span class="sr-only">Previous</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                            1
                        </button>
                        <button class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 cursor-not-allowed opacity-50">
                            2
                        </button>
                        <button class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 cursor-not-allowed opacity-50">
                            <span class="sr-only">Next</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
