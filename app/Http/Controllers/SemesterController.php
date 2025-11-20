<?php

namespace App\Http\Controllers;

// Models
use App\Models\Semester;
use App\Models\Course;
use App\Models\Task;

// Requests
use Illuminate\Http\Request;
use App\Http\Requests\StoreSemesterRequest;
use App\Http\Requests\FinalizeSemesterRequest;

// Facades & Classes
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Log; // Uncomment jika butuh logging

class SemesterController extends Controller
{
    /**
     * Menampilkan daftar semester milik user.
     * Mengarah ke view 'semester.index' (resources/views/semester/index.blade.php)
     */
    public function index(): View
    {
        $user = Auth::user();
        $semesters = $user->semesters()->orderBy('semester_number', 'desc')->get();

        return view('semester.index', ['semesters' => $semesters]); // <-- PERBAIKAN PATH VIEW
    }

    /**
     * Menyimpan semester baru.
     */
    public function store(StoreSemesterRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        try {
            DB::transaction(function () use ($user, $validatedData) {
                $user->semesters()->where('status', 'active')->update(['status' => 'completed']);
                $user->semesters()->create([
                    'semester_number' => $validatedData['semester_number'],
                    'status' => 'active',
                ]);
            });
            return redirect()->route('semester.index')->with('success', 'Semester baru berhasil ditambahkan dan diaktifkan!');
        } catch (\Exception $e) {
            // Log::error('Gagal tambah semester: ' . $e->getMessage());
            return redirect()->route('semester.index')->withErrors(['error' => 'Gagal menambahkan semester. Terjadi kesalahan.']);
        }
    }

    /**
     * Memperbarui semester (misal: mengaktifkan).
     */
    public function update(Request $request, Semester $semester): RedirectResponse
    {
        if ($semester->user_id !== Auth::id()) {
            abort(403);
        }

        if ($request->input('action') === 'activate') {
             try {
                DB::transaction(function () use ($semester) {
                    Auth::user()->semesters()->where('id', '!=', $semester->id)
                                ->update(['status' => 'completed']);
                    $semester->update(['status' => 'active']);
                });
                return redirect()->route('semester.index')->with('success', 'Semester '.$semester->semester_number.' berhasil diaktifkan!');
            } catch (\Exception $e) {
                // Log::error('Gagal aktivasi semester: ' . $e->getMessage());
                 return redirect()->route('semester.index')->withErrors(['error' => 'Gagal mengaktifkan semester. Terjadi kesalahan.']);
            }
        }
        return redirect()->route('semester.index');
    }

    /**
     * Menampilkan form untuk mengakhiri semester (input nilai).
     * Mengarah ke view 'semester.finalize' (resources/views/semester/finalize.blade.php)
     */
    public function showFinalizeForm(): View | RedirectResponse
    {
        $user = Auth::user();
        $activeSemester = $user->semesters()->where('status', 'active')->first(); 

        // JIKA TIDAK ADA SEMESTER AKTIF, kembalikan ke dashboard dengan pesan error
        if (!$activeSemester) {
            return redirect()->route('dashboard')
                             ->withErrors(['no_active_semester' => 'Tidak ada semester aktif. Silakan aktifkan semester terlebih dahulu di halaman "Kelola Semester".']);
        }
        
        $courses = $activeSemester->courses()->orderBy('course_name')->get();

        return view('semester.finalize', [ // <-- PERBAIKAN PATH VIEW
            'activeSemester' => $activeSemester,
            'courses' => $courses
        ]);
    }

    /**
     * Memproses data nilai akhir, mengarsipkan semester, dan membuat evaluasi.
     */
    public function finalizeSemester(FinalizeSemesterRequest $request): RedirectResponse
    {
        $validatedGrades = $request->validated()['grades'];
        $user = Auth::user();
        $activeSemester = $user->semesters()->where('status', 'active')->firstOrFail();
        
        $totalSks = 0;
        $totalBobot = 0;
        $courseIds = array_keys($validatedGrades);
        $courses = Course::whereIn('id', $courseIds)->where('semester_id', $activeSemester->id)->get();

        // --- 1. Kalkulasi IP Semester (IPS) ---
        foreach ($courses as $course) {
            $sks = $course->sks;
            $grade = $validatedGrades[$course->id];

            if (empty($sks) || $sks == 0) {
                 return redirect()->back()
                    ->withInput()
                    ->with('sks_error', 'Gagal hitung IP: Mata kuliah "' . $course->course_name . '" belum diisi SKS. Harap edit di halaman Mata Kuliah.');
            }
            $totalSks += $sks;
            $totalBobot += ($grade * $sks);
        }

        $finalIpSemester = ($totalSks > 0) ? ($totalBobot / $totalSks) : 0;

        // --- 2. Siapkan Data Evaluasi (dari tugas) ---
        $gradedTasks = Task::whereIn('course_id', $courseIds)
                           ->where('status', 'graded')
                           ->get();

        $gradeDistribution = [ 'Sangat Baik' => 0, 'Baik' => 0, 'Cukup' => 0, 'Kurang' => 0 ];
        foreach ($gradedTasks as $task) {
            if ($task->score >= 85) $gradeDistribution['Sangat Baik']++;
            elseif ($task->score >= 70) $gradeDistribution['Baik']++;
            elseif ($task->score >= 55) $gradeDistribution['Cukup']++;
            else $gradeDistribution['Kurang']++;
        }
        
        $summary = "Semester ini Anda menyelesaikan " . $gradedTasks->count() . " tugas. Kinerja terbaik Anda ada pada tugas dengan nilai 'Sangat Baik' (" . $gradeDistribution['Sangat Baik'] . " tugas).";

        // --- 3. Jalankan Transaksi Database ---
        try {
            DB::transaction(function () use ($activeSemester, $finalIpSemester, $summary, $gradeDistribution) {
                $activeSemester->update([
                    'status' => 'completed',
                    'final_ip' => $finalIpSemester,
                ]);
                $activeSemester->evaluation()->create([
                    'evaluation_summary' => $summary,
                    'grade_distribution' => $gradeDistribution, // Eloquent otomatis handle JSON
                ]);
            });
        } catch (\Exception $e) {
            // Log::error('Gagal arsipkan semester: ' . $e->getMessage());
            return redirect()->back()->withErrors(['db_error' => 'Gagal menyimpan data. Terjadi kesalahan database.']);
        }

        // --- 4. Selesai & Redirect ---
        return redirect()->route('riwayat')
                         ->with('success', 'Semester ' . $activeSemester->semester_number . ' berhasil diarsipkan dengan IPS: ' . number_format($finalIpSemester, 2));
    }
    
    
    // --- Method Resource yang Tidak Dipakai ---
    
    public function create() { return redirect()->route('semester.index'); }
    public function show(Semester $semester) { abort(404); }
    public function edit(Semester $semester) { return redirect()->route('semester.index'); }
    
    public function destroy(Semester $semester)
    {
        if ($semester->user_id !== Auth::id()) { abort(403); }
        // $semester->delete(); // Hati-hati, cascade delete
        // return redirect()->route('semester.index')->with('success', 'Semester berhasil dihapus.');
        abort(404); // Nonaktifkan fitur hapus untuk saat ini
    }
}