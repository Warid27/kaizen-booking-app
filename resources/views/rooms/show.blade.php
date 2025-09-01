{{-- resources/views/rooms/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Room Details - KaiBook')

@section('content')
{{-- Using Alpine.js to fetch real room data from API --}}

<div class="px-4 sm:px-6 lg:px-8" x-data="{
    userRole: $store.auth.role,
    room: null,
    loading: true,
    error: null,
    get id() { return parseInt(window.location.pathname.split('/').pop()); },
    get bookings() { return (this.room && Array.isArray(this.room.bookings)) ? this.room.bookings : []; },
    get computedStatus() { return this.bookings.length > 0 ? 'Booked' : 'Available'; },
    async loadRoom() {
        try {
            const resp = await window.roomsAPI.getById(this.id);
            // API returns { message, data }
            this.room = resp.data || resp;
        } catch (e) {
            this.error = window.handleApiError ? window.handleApiError(e, 'Failed to load room') : 'Failed to load room';
            this.$store.ui && this.$store.ui.addToast && this.$store.ui.addToast('error', this.error);
        } finally {
            this.loading = false;
        }
    }
}" x-init="loadRoom()">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Rooms', 'href' => '/rooms'],
        ['label' => 'Room Details', 'href' => '/rooms']
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
                <h1 class="text-2xl font-semibold leading-6 text-gray-900" x-text="room?.name || 'Room'"></h1>
                <p class="mt-2 text-sm text-gray-700">Detailed information and booking history for this room.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                <a :href="`/rooms/${room?.id ?? id}/edit`" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
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
                            <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full" :class="computedStatus === 'Available' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" x-text="computedStatus"></span>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="room?.description || '—'"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Guest Capacity</dt>
                                <dd class="mt-1 text-sm text-gray-900"><span x-text="room?.capacity ?? '—'"></span> guests</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Room ID</dt>
                                <dd class="mt-1 text-sm text-gray-900">#<span x-text="room?.id ?? id"></span></dd>
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
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200" x-show="bookings.length > 0">
                                            <template x-for="b in bookings" :key="b.id">
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="b.user_id"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="b.start_time"></td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="b.end_time"></td>
                                                </tr>
                                            </template>
                                        </tbody>
                                    </table>
                                    <div x-show="!loading && bookings.length === 0" class="text-center text-sm text-gray-500 py-6">No bookings yet</div>
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
                                :href="`/bookings/create?room=${room?.id ?? id}`"
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
                                <span class="text-sm font-medium text-gray-900" x-text="bookings.length"></span>
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
