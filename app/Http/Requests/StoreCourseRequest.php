<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini

class StoreCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan request hanya jika user sudah login
        return Auth::check(); 
    }

  /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_name' => 'required|string|max:255',
            'semester_id' => 'required|exists:semesters,id',
            
            // --- TAMBAHKAN ATURAN INI ---
            // Kita buat 'nullable' artinya boleh dikosongkan
            'course_code' => 'nullable|string|max:50',
            'dosen_name'  => 'nullable|string|max:255',
            'sks'         => 'nullable|integer|min:0|max:10',
            'ruangan'     => 'nullable|string|max:100',
            'jam'         => 'nullable|string|max:100',
        ];
    }

    /**
     * (Opsional) Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'course_name.required' => 'Nama mata kuliah tidak boleh kosong.',
            'semester_id.required' => 'ID Semester tidak valid.',
            'semester_id.exists'   => 'Semester yang dipilih tidak ditemukan.',
        ];
    }
}