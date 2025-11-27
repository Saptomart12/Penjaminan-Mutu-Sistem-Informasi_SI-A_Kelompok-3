<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan ini
use App\Models\Course; // <-- Tambahkan ini

class StoreTaskRequest extends FormRequest
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
            // Judul tugas wajib ada, berupa teks, maksimal 255 karakter
            'title' => 'required|string|max:255',

            // Mata kuliah wajib dipilih dan ID nya harus ada di tabel 'courses'
            'course_id' => 'required|exists:courses,id',

            // Deadline wajib ada dan harus berupa format tanggal yang valid
            'deadline' => 'required|date_format:Y-m-d\TH:i,d/m/Y H:i',
        ];
    }

    /**
     * (Opsional) Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Nama tugas tidak boleh kosong.',
            'course_id.required' => 'Mata kuliah wajib dipilih.',
            'course_id.exists' => 'Mata kuliah yang dipilih tidak valid.',
            'deadline.required' => 'Tenggat waktu tidak boleh kosong.',
            'deadline.date' => 'Format tenggat waktu tidak valid.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // Validasi tambahan: Pastikan course_id milik user yang login
            $course = Course::find($this->input('course_id'));
            // Cek jika course ditemukan DAN semester course tersebut user_id nya TIDAK SAMA dengan user yg login
            if ($course && $course->semester->user_id !== Auth::id()) {
                $validator->errors()->add('course_id', 'Mata kuliah yang dipilih tidak valid untuk Anda.');
            }
        });
    }

    /**
     * Get the error bag name for the request.
     *
     * @return string
     */
    public function errorBag(): string // <-- Tambahkan method ini
    {
        // Menentukan nama error bag agar tidak bentrok dengan form lain
        return 'storeTask';
    }
}