{{-- resources/views/bookings/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Booking - KaiBook')

@section('content')

<div class="px-4 sm:px-6 lg:px-8" x-data="{
    userRole: $store.auth.role,
    loading: true,
    bookingId: null,
    rooms: [],
    formData: {
        room_id: null,
        title: '',
        description: '',
        start_time: '',
        end_time: '',
        attendees: 1,
    },
    init() {
        const parts = window.location.pathname.split('/').filter(Boolean);
        // When on /bookings/{id}/edit, last segment is 'edit' -> take the id before it
        this.bookingId = parts[parts.length - 1] === 'edit' ? parts[parts.length - 2] : parts[parts.length - 1];
        this.loadData();
    },
    async loadData() {
        try {
            const [roomsRes, booking] = await Promise.all([
                window.roomsAPI.getAll(),
                window.bookingsAPI.getById(this.bookingId)
            ]);

            // Normalize rooms (handle array or paginated format)
            const rawRooms = roomsRes?.data ?? roomsRes;
            this.rooms = Array.isArray(rawRooms?.data) ? rawRooms.data : (Array.isArray(rawRooms) ? rawRooms : []);

            // Populate form with booking
            this.formData.room_id = booking.room_id ?? booking.room?.id ?? null;
            this.formData.title = booking.title ?? '';
            this.formData.description = booking.description ?? '';
            this.formData.attendees = booking.attendees ?? 1;

            const toInput = (dt) => {
                if (!dt) return '';
                const d = new Date(dt);
                if (isNaN(d)) return '';
                const pad = (n) => String(n).padStart(2, '0');
                const yyyy = d.getFullYear();
                const MM = pad(d.getMonth() + 1);
                const dd = pad(d.getDate());
                const HH = pad(d.getHours());
                const mm = pad(d.getMinutes());
                return `${yyyy}-${MM}-${dd}T${HH}:${mm}`;
            };
            this.formData.start_time = toInput(booking.start_time);
            this.formData.end_time = toInput(booking.end_time);
        } catch (e) {
            const msg = window.handleApiError ? window.handleApiError(e, 'Failed to load booking') : 'Failed to load booking';
            alert(msg);
        } finally {
            this.loading = false;
        }
    },
    async updateBooking() {
        try {
            const toApi = (val) => {
                if (!val) return '';
                // From 'YYYY-MM-DDTHH:mm' to 'YYYY-MM-DD HH:mm:SS'
                return `${val.replace('T', ' ')}:00`;
            };
            const payload = {
                room_id: this.formData.room_id,
                title: this.formData.title,
                description: this.formData.description,
                start_time: toApi(this.formData.start_time),
                end_time: toApi(this.formData.end_time),
                attendees: Number(this.formData.attendees)
            };

            await window.bookingsAPI.update(this.bookingId, payload);
            // Success feedback and redirect
            window.location.href = `/bookings/${this.bookingId}`;
        } catch (e) {
            const msg = window.handleApiError ? window.handleApiError(e, 'Failed to update booking') : 'Failed to update booking';
            alert(msg);
        }
    }
}">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Bookings', 'href' => '/bookings'],
        ['label' => 'Edit Booking', 'href' => '/bookings']
    ]" />

    <!-- Check if user is authenticated -->
    <div x-show="!$store.auth.isAuthed" class="text-center py-12">
        <div class="mx-auto max-w-md">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Authentication Required</h3>
            <p class="mt-1 text-sm text-gray-500">You need to be logged in to edit bookings.</p>
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
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">Edit Booking</h1>
                <p class="mt-2 text-sm text-gray-700">Update booking details and preferences.</p>
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
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    required
                                >
                                    <option value="" disabled>Select a room</option>
                                    <template x-for="room in rooms" :key="room.id">
                                        <option :value="room.id" x-text="room.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium leading-6 text-gray-900">Title</label>
                            <div class="mt-2">
                                <input
                                    type="text"
                                    id="title"
                                    name="title"
                                    x-model="formData.title"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium leading-6 text-gray-900">Description</label>
                            <div class="mt-2">
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="3"
                                    x-model="formData.description"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    placeholder="Add details about the meeting or event"
                                ></textarea>
                            </div>
                        </div>

                        <!-- Datetimes -->
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="start_time" class="block text-sm font-medium leading-6 text-gray-900">Start Time</label>
                                <div class="mt-2">
                                    <input
                                        type="datetime-local"
                                        id="start_time"
                                        name="start_time"
                                        x-model="formData.start_time"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    />
                                </div>
                            </div>

                            <div>
                                <label for="end_time" class="block text-sm font-medium leading-6 text-gray-900">End Time</label>
                                <div class="mt-2">
                                    <input
                                        type="datetime-local"
                                        id="end_time"
                                        name="end_time"
                                        x-model="formData.end_time"
                                        class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                        required
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Attendees -->
                        <div>
                            <label for="attendees" class="block text-sm font-medium leading-6 text-gray-900">Attendees</label>
                            <div class="mt-2">
                                <input
                                    type="number"
                                    id="attendees"
                                    name="attendees"
                                    min="1"
                                    x-model="formData.attendees"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                            <a
                                :href="`/bookings/${bookingId}`"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                            >
                                Cancel
                            </a>
                            <button
                                type="button"
                                :disabled="loading"
                                @click="updateBooking()"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50"
                            >
                                Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Side panel (optional placeholder) -->
            <div class="space-y-6">
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Editing</h3>
                        <p class="text-sm text-gray-600">Update your booking details and save.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
