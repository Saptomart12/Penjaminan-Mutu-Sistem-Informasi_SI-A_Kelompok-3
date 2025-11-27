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
        $activeSemester = $user->semesters()->where('status', 'active')->first();
        
        // Inisialisasi variabel sebagai collection kosong
        $courses = collect();
        $allTasks = collect();
        $pendingTasks = collect();
        $gradedTasks = collect();
        $evaluationData = null;
        $penilaianData = null;
        
        // =========================================================
        //           INISIALISASI DATA BARU
        // =========================================================
        $visualisasiData = ['labels' => [], 'data' => []]; // Default data kosong u/ chart
        $prediksiIp = 'N/A'; // Default N/A
        // =========================================================


        // Hanya jalankan query jika ada semester yang aktif
        if ($activeSemester) {
            
            // 1. Ambil SEMUA mata kuliah di semester aktif (untuk dropdown modal & filter)
            $courses = $activeSemester->courses()->orderBy('course_name')->get();
            $courseIds = $courses->pluck('id');

            // 2. Ambil SEMUA tugas untuk semester ini
            $allTasks = Task::whereIn('course_id', $courseIds)
                            ->with('course') // Eager load relasi 'course'
                            ->orderBy('deadline', 'desc') // Urutkan terbaru dulu
                            ->get();

            // 3. Filter koleksi tugas berdasarkan status
            $pendingTasks = $allTasks->where('status', 'pending')->sortBy('deadline'); // Urutkan pending terdekat
            $gradedTasks = $allTasks->where('status', 'graded');
            
            // 4. Ambil data Evaluasi & Penilaian dari SEMESTER SEBELUMNYA (jika ada)
            $previousSemester = $user->semesters()
                                     ->where('status', 'completed')
                                     ->orderBy('semester_number', 'desc')
                                     ->first();
            if($previousSemester) {
                $evaluationData = $previousSemester->evaluation;
                $penilaianData = $evaluationData ? $evaluationData->grade_distribution : null;
            }

            // =========================================================
            //           LOGIKA BARU (FRAGMEN 4 & 5)
            // =========================================================
            
            // Cek apakah ada tugas yang sudah dinilai
            if($gradedTasks->count() > 0) {

                // 5. Logic untuk Visualisasi Chart (Fragmen 4)
                // Urutkan tugas yang dinilai berdasarkan deadline (awal ke akhir)
                $gradedTasksForChart = $gradedTasks->sortBy('deadline');
                
                $visualisasiData = [
                    // Ambil judul tugas sebagai label
                    'labels' => $gradedTasksForChart->pluck('title'), 
                    // Ambil nilai (score) sebagai data
                    'data' => $gradedTasksForChart->pluck('score'),   
                ];

                // 6. Logic untuk Prediksi IP (Fragmen 5)
                // Ini adalah perhitungan SANGAT SEDERHANA (rata-rata nilai).
                // TODO: Nanti perbaiki ini agar pakai bobot SKS mata kuliah.
                
                // Hitung rata-rata nilai (skala 0-100)
                $averageScore = $gradedTasks->avg('score');
                
                // Konversi sederhana ke skala IP 4.0
                // Asumsi 100 = 4.0, 80 = 3.2, 50 = 2.0
                $prediksiIp = number_format(($averageScore / 100) * 4.0, 2);
            }
            // =========================================================
        }

        // Kirim semua data ke view 'dashboard'
        return view('dashboard', [
            'activeSemester'  => $activeSemester,
            'courses'         => $courses,
            'allTasks'        => $allTasks,
            'pendingTasks'    => $pendingTasks,
            'gradedTasks'     => $gradedTasks,
            'evaluationData'  => $evaluationData,
            'penilaianData'   => $penilaianData,
            'visualisasiData' => $visualisasiData, // <-- Data Chart.js (BARU)
            'prediksiIp'      => $prediksiIp,       // <-- Data Prediksi IP (BARU)
        ]);
    }
}