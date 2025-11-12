<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes (Versi Uji Coba Login)
|--------------------------------------------------------------------------
*/

// 1. Halaman utama (/) akan langsung redirect ke halaman login.
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Grup Rute yang Membutuhkan Login (Authentication)
Route::middleware(['auth'])->group(function () {

    // Rute Dashboard (SIMPLE)
    // Langsung return view 'dashboard' tanpa controller.
    Route::get('/dashboard', function () {
        // Halaman ini HANYA AKAN TAMPIL JIKA SUDAH LOGIN
        return view('dashboard'); 
    })->name('dashboard');

    // Rute Profil (wajib ada untuk tombol profile di topbar)
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [\App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Akhir dari Route::middleware('auth')->group()


// 3. Include Rute Otentikasi Bawaan Breeze
// Ini akan mengurus GET /login, POST /login, GET /register, POST /register, dll.
require __DIR__.'/auth.php';