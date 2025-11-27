<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Impor Auth
use Illuminate\View\View;            // <-- Impor View

class RiwayatController extends Controller
{
    /**
     * Menampilkan halaman riwayat semester.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Ambil SEMUA semester yang statusnya 'completed'
        $riwayatSemesters = $user->semesters()
                                ->where('status', 'completed')
                                ->orderBy('semester_number', 'desc') // Urutkan dari terbaru
                                ->with('evaluation') // Eager load data evaluasi
                                ->get();
        
        // Kirim data ke view 'riwayat.blade.php'
        return view('riwayat', ['riwayatSemesters' => $riwayatSemesters]);
    }
}