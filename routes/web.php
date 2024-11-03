<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PrivilageController;
use App\Http\Controllers\Backend\TransaksiController;
use App\Http\Controllers\Backend\SatuanController;
use App\Http\Controllers\Backend\BarangController;

// Route::get('/', function () {
//     return ['Laravel' => app()->version()];
// });
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [Backend::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [Backend::class, 'profile'])->name('profile.edit');
});

Route::get('/', [Backend::class, 'signin'])->name('signin');
Route::get('/get-role-name', [PrivilageController::class, 'getRoleName'])->name('get.role.name');
Route::get('/transaksi/print/{id}', [TransaksiController::class, 'print'])->name('transaksi.print');
Route::post('/clear-session', [TransaksiController::class, 'clearSession'])->name('clear.session');
Route::get('/transaksi/laporan', [TransaksiController::class, 'laporan'])->name('transaksi.laporan');
Route::get('/transaksi/laporan/cetak', [TransaksiController::class, 'cetakLaporan'])->name('transaksi.laporan.cetak');
Route::get('/company-profile', [Backend::class, 'editCompany'])->name('companyProfile');
Route::put('/company-profile/update', [Backend::class, 'updateCompany'])->name('companyProfile.update');

Route::middleware(['auth'])->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('user', UserController::class); //user
    Route::get('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::resource('privilage', PrivilageController::class); //privilage
    Route::resource('transaksi', TransaksiController::class); //transaksi
    Route::resource('satuan', SatuanController::class); //satuan
    Route::resource('barang', BarangController::class); //barang
});

require __DIR__.'/auth.php';
