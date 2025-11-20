<?php

namespace App\Http\Controllers;

// Models
use App\Models\Course;
use App\Models\Semester;

// Requests for Validation
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest; // Pastikan file ini sudah dibuat

// Laravel Facades & Classes
use Illuminate\Support\Facades\Auth; // Untuk mendapatkan user yang login
use Illuminate\Http\Request;         // Bisa digunakan jika UpdateRequest belum ada
use Illuminate\View\View;            // Return type hint for index()
use Illuminate\Http\RedirectResponse;// Return type hint for store(), update(), destroy()
// use Illuminate\Support\Facades\Log; // Uncomment jika perlu logging error

class CourseController extends Controller
{
    /**
     * Menampilkan daftar mata kuliah untuk semester aktif.
     * Method ini dipanggil oleh route GET /mata-kuliah.
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $user = Auth::user();
        // Cari semester aktif milik user
        $activeSemester = $user->semesters()->where('status', 'active')->first();

        $courses = []; // Default array kosong
        if ($activeSemester) {
            // Ambil mata kuliah hanya dari semester aktif, urutkan nama
            // TODO: Nanti tambahkan ->withCount('tasks') untuk kolom Jumlah Tugas
            $courses = $activeSemester->courses()->orderBy('course_name')->get();
        }

        // Kirim data ke view 'mata-kuliah.blade.php'
        return view('mata-kuliah', [
            'activeSemester' => $activeSemester,
            'courses' => $courses,
        ]);
    }

    /**
     * Menampilkan form untuk membuat mata kuliah baru.
     * (Tidak dipakai langsung karena kita pakai modal di halaman index)
     * Method ini dipanggil oleh route GET /mata-kuliah/create.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create(): RedirectResponse
    {
        // Redirect kembali ke index dengan info
        return redirect()->route('mata-kuliah.index')->with('info', 'Gunakan tombol "Tambah Mata Kuliah" pada halaman daftar.');
    }

    /**
     * Menyimpan mata kuliah baru ke database.
     * Method ini dipanggil oleh route POST /mata-kuliah.
     * @param  \App\Http\Requests\StoreCourseRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCourseRequest $request): RedirectResponse
    {
        // 1. Validasi otomatis dijalankan oleh StoreCourseRequest
        $validatedData = $request->validated();

        // 2. Pastikan semester_id milik user yang login
        $semester = Semester::where('id', $validatedData['semester_id'])
                            ->where('user_id', Auth::id())
                            ->firstOrFail(); 

        // 3. Buat course baru yang berelasi dengan semester
        //    Langsung masukkan SEMUA data yang sudah divalidasi
        try {
            $semester->courses()->create($validatedData);
        } catch (\Exception $e) {
             // Tangani jika ada error saat create (misal: nama kolom database salah)
             return redirect()->route('mata-kuliah.index')
                             ->withErrors(['error' => 'Gagal menyimpan ke database. Cek Log.']);
            // Log::error($e->getMessage()); // Jika perlu
        }


        // 4. Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('mata-kuliah.index')
                         ->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail spesifik mata kuliah.
     * (Opsional, mungkin tidak diperlukan untuk aplikasi ini)
     * Method ini dipanggil oleh route GET /mata-kuliah/{mata_kuliah}.
     * @param  \App\Models\Course  $mata_kuliah
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(Course $mata_kuliah)
    {
        // Otorisasi: Pastikan course milik user
        if ($mata_kuliah->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        // Jika butuh halaman detail, buat viewnya:
        // return view('courses.show', ['course' => $mata_kuliah]);

        // Jika tidak butuh, tampilkan 404
        abort(404);
    }

    /**
     * Menampilkan form untuk mengedit mata kuliah.
     * (Tidak dipakai langsung karena kita pakai modal di halaman index)
     * Method ini dipanggil oleh route GET /mata-kuliah/{mata_kuliah}/edit.
     * @param  \App\Models\Course  $mata_kuliah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Course $mata_kuliah): RedirectResponse
    {
         // Otorisasi: Pastikan course milik user
        if ($mata_kuliah->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }
        // Redirect kembali ke index dengan info
        return redirect()->route('mata-kuliah.index')->with('info', 'Gunakan tombol edit pada halaman daftar.');
    }

    /**
     * Memperbarui data mata kuliah di database.
     * Method ini dipanggil oleh route PUT/PATCH /mata-kuliah/{mata_kuliah}.
     * @param  \App\Http\Requests\UpdateCourseRequest  $request
     * @param  \App\Models\Course  $mata_kuliah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCourseRequest $request, Course $mata_kuliah): RedirectResponse
    {
         // Otorisasi
        if ($mata_kuliah->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // Ambil SEMUA data yang sudah lolos validasi (termasuk dosen_name, dll)
        $validatedData = $request->validated();

        try {
            // Update data mata kuliah
            $mata_kuliah->update($validatedData); // Sekarang $validatedData sudah lengkap

            return redirect()->route('mata-kuliah.index')
                             ->with('success', 'Mata kuliah berhasil diperbarui!');

        } catch (\Exception $e) {
             return redirect()->route('mata-kuliah.index')
                             ->withErrors(['error' => 'Gagal memperbarui mata kuliah. Terjadi kesalahan.']);
        }
    }

    /**
     * Menghapus mata kuliah dari database.
     * Method ini dipanggil oleh route DELETE /mata-kuliah/{mata_kuliah}.
     * @param  \App\Models\Course  $mata_kuliah
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Course $mata_kuliah): RedirectResponse
    {
        // Otorisasi: Pastikan course milik user
        if ($mata_kuliah->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        try {
            // Simpan nama untuk pesan sukses sebelum dihapus
            $courseName = $mata_kuliah->course_name;

            // Hapus mata kuliah
            // Tugas terkait akan ikut terhapus jika onDelete('cascade') ada di migrasi tasks
            $mata_kuliah->delete();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('mata-kuliah.index')
                             ->with('success', 'Mata kuliah "' . $courseName . '" berhasil dihapus!');

        } catch (\Exception $e) {
            // Log::error('Gagal menghapus mata kuliah: '. $e->getMessage()); // Uncomment jika perlu log
             // Redirect kembali dengan pesan error general
             return redirect()->route('mata-kuliah.index')
                             ->withErrors(['error' => 'Gagal menghapus mata kuliah. Terjadi kesalahan internal.']);
        }
    }
}