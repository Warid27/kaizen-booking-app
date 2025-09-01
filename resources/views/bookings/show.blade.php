{{-- resources/views/bookings/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Booking Details - KaiBook')

@section('content')

<div class="px-4 sm:px-6 lg:px-8" x-data="{
    userRole: $store.auth.role,
    bookingId: null,
    booking: null,
    loading: true,
    showCancelModal: false,
    deleting: false,
    init() {
        const parts = window.location.pathname.split('/').filter(Boolean);
        this.bookingId = parts[parts.length - 1];
        this.load();
    },
    async load() {
        try {
            const data = await window.bookingsAPI.getById(this.bookingId);
            this.booking = data;
        } catch (e) {
            const msg = window.handleApiError ? window.handleApiError(e, 'Failed to load booking') : 'Failed to load booking';
            alert(msg);
        } finally {
            this.loading = false;
        }
    },
    fmtDate(d) {
        if (!d) return '';
        const date = new Date(d);
        return date.toLocaleDateString();
    },
    fmtDateTime(d) {
        if (!d) return '';
        const date = new Date(d);
        return date.toLocaleString();
    },
    async cancelBooking() {
        try {
            this.deleting = true;
            await window.bookingsAPI.delete(this.bookingId);
            window.location.href = '/bookings';
        } catch (e) {
            const msg = window.handleApiError ? window.handleApiError(e, 'Failed to cancel booking') : 'Failed to cancel booking';
            alert(msg);
        } finally {
            this.deleting = false;
            this.showCancelModal = false;
        }
    }
}">
    <x-breadcrumbs :links="[
        ['label' => 'Home', 'href' => '/'],
        ['label' => 'Dashboard', 'href' => '/dashboard'],
        ['label' => 'Bookings', 'href' => '/bookings'],
        ['label' => 'Booking Details', 'href' => '/bookings']
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
                <p class="mt-2 text-sm text-gray-700" x-text="booking?.confirmation_code ? `Confirmation code: ${booking.confirmation_code}` : ''"></p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-3">
                <a :href="`/bookings/${bookingId}/edit`" class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                    Edit Booking
                </a>
                <button 
                    type="button"
                    @click="showCancelModal = true"
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
                            <template x-if="booking">
                                <span class="inline-flex px-2 py-1 text-sm font-semibold rounded-full"
                                      :class="{
                                        'bg-green-100 text-green-800': booking.status === 'Confirmed',
                                        'bg-yellow-100 text-yellow-800': booking.status === 'Pending',
                                        'bg-blue-100 text-blue-800': booking.status === 'Completed',
                                        'bg-red-100 text-red-800': booking.status === 'Cancelled'
                                      }"
                                      x-text="booking.status || 'Pending'">
                                </span>
                            </template>
                        </div>
                        
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Booking ID</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="`#${bookingId}`"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Confirmation Code</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="booking?.confirmation_code || '-' "></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Room</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="booking?.room?.name || '-' "></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Attendees</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="booking?.attendees ?? '-' "></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Start</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="fmtDateTime(booking?.start_time)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">End</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="fmtDateTime(booking?.end_time)"></dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Booked By</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="booking?.user?.name || '-' "></dd>
                            </div>
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Booking Date</dt>
                                <dd class="mt-1 text-sm text-gray-900" x-text="fmtDateTime(booking?.created_at)"></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Description (if any) -->
                <template x-if="booking?.description">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Description</h3>
                            <p class="text-sm text-gray-700" x-text="booking.description"></p>
                        </div>
                    </div>
                </template>

                <!-- Room Details quick view -->
                <template x-if="booking?.room">
                    <div class="bg-white shadow sm:rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Room</h3>
                            <p class="text-sm text-gray-700" x-text="booking.room.name"></p>
                            <p class="text-sm text-gray-500" x-text="`Capacity: ${booking.room.capacity}`"></p>
                        </div>
                    </div>
                </template>
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
                                :href="booking?.room?.id ? `/rooms/${booking.room.id}` : '#'"
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
                                                    <p class="text-xs text-gray-400" x-text="fmtDateTime(booking?.created_at)"></p>
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
                                                    <p class="text-xs text-gray-400" x-text="fmtDateTime(booking?.created_at)"></p>
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
                                                    <p class="text-sm text-gray-500">Start scheduled</p>
                                                    <p class="text-xs text-gray-400" x-text="fmtDate(booking?.start_time)"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Meta -->
                <div class="bg-white shadow sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Meta</h3>
                        <div class="space-y-2 text-sm text-gray-700">
                            <div>Created: <span x-text="fmtDateTime(booking?.created_at)"></span></div>
                            <div>Updated: <span x-text="fmtDateTime(booking?.updated_at)"></span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Cancel Confirmation Modal -->
    <div
        x-show="showCancelModal"
        class="relative z-10"
        aria-labelledby="modal-title" role="dialog" aria-modal="true"
        x-cloak
    >
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M4.93 4.93l14.14 14.14M12 2a10 10 0 100 20 10 10 0 000-20z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title">Cancel this booking?</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">This action cannot be undone. The booking will be permanently deleted.</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button type="button" @click="cancelBooking()" :disabled="deleting" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto disabled:opacity-50">
                            <span x-show="!deleting">Delete</span>
                            <span x-show="deleting">Deleting...</span>
                        </button>
                        <button type="button" @click="showCancelModal = false" :disabled="deleting" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto disabled:opacity-50">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
