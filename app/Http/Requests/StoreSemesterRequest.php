<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <-- Tambahkan ini

class StoreSemesterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $userId = Auth::id(); // Dapatkan ID user yang login

        return [
            // semester_number wajib, angka, minimal 1,
            // dan unik untuk user_id tersebut (tidak boleh ada semester 5 dua kali)
            'semester_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('semesters')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
        ];
    }

     public function messages(): array
    {
        return [
            'semester_number.required' => 'Nomor semester wajib diisi.',
            'semester_number.integer' => 'Nomor semester harus berupa angka.',
            'semester_number.min' => 'Nomor semester minimal adalah 1.',
            'semester_number.unique' => 'Nomor semester ini sudah pernah ditambahkan.',
        ];
    }
}