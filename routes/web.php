<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PrivilageController;

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [Backend::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [Backend::class, 'profile'])->name('profile.edit');
});

Route::get('/', [Backend::class, 'signin'])->name('signin');
Route::get('/get-role-name', [PrivilageController::class, 'getRoleName'])->name('get.role.name');

Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('user', UserController::class); //user
    Route::get('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::resource('privilage', PrivilageController::class); //privilage
});

require __DIR__.'/auth.php';
