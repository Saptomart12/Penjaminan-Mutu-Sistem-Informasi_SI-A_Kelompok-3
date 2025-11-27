<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\StreamedResponse; // <-- Impor untuk streaming

class TaskFileController extends Controller
{
    /**
     * Menyimpan file yang di-upload ke tugas.
     */
    public function store(Request $request, Task $tuga): RedirectResponse
    {
        // 1. Otorisasi
        if ($tuga->course->semester->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validasi
        $request->validate([
            'file_upload' => 'required|file|mimes:jpg,jpeg,png,bmp,pdf,doc,docx,xls,xlsx,txt,zip|max:5120', // Maks 5MB
        ]);

        $file = $request->file('file_upload');
        $originalName = $file->getClientOriginalName();
        // Simpan file ke 'storage/app/public/task_files'
        $path = $file->store('task_files', 'public');

        // 3. Simpan info file ke database
        $tuga->files()->create([
            'file_name' => $originalName,
            'file_path' => $path,
            'file_type' => $file->getClientOriginalExtension(),
        ]);

        return redirect()->route('tugas.show', $tuga->id)->with('success', 'File berhasil diunggah!');
    }

    /**
     * Menghapus file dari tugas.
     */
    public function destroy(TaskFile $taskFile): RedirectResponse
    {
        // 1. Otorisasi
        if ($taskFile->task->course->semester->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Hapus file dari storage
        Storage::disk('public')->delete($taskFile->file_path);

        // 3. Hapus record dari database
        $taskFile->delete();

        return redirect()->route('tugas.show', $taskFile->task_id)->with('success', 'File berhasil dihapus!');
    }

    /**
     * Menampilkan file preview (inline) di browser.
     */
    public function preview(TaskFile $taskFile): StreamedResponse
    {
        // 1. Otorisasi
        if ($taskFile->task->course->semester->user_id !== Auth::id()) {
            abort(403, 'AKSES DITOLAK');
        }

        // 2. Cek file
        $path = $taskFile->file_path;
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        // 3. Siapkan header 'inline'
        $name = $taskFile->file_name;
        $mime = Storage::disk('public')->mimeType($path);
        
        $headers = [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $name . '"', // 'inline' minta browser nampilin
        ];

        // 4. Stream file (lebih cepat & hemat memori)
        return Storage::disk('public')->response($path, $name, $headers);
    }
}