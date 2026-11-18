<?php

use App\Http\Controllers\AturanRekomendasiController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CatatanHarianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PakanController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RekomendasiController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Publik Pembeli (Tanpa Login)
Route::get('/', [BerandaController::class, 'index'])->name('beranda');
Route::post('/pesan', [BerandaController::class, 'pesan'])->name('pembeli.pesan');

// 2. Ruang Khusus Pemilik (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rekomendasi & Simulasi Biaya (IOFC)
    Route::get('/rekomendasi', [RekomendasiController::class, 'index'])->name('rekomendasi.index');
    Route::patch('/rekomendasi/{rekomendasi}/selesai', [RekomendasiController::class, 'tandaiSelesai'])->name('rekomendasi.selesai');
    Route::post('/rekomendasi/simulasi', [RekomendasiController::class, 'simulasi'])->name('rekomendasi.simulasi');

    // Catatan Harian & Riwayat Kesehatan
    Route::get('/catatan-harian', [CatatanHarianController::class, 'index'])->name('catatan.index');
    Route::post('/catatan-harian', [CatatanHarianController::class, 'store'])->name('catatan.store');
    Route::post('/riwayat-kesehatan', [CatatanHarianController::class, 'storeKesehatan'])->name('kesehatan.store');

    // Stok Pakan & Pakan Masuk
    Route::get('/stok-pakan', [PakanController::class, 'index'])->name('pakan.index');
    Route::post('/stok-pakan', [PakanController::class, 'store'])->name('pakan.store');
    Route::post('/pakan-masuk', [PakanController::class, 'storeMasuk'])->name('pakan.masuk');

    // Aturan Rekomendasi Manual
    Route::get('/aturan-rekomendasi', [AturanRekomendasiController::class, 'index'])->name('aturan.index');
    Route::post('/aturan-rekomendasi', [AturanRekomendasiController::class, 'store'])->name('aturan.store');
    Route::put('/aturan-rekomendasi/{aturan}', [AturanRekomendasiController::class, 'update'])->name('aturan.update');

    // Produk dan Harga
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');

    // Pesanan Masuk
    Route::get('/pesanan', [PesananController::class, 'index'])->name('pesanan.index');
    Route::patch('/pesanan/{pesanan}/status', [PesananController::class, 'updateStatus'])->name('pesanan.status');

    // Profil Bawaan Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';