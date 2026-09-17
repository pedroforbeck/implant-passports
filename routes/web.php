<?php

use App\Http\Controllers\CheckupController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::resource('manufacturers', ManufacturerController::class)->except('show');
        Route::resource('users', UserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

    Route::resource('patients', PatientController::class)->except('destroy');
    Route::resource('patients.devices', DeviceController::class)->only(['create', 'store']);
    Route::resource('devices', DeviceController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::resource('devices.checkups', CheckupController::class)->except('show')->scoped(['checkup' => 'device_id']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
