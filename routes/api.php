<?php

use App\Http\Controllers\BookingsController;
use App\Models\Bookings;
use Illuminate\Support\Facades\Route;

Route::get("booking-schedule", [BookingsController::class, 'bookingSchedule']);