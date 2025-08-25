<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\BookingController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\AppointmentController;

// Client side
Route::get('/', fn() => redirect()->route('booking.create'))->name('home');
Route::get('/booking', [BookingController::class, 'create'])->name('booking.create');
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/thanks', [BookingController::class, 'thanks'])->name('booking.thanks');

// Admin auth (no Breeze)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [LoginController::class, 'login'])->name('admin.login.submit');
});
Route::post('/admin/logout', [LoginController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');

// Admin panel
Route::prefix('admin')->name('admin.')->middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AppointmentController::class, 'index'])->name('dashboard');
    Route::get('/calendar/events', [AppointmentController::class, 'events'])->name('calendar.events');

    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');
});
