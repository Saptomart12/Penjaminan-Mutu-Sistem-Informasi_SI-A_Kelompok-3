<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <-- Tambahkan ini

class UpdateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan jika user sudah login (otorisasi lebih spesifik ada di controller)
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
            // Nama mata kuliah wajib diisi
            'course_name' => 'required|string|max:255',
            
            // --- TAMBAHKAN ATURAN INI (SAMA SEPERTI STORE) ---
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
        ];
    }
}