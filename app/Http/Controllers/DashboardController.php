<?php

namespace App\Http\Controllers;

// Impor semua model dan class yang kita butuhkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Semester;
use App\Models\Task;
use App\Models\Course;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard utama.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Kirim semua data ke view 'dashboard'
        return view('dashboard', []);
    }
}