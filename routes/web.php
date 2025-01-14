<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\PrivilageController;
use App\Http\Controllers\Backend\TransaksiController;
use App\Http\Controllers\Backend\SatuanController;
use App\Http\Controllers\Backend\BarangController;
use App\Http\Controllers\Backend\PurchaseOrderController;
use App\Http\Controllers\Backend\BarangMasukController;
use App\Http\Controllers\Backend\BarangKeluarController;
use App\Http\Controllers\Backend\BarangBrokenController;
use App\Http\Controllers\Backend\BarangTemplateController;
use App\Http\Controllers\Backend\CustomerController;

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

Route::get('/company-profile', [Backend::class, 'editCompany'])->name('companyProfile');
Route::put('/company-profile/update', [Backend::class, 'updateCompany'])->name('companyProfile.update');
Route::delete('satuan/{satuan}', [SatuanController::class, 'destroy'])->name('satuan.destroy');
Route::delete('barang/{barang}', [BarangController::class, 'destroy'])->name('barang.destroy');
Route::post('barang/import', [BarangController::class, 'import'])->name('barang.import');
Route::get('barang/template', [BarangController::class, 'downloadTemplate'])->name('barang.template');
Route::post('/barang/destroy/{barang?}', [BarangController::class, 'destroy'])->name('barang.destroy');

Route::delete('/purchase_order/{PurchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase_order.destroy');
Route::post('purchase_order/{id}/approve', [PurchaseOrderController::class, 'approve'])->name('purchase_order.approve');
Route::get('/purchase-order/{id}/print', [PurchaseOrderController::class, 'print'])->name('purchase_order.print');
Route::post('/barang_masuk/process', [BarangMasukController::class, 'process'])->name('barang_masuk.process');
Route::get('/barang-keluar/{id}/print', [BarangKeluarController::class, 'print'])->name('barang_keluar.print');
Route::get('/barang-broken/{id}/print', [BarangBrokenController::class, 'print'])->name('barang_broken.print');
Route::get('/barang-template/{id}/print', [BarangTemplateController::class, 'print'])->name('barang_template.print');
// Route to get barang template data
Route::get('barang-template/{id}', [BarangKeluarController::class, 'getBarangTemplateData'])->name('barang_template.get_data');

// export data
Route::get('/barang/export', [BarangController::class, 'export'])->name('barang.export');
Route::get('/satuan/export', [SatuanController::class, 'export'])->name('satuan.export');
Route::get('/customer/export', [CustomerController::class, 'export'])->name('customer.export');
Route::get('/transaksi/laporan/cetak', [TransaksiController::class, 'cetakLaporan'])->name('transaksi.laporan.cetak');

Route::middleware(['auth'])->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('user', UserController::class); //user
    Route::get('/user/{id}/reset-password', [UserController::class, 'resetPassword'])->name('user.resetPassword');
    Route::resource('privilage', PrivilageController::class); //privilage
    Route::resource('satuan', SatuanController::class); //satuan
    Route::resource('barang', BarangController::class); //barang
    Route::resource('purchase_order', PurchaseOrderController::class); //purchase_order

    Route::resource('barang_masuk', BarangMasukController::class); //barang_masuk
    Route::resource('barang_keluar', BarangKeluarController::class); //barang_keluar
    Route::resource('barang_broken', BarangBrokenController::class); //barang_broken
    Route::resource('barang_template', BarangTemplateController::class); //barang_template
    Route::resource('customer', CustomerController::class); //customer
});

require __DIR__.'/auth.php';
