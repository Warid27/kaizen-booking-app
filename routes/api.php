<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Authentication routes (public)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public route - view all bookings (read-only)
Route::get('/schedule', [BookingController::class, 'schedule']);

// Protected routes requiring authentication
Route::middleware('auth:sanctum')->group(function () {
    // Logout route (requires authentication)
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // User can get their own profile
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    
    // Bookings CRUD for authenticated users
    Route::apiResource('bookings', BookingController::class);
    
    // Rooms - index accessible to any authenticated user; other actions admin-only
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::middleware('admin')->group(function () {
        Route::apiResource('rooms', RoomController::class)->except(['index']);
    });
});
