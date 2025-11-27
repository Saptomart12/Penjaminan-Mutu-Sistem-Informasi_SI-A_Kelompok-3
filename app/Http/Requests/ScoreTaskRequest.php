<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Task; // <-- Tambahkan ini

class ScoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Izinkan jika user sudah login.
        // Otorisasi spesifik (apa ini tugas milik user) kita lakukan di controller.
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
            // Nilai wajib diisi, harus angka, minimal 0, maksimal 100
            'score' => 'required|numeric|min:0|max:100',
        ];
    }

    /**
     * Pesan error kustom.
     */
    public function messages(): array
    {
        return [
            'score.required' => 'Nilai tidak boleh kosong.',
            'score.numeric'  => 'Nilai harus berupa angka.',
            'score.min'      => 'Nilai minimal adalah 0.',
            'score.max'      => 'Nilai maksimal adalah 100.',
        ];
    }
}