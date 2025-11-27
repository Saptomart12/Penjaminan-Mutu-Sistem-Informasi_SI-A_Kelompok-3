<?php

namespace App\Http\Controllers;

// Models
use App\Models\Task;
use App\Models\Course;

// Requests for Validation
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\ScoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest; // Pastikan file ini sudah dibuat

// Laravel Facades & Classes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
// use Illuminate\Support\Facades\Log; // Uncomment jika perlu logging error

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas (kita pakai Dashboard, jadi redirect).
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Menampilkan form tambah (kita pakai Modal, jadi redirect).
     */
    public function create()
    {
         return redirect()->route('dashboard')->with('info', 'Gunakan tombol "Tambah Tugas" di Dashboard.');
    }

    /**
     * Menyimpan tugas baru (dari Modal Tambah Tugas).
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        
        // Otorisasi (double check)
        $course = Course::findOrFail($validatedData['course_id']);
        if ($course->semester->user_id !== Auth::id()) {
            abort(403);
        }

        // --- Konversi format tanggal (Penting!) ---
        try {
            // Coba parse format HTML5 default
            $deadline = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['deadline']);
        } catch (\Exception $e) {
            // Jika gagal, coba parse format manual (d/m/Y H:i)
            try {
                 $deadline = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $validatedData['deadline']);
            } catch (\Exception $e2) {
                // Fallback jika format lain
                $deadline = \Carbon\Carbon::parse($validatedData['deadline']);
            }
        }
        // Simpan format standar database
        $validatedData['deadline'] = $deadline->format('Y-m-d H:i:s');
        // ------------------------------------

        // Tambahkan deskripsi kosong (sesuai alur baru kita)
        $validatedData['description'] = ''; 

        // Buat tugas
        Task::create($validatedData);

        return redirect()->route('dashboard')
                         ->with('success', 'Tugas baru berhasil ditambahkan!');
    }

    /**
     * Menampilkan halaman detail tugas.
     */
    public function show(Task $tuga): View // Parameter $tuga (sesuai route resource 'tugas')
    {
        // Otorisasi: Pastikan tugas ini milik user
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403);
        }

        // Eager load relasi files DAN course (untuk info dosen)
        $tuga->load('files', 'course'); 

        return view('tugas.show', [
            'task' => $tuga // Kirim data tugas ke view
        ]);
    }

    /**
     * Menampilkan form edit (kita pakai Modal, jadi redirect).
     */
    public function edit(Task $tuga) // <-- Parameter disamakan jadi $tuga
    {
         return redirect()->route('dashboard')->with('info', 'Gunakan tombol edit di Dashboard.');
    }

    /**
     * Memperbarui data tugas (Untuk Modal Input Nilai ✓).
     */
    public function update(ScoreTaskRequest $request, Task $tuga): RedirectResponse
    {
        // Otorisasi
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        $validatedData = $request->validated();

        try {
            $tuga->update([
                'status' => 'graded',
                'score'  => $validatedData['score']
            ]);

            return redirect()->route('dashboard')
                             ->with('success', 'Tugas "' . $tuga->title . '" berhasil dinilai!');
        } catch (\Exception $e) {
             return redirect()->route('dashboard')
                             ->withErrors(['error' => 'Gagal memperbarui tugas. Terjadi kesalahan.']);
        }
    }

    // ===============================================
    //           METHOD BARU (UPDATE DETAIL TUGAS)
    // ===============================================
    /**
     * Update detail tugas (Judul, Deadline, MK) dari modal edit ✏️.
     * Dipanggil oleh route PATCH /tugas/{tuga}/details
     */
    public function updateDetails(UpdateTaskRequest $request, Task $tuga): RedirectResponse
    {
        // 1. Otorisasi (double check)
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // 2. Ambil data yang sudah divalidasi
        $validatedData = $request->validated();
        
        // 3. Konversi format tanggal
        try {
            $deadline = \Carbon\Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['deadline']);
        } catch (\Exception $e) {
            try {
                $deadline = \Carbon\Carbon::createFromFormat('d/m/Y H:i', $validatedData['deadline']);
            } catch (\Exception $e2) {
                $deadline = \Carbon\Carbon::parse($validatedData['deadline']);
            }
        }
        $validatedData['deadline'] = $deadline->format('Y-m-d H:i:s');
        
        try {
            // 4. Update data tugas
            $tuga->update($validatedData);

            // 5. Redirect kembali ke dashboard dengan pesan sukses
            return redirect()->route('dashboard')
                             ->with('success', 'Tugas "' . $tuga->title . '" berhasil di-edit!');

        } catch (\Exception $e) {
             // Kirim error ke error bag yang spesifik
             return redirect()->route('dashboard')
                             ->withErrors(['error' => 'Gagal mengedit tugas. Terjadi kesalahan.'], 'editTask' . $tuga->id); 
        }
    }

    /**
     * Memperbarui HANYA deskripsi tugas (dari Halaman Detail).
     */
    public function updateDescription(Request $request, Task $tuga): RedirectResponse
    {
        // 1. Otorisasi
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validasi (simpel)
        $validatedData = $request->validate([
            'description' => 'nullable|string',
        ]);

        // 3. Update deskripsi
        $tuga->update([
            'description' => $validatedData['description']
        ]);

        // 4. Redirect kembali ke halaman detail
        return redirect()->route('tugas.show', $tuga->id)
                         ->with('success', 'Deskripsi tugas berhasil diperbarui!');
    }


    /**
     * Menghapus tugas.
     */
    public function destroy(Task $tuga): RedirectResponse
    {
        // Otorisasi
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        try {
            $courseName = $tuga->title;

            // Hapus semua file terkait di storage (jika ada)
            foreach ($tuga->files as $file) {
                Storage::disk('public')->delete($file->file_path);
            }
            
            // Hapus tugas (record file di DB akan ikut terhapus via onDelete cascade)
            $tuga->delete(); 

            return redirect()->route('dashboard')
                             ->with('success', 'Tugas "' . $courseName . '" berhasil dihapus!');

        } catch (\Exception $e) {
             return redirect()->route('dashboard')
                             ->withErrors(['error' => 'Gagal menghapus tugas. Terjadi kesalahan.']);
        }
    }
}