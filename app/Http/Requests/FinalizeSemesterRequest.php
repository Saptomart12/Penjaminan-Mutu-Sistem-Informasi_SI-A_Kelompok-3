<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FinalizeSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            // Pastikan 'grades' adalah array
            'grades'   => 'required|array',
            // Pastikan SETIAP item di dalam 'grades' adalah angka, min 0, max 4
            'grades.*' => 'required|numeric|min:0|max:4',
        ];
    }
    
    public function messages(): array
    {
        return [
            'grades.*.required' => 'IP untuk setiap mata kuliah wajib diisi.',
            'grades.*.numeric'  => 'IP harus berupa angka.',
            'grades.*.min'      => 'IP minimal 0.00.',
            'grades.*.max'      => 'IP maksimal 4.00.',
        ];
    }
}