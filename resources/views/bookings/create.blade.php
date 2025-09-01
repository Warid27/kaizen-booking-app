{{-- resources/views/bookings/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Create Booking - KaiBook')

@section('content')
<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    formData: {
        room_id: '',
        title: '',
        description: '',
        start_time: '',
        end_time: '',
        attendees: 1
    },
    availableRooms: [],
    selectedRoom: null,

    init() {
        this.loadRooms();
        // default start/end times: now +1h, +2h
        const now = new Date();
        const start = new Date(now.getTime() + 60*60*1000);
        const end = new Date(now.getTime() + 2*60*60*1000);
        this.formData.start_time = this.toLocalInput(start);
        this.formData.end_time = this.toLocalInput(end);
    },

    async loadRooms() {
        try {
            const data = await window.roomsAPI.getAll();
            this.availableRooms = Array.isArray(data) ? data : (data.data || []);
        } catch (e) {
            this.$store.ui.addToast('error', window.handleApiError(e, 'Failed to load rooms'));
        }
    },

    selectRoom() {
        this.selectedRoom = this.availableRooms.find(r => String(r.id) === String(this.formData.room_id));
    },

    toLocalInput(d) {
        const pad = (n) => n.toString().padStart(2, '0');
        return `${d.getFullYear()}-${pad(d.getMonth()+1)}-${pad(d.getDate())}T${pad(d.getHours())}:${pad(d.getMinutes())}`;
    },

    toApiDateTime(v) {
        // v is from datetime-local (YYYY-MM-DDTHH:mm)
        if (!v) return '';
        return v.replace('T', ' ') + ':00';
    },

    async createBooking() {
        try {
            this.$store.ui.showLoading();
            const payload = {
                room_id: Number(this.formData.room_id),
                title: this.formData.title,
                description: this.formData.description,
                start_time: this.toApiDateTime(this.formData.start_time),
                end_time: this.toApiDateTime(this.formData.end_time),
                attendees: Number(this.formData.attendees)
            };
            await window.bookingsAPI.create(payload);
            this.$store.ui.addToast('success', 'Booking created successfully');
            window.location.href = '/bookings';
        } catch (error) {
            this.$store.ui.addToast('error', window.handleApiError(error, 'Failed to create booking'));
        } finally {
            this.$store.ui.hideLoading();
        }
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
                                        <option :value="room.id" x-text="`${room.name} (${room.capacity} capacity)`"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                                <div class="mt-2">
                                    <input 
                                        type="text" 
                                        name="title" 
                                        id="title" 
                                        x-model="formData.title"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="Team Meeting"
                                        required
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                                <div class="mt-2">
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        rows="3" 
                                        x-model="formData.description"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        placeholder="Weekly team standup meeting"
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Start/End and Attendees -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                            <div class="sm:col-span-1">
                                <label for="start_time" class="block text-sm font-medium leading-6 text-gray-900">Start Time</label>
                                <div class="mt-2">
                                    <input 
                                        type="datetime-local" 
                                        name="start_time" 
                                        id="start_time" 
                                        x-model="formData.start_time"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <label for="end_time" class="block text-sm font-medium leading-6 text-gray-900">End Time</label>
                                <div class="mt-2">
                                    <input 
                                        type="datetime-local" 
                                        name="end_time" 
                                        id="end_time" 
                                        x-model="formData.end_time"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                            </div>

                            <div class="sm:col-span-1">
                                <label for="attendees" class="block text-sm font-medium leading-6 text-gray-900">Attendees</label>
                                <div class="mt-2">
                                    <input 
                                        type="number" 
                                        name="attendees" 
                                        id="attendees" 
                                        min="1" 
                                        :max="selectedRoom ? selectedRoom.capacity : 100"
                                        x-model="formData.attendees"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    >
                                </div>
                                <p class="mt-1 text-sm text-gray-500" x-show="selectedRoom">
                                    Room capacity: <span x-text="selectedRoom.capacity"></span>
                                </p>
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
                                :disabled="!formData.room_id || !formData.title || !formData.start_time || !formData.end_time || !formData.attendees"
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
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Capacity:</span>
                                <span class="text-gray-900" x-text="`${selectedRoom?.capacity} people`"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Summary</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Title:</span>
                                <span class="text-gray-900" x-text="formData.title || '-' "></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Start:</span>
                                <span class="text-gray-900" x-text="formData.start_time"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">End:</span>
                                <span class="text-gray-900" x-text="formData.end_time"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Attendees:</span>
                                <span class="text-gray-900" x-text="formData.attendees"></span>
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
                                <p>Fill in meeting details and submit. The booking will be created via the API.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
