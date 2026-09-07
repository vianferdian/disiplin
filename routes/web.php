<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankDataProxyController;
use App\Http\Controllers\BankDataSyncController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisPelanggaranController;
use App\Http\Controllers\KategoriPelanggaranController;
use App\Http\Controllers\PelanggaranSiswaController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pencatatan & Data Pelanggaran Siswa (Wakasek & Admin)
    Route::get('/pelanggaran-siswa', [PelanggaranSiswaController::class, 'index'])->name('pelanggaran-siswa.index');
    Route::get('/pelanggaran-siswa/create', [PelanggaranSiswaController::class, 'create'])->name('pelanggaran-siswa.create');
    Route::post('/pelanggaran-siswa', [PelanggaranSiswaController::class, 'store'])->name('pelanggaran-siswa.store');
    Route::get('/pelanggaran-siswa/report', [PelanggaranSiswaController::class, 'report'])->name('pelanggaran-siswa.report');
    Route::get('/pelanggaran-siswa/print', [PelanggaranSiswaController::class, 'printReport'])->name('pelanggaran-siswa.print');
    Route::get('/pelanggaran-siswa/{pelanggaranSiswa}/print-single', [PelanggaranSiswaController::class, 'printSingle'])->name('pelanggaran-siswa.print-single');
    Route::get('/pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranSiswaController::class, 'show'])->name('pelanggaran-siswa.show');
    Route::delete('/pelanggaran-siswa/{pelanggaranSiswa}', [PelanggaranSiswaController::class, 'destroy'])->name('pelanggaran-siswa.destroy');

    // Integrasi & Sync Bank Data API
    Route::get('/bank-data', [BankDataSyncController::class, 'index'])->name('bank-data.index');
    Route::get('/bank-data/test', [BankDataSyncController::class, 'testConnection'])->name('bank-data.test');
    Route::post('/bank-data/sync', [BankDataSyncController::class, 'syncNow'])->name('bank-data.sync');

    // AJAX Proxy API for Bank Data
    Route::get('/api-proxy/kelas', [BankDataProxyController::class, 'getKelas'])->name('api.kelas');
    Route::get('/api-proxy/siswa', [BankDataProxyController::class, 'getSiswa'])->name('api.siswa');

    // Master Data Management (Admin Only)
    Route::middleware(['can:admin-access'])->group(function () {
        Route::resource('kategori-pelanggaran', KategoriPelanggaranController::class)->except(['create', 'edit', 'show']);
        Route::resource('jenis-pelanggaran', JenisPelanggaranController::class)->except(['create', 'edit', 'show']);
        Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
        
        // Pengaturan TTD Wakasek
        Route::get('/settings/wakasek', [SettingController::class, 'editWakasek'])->name('settings.wakasek');
        Route::post('/settings/wakasek', [SettingController::class, 'updateWakasek'])->name('settings.wakasek.update');
    });
});

