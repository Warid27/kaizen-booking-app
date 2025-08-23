<?php

use Illuminate\Support\Facades\Route;

// Public routes
Route::view('/', 'welcome')->name('home');
Route::view('/schedule', 'schedule.index')->name('schedule');

// Auth routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/logout', 'auth.logout')->name('logout');

// Dashboard (requires auth check in view)
Route::view('/dashboard', 'dashboard.index')->name('dashboard');

// Rooms management (admin-only, auth check in view)
Route::view('/rooms', 'rooms.index')->name('rooms.index');
Route::view('/rooms/create', 'rooms.create')->name('rooms.create');
Route::view('/rooms/{id}', 'rooms.show')->name('rooms.show');
Route::view('/rooms/{id}/edit', 'rooms.edit')->name('rooms.edit');

// Bookings management (authenticated users, auth check in view)
Route::view('/bookings', 'bookings.index')->name('bookings.index');
Route::view('/bookings/create', 'bookings.create')->name('bookings.create');
Route::view('/bookings/{id}', 'bookings.show')->name('bookings.show');
Route::view('/bookings/{id}/edit', 'bookings.edit')->name('bookings.edit');

// User Profile and Settings (authenticated users, auth check in view)
Route::view('/profile', 'profile.index')->name('profile');
Route::view('/settings', 'settings.index')->name('settings');
