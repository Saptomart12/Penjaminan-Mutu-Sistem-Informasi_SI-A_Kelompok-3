<?php

// Impor semua Controller di bagian atas
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SemesterController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskFileController;
use App\Http\Controllers\RiwayatController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Diperlukan untuk closure Riwayat

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman utama langsung redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Grup Rute yang Membutuhkan Login (Authentication)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rute Resource
    Route::resource('mata-kuliah', CourseController::class);
    
    // =========================================================
    //         FOKUS PADA RUTE SEMESTER (DIRAPIKAN)
    // =========================================================
    
    // Halaman utama "Kelola Semester" (List)
    Route::get('/semester', [SemesterController::class, 'index'])->name('semester.index');
    // Menyimpan semester baru
    Route::post('/semester', [SemesterController::class, 'store'])->name('semester.store');
    // Mengaktifkan semester
    Route::put('/semester/{semester}', [SemesterController::class, 'update'])->name('semester.update');
    // Menghapus semester (jika perlu)
    Route::delete('/semester/{semester}', [SemesterController::class, 'destroy'])->name('semester.destroy');
    
    // Halaman form "Akhiri Semester"
    Route::get('/semester/finalize', [SemesterController::class, 'showFinalizeForm'])
         ->name('semester.finalize.form');
    // Memproses form "Akhiri Semester"
    Route::post('/semester/finalize', [SemesterController::class, 'finalizeSemester'])
         ->name('semester.finalize.store');
    // =========================================================

    // Rute Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

}); // Akhir dari Route::middleware('auth')->group()

// Include Rute Otentikasi Bawaan Breeze
require __DIR__.'/auth.php';