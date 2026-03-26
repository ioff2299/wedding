<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\BlockController;
use App\Http\Controllers\Admin\GuestController;
use App\Http\Controllers\GeocodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RsvpController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);

// RSVP API
Route::get('/api/rsvp/{token}', [RsvpController::class, 'show']);
Route::post('/api/rsvp', [RsvpController::class, 'store']);

// Геокодирование (сервер → Яндекс HTTP API; без ограничений по Referer у JS)
Route::get('/api/geocode', [GeocodeController::class, 'lookup'])->middleware('throttle:60,1');

// Admin auth
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.post');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin panel (protected)
Route::middleware('admin.auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::put('/blocks/{key}', [BlockController::class, 'update'])->name('blocks.update');
    Route::post('/blocks/{key}/image', [BlockController::class, 'update'])->name('blocks.image');
    Route::get('/guests/export', [GuestController::class, 'export'])->name('guests.export');
    Route::get('/guests', [GuestController::class, 'index'])->name('guests.index');
});
