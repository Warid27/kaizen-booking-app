{{-- resources/views/bookings/index.blade.php --}}
@extends('layouts.app')

@section('title', 'My Bookings - KaiBook')

@section('content')
<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    searchTerm: '', 
    filteredBookings: [],
    allBookings: [],
    isLoading: false,
    normalize(b) {
        const roomName = b.room?.name || b.room_name || `Room #${b.room_id || ''}`.trim();
        // Use user_id directly (fallback to nested user.id if present)
        const userId = b.user_id || b.user?.id || '';
        const checkIn = b.start_time || b.check_in || '';
        const checkOut = b.end_time || b.check_out || '';
        const guests = userId || '';
        const roomType = b.room?.type || b.room_type || '';
        return { id: b.id, room_name: roomName, user_id: userId, room_type: roomType, check_in: checkIn, check_out: checkOut, guests };
    },
    async loadBookings() {
        this.isLoading = true;
        try {
            const data = await window.bookingsAPI.getAll();
            const list = Array.isArray(data) ? data : (data.data || []);
            this.allBookings = list.map(this.normalize);
            this.filteredBookings = this.allBookings;
        } catch (error) {
            this.$store.ui.addToast('error', window.handleApiError(error, 'Failed to load bookings'));
        } finally {
            this.isLoading = false;
        }
    },
    filterBookings() {
        const searchLower = this.searchTerm.toLowerCase();
        this.filteredBookings = this.allBookings.filter(booking => {
            return (booking.room_name || '').toLowerCase().includes(searchLower) ||
                   String(booking.user_id || '').toLowerCase().includes(searchLower);
        });
    }
}" x-init="loadBookings()">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Bookings', 'href' => '/bookings']
    ]" />

    <!-- Check if user is authenticated -->
    <div x-show="!$store.auth.isAuthed" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Authentication Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need to be logged in to view bookings.</p>
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
                <h1 class="text-2xl font-semibold leading-6 text-gray-900" x-text="userRole === 'admin' ? 'All Bookings' : 'My Bookings'"></h1>
                <p class="mt-2 text-sm text-gray-700" x-text="userRole === 'admin' ? 'Manage all bookings across the system.' : 'View and manage your room reservations.'"></p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="/bookings/create" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    New Booking
                </a>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="mt-6">
            <div class="flex flex-col sm:flex-row gap-4 mb-6">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search bookings</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <input 
                            type="search" 
                            x-model="searchTerm"
                            @input="filterBookings()"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                            :placeholder="userRole === 'admin' ? 'Search by room or user ID...' : 'Search by room...'"
                        >
                    </div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="mt-8 flow-root">
                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-300">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Room</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" x-show="userRole === 'admin'">User ID</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-in</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-out</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guests</th>
                                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">Actions</span></th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <template x-for="booking in filteredBookings" :key="booking.id">
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900" x-text="booking.room_name"></div>
                                                <div class="text-sm text-gray-500" x-text="booking.room_type" x-show="booking.room_type"></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-show="userRole === 'admin'" x-text="booking.user_id"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.check_in"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.check_out"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="booking.guests"></td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex justify-end space-x-2">
                                                    <a 
                                                        :href="`/bookings/${booking.id}`"
                                                        class="text-blue-600 hover:text-blue-900"
                                                    >
                                                        View
                                                    </a>
                                                    <a 
                                                        :href="`/bookings/${booking.id}/edit`"
                                                        class="text-blue-600 hover:text-blue-900"
                                                    >
                                                        Edit
                                                    </a>
                                                    <!-- Optional: Wire real cancel via API later -->
                                                </div>
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
                                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or create a new booking.</p>
                                <div class="mt-6">
                                    <a href="/bookings/create" class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                                        Create Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="mt-8 bg-gray-50 rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900" x-text="allBookings.length"></div>
                        <div class="text-sm text-gray-500">Total Bookings</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
