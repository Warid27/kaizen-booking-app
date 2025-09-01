{{-- resources/views/rooms/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Edit Room - KaiBook')

@section('content')
<div class="px-4 sm:px-6 lg:px-8" x-data="{ 
    userRole: $store.auth.role,
    loading: true,
    error: null,
    formData: { id: null, name: '', description: '', capacity: null, location: '', amenitiesText: '' },
    get id() { return parseInt(window.location.pathname.split('/').slice(-2)[0]); },
    async loadRoom() {
        try {
            const resp = await window.roomsAPI.getById(this.id);
            const room = resp.data || resp;
            this.formData.id = room.id;
            this.formData.name = room.name || '';
            this.formData.description = room.description || '';
            this.formData.capacity = room.capacity ?? null;
            this.formData.location = room.location || '';
            // amenities in API may be array; join to comma-separated for editing
            const am = Array.isArray(room.amenities) ? room.amenities : (room.amenities ? [room.amenities] : []);
            this.formData.amenitiesText = am.join(', ');
        } catch (e) {
            this.error = window.handleApiError ? window.handleApiError(e, 'Failed to load room') : 'Failed to load room';
            this.$store.ui && this.$store.ui.addToast && this.$store.ui.addToast('error', this.error);
        } finally {
            this.loading = false;
        }
    },
    async updateRoom() {
        const payload = {
            name: this.formData.name,
            description: this.formData.description,
            capacity: Number(this.formData.capacity),
            location: this.formData.location || null,
            amenities: this.formData.amenitiesText
                ? this.formData.amenitiesText.split(',').map(s => s.trim()).filter(Boolean)
                : []
        };
        try {
            await window.roomsAPI.update(this.formData.id ?? this.id, payload);
            this.$store.ui && this.$store.ui.addToast && this.$store.ui.addToast('success', 'Room updated successfully');
            window.location.href = `/rooms/${this.formData.id ?? this.id}`;
        } catch (e) {
            const msg = window.handleApiError ? window.handleApiError(e, 'Failed to update room') : 'Failed to update room';
            this.$store.ui && this.$store.ui.addToast && this.$store.ui.addToast('error', msg);
        }
    }
}" x-init="loadRoom()">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Rooms', 'href' => '/rooms'],
        ['label' => 'Edit', 'href' => request()->path()]
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
                <p class="mt-2 text-sm text-gray-700">Update room details.</p>
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

                        <!-- Capacity -->
                        <div>
                            <label for="capacity" class="block text-sm font-medium leading-6 text-gray-900">Guest Capacity</label>
                            <div class="mt-2">
                                <input 
                                    type="number" 
                                    name="capacity" 
                                    id="capacity" 
                                    min="1" 
                                    max="1000"
                                    x-model.number="formData.capacity"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
                            </div>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium leading-6 text-gray-900">Location</label>
                            <div class="mt-2">
                                <input 
                                    type="text" 
                                    name="location" 
                                    id="location" 
                                    x-model="formData.location"
                                    placeholder="e.g. 2nd Floor, East Wing"
                                    class="block w-full rounded-md border-0 py-1.5 px-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                >
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
                                x-model="formData.amenitiesText"
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
                            :href="`/rooms/${formData.id ?? id}`"
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
                    </div>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500"><span x-text="formData.capacity"></span> guests</p>
                        <p class="mt-1 text-sm text-gray-600" x-text="formData.location"></p>
                        <p class="mt-1 text-sm text-gray-600" x-text="formData.amenitiesText"></p>
                        <p class="mt-2 text-sm text-gray-700" x-text="formData.description"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
