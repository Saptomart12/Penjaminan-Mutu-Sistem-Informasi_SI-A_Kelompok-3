<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Course; // <-- Tambahkan ini

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            // Validasi sama seperti StoreTaskRequest, tapi tanpa deskripsi (karena deskripsi di hal. detail)
            'title'       => 'required|string|max:255',
            'course_id'   => 'required|exists:courses,id',
            'deadline'    => 'required|date_format:Y-m-d\TH:i,d/m/Y H:i',
            // 'description' kita edit di halaman detail, jadi tidak perlu di sini
        ];
    }

    /**
     * Cek otorisasi tambahan: Pastikan course_id milik user.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $course = Course::find($this->input('course_id'));
            if ($course && $course->semester->user_id !== Auth::id()) {
                $validator->errors()->add('course_id', 'Mata kuliah yang dipilih tidak valid.');
            }
        });
    }

    /**
     * Definisikan error bag agar tidak bentrok
     */
    public function errorBag(): string
    {
        // Kita butuh error bag dinamis berdasarkan ID tugas
        // Ambil ID dari segmen URI (misal: /tugas/5/details)
        $taskId = $this->route('tuga')->id ?? '0'; 
        return 'editTask' . $taskId; // Hasil: 'editTask5'
    }
}