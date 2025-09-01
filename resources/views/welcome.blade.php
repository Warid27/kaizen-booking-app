{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('title', 'Welcome to KaiBook')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Hero Section -->
    <div class="text-center py-12">
        <h1 class="text-4xl font-bold text-gray-900 sm:text-6xl">
            Welcome to <span class="text-blue-600">KaiBook</span>
        </h1>
        <p class="mt-6 text-lg leading-8 text-gray-600 max-w-2xl mx-auto">
            Your complete room booking solution. Manage rooms, handle reservations, and streamline your hospitality operations with ease.
        </p>
        
        <!-- Action Buttons -->
        <div class="mt-10 flex items-center justify-center gap-x-6">
            <a href="/schedule" class="inline-flex items-center space-x-2 rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-all duration-200 hover:scale-105 hover:shadow-lg focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                <i data-lucide="calendar-clock" class="h-4 w-4"></i>
                <span>View Schedule</span>
            </a>
            <template x-if="$store.auth.isAuthed">
                <a href="/dashboard" class="inline-flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-lg font-semibold transition-all duration-200 hover:scale-105 hover:shadow-lg">
                    <i data-lucide="layout-dashboard" class="h-5 w-5"></i>
                    <span>Go to Dashboard</span>
                </a>
            </template>
            <template x-if="!$store.auth.isAuthed">
                <a href="/login" class="inline-flex items-center space-x-2 text-sm font-semibold leading-6 text-gray-900 hover:text-blue-600 transition-colors duration-200">
                    <span>Get Started</span>
                    <i data-lucide="arrow-right" class="h-4 w-4"></i>
                </a>
            </template>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="py-12">
        <div class="mx-auto max-w-7xl">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-blue-600">Everything you need</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                    Powerful booking management
                </p>
            </div>
            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <div class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-8 lg:max-w-none lg:grid-cols-3">
                    <x-card>
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="flex-shrink-0 p-2 bg-blue-100 rounded-lg">
                                    <i data-lucide="bed-double" class="h-6 w-6 text-blue-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold leading-7 text-gray-900">Room Management</h3>
                            </div>
                            <p class="text-base leading-7 text-gray-600">Easily manage your rooms, set pricing, and track availability in real-time with our intuitive dashboard.</p>
                        </div>
                    </x-card>

                    <x-card>
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="flex-shrink-0 p-2 bg-green-100 rounded-lg">
                                    <i data-lucide="calendar-check" class="h-6 w-6 text-green-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold leading-7 text-gray-900">Smart Booking</h3>
                            </div>
                            <p class="text-base leading-7 text-gray-600">Streamlined booking process with automated confirmations and calendar integration for seamless operations.</p>
                        </div>
                    </x-card>

                    <x-card>
                        <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center gap-x-3 mb-4">
                                <div class="flex-shrink-0 p-2 bg-purple-100 rounded-lg">
                                    <i data-lucide="bar-chart-3" class="h-6 w-6 text-purple-600"></i>
                                </div>
                                <h3 class="text-lg font-semibold leading-7 text-gray-900">Analytics & Reports</h3>
                            </div>
                            <p class="text-base leading-7 text-gray-600">Comprehensive analytics and reporting to help you make data-driven decisions and optimize your business.</p>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </div>

    
</div>
@endsection
