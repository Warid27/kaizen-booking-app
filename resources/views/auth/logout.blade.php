{{-- resources/views/auth/logout.blade.php --}}
@extends('layouts.app')

@section('title', 'Logout - BookingApp')

@section('content')
<div class="min-h-full flex flex-col justify-center py-12 sm:px-6 lg:px-8" x-data x-init="
    $store.auth.logout();
    setTimeout(() => {
        window.location.href = '/';
    }, 2000);
">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">
                You've been logged out
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Thanks for using BookingApp. You'll be redirected to the home page shortly.
            </p>
            <div class="mt-6">
                <a href="/" class="font-medium text-blue-600 hover:text-blue-500">
                    Return to home page
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
