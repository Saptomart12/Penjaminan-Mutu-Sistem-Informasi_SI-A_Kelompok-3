<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
// PENTING: Tambahkan Controller Semester di sini agar route di bawah terbaca
use App\Http\Controllers\SemesterController; 

/*
|--------------------------------------------------------------------------
| Web Routes (Proyek Clone - Integrasi Fitur Semester)
|--------------------------------------------------------------------------
*/

// 1. Halaman utama (/) akan langsung redirect ke halaman login.
Route::get('/', function () {
    return redirect()->route('login');
});

// 2. Grup Rute yang Membutuhkan Login (Authentication)
Route::middleware(['auth'])->group(function () {

    // Rute Dashboard (Masih versi simpel/closure sesuai proyek clone)
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('dashboard');

    // =========================================================
    //           FITUR KELOLA SEMESTER (BARU DITAMBAHKAN)
    // =========================================================
    
    // Menampilkan daftar semester
    Route::get('/semester', [SemesterController::class, 'index'])->name('semester.index');
    
    // Menyimpan semester baru
    Route::post('/semester', [SemesterController::class, 'store'])->name('semester.store');
    
    // Mengupdate status/data semester
    Route::put('/semester/{semester}', [SemesterController::class, 'update'])->name('semester.update');
    
    // Menghapus semester
    Route::delete('/semester/{semester}', [SemesterController::class, 'destroy'])->name('semester.destroy');
    
    // Halaman form "Akhiri Semester" (Evaluasi)
    Route::get('/semester/finalize', [SemesterController::class, 'showFinalizeForm'])
         ->name('semester.finalize.form');
         
    // Memproses "Akhiri Semester"
    Route::post('/semester/finalize', [SemesterController::class, 'finalizeSemester'])
         ->name('semester.finalize.store');

    // =========================================================

    // Rute Profil (Bawaan)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Akhir dari Route::middleware('auth')->group()


// 3. Include Rute Otentikasi Bawaan Breeze
require __DIR__.'/auth.php';