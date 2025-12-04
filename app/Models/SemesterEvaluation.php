<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini

class SemesterEvaluation extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'semester_id',
        'evaluation_summary',
        'grade_distribution',
    ];

    /**
     * Tipe data cast untuk kolom JSON.
     */
    protected $casts = [
        'grade_distribution' => 'array', // Otomatis konversi JSON ke array PHP
    ];


    /**
     * Mendefinisikan relasi one-to-one (inverse) ke Semester.
     * Satu Evaluasi dimiliki oleh satu Semester.
     */
    public function semester(): BelongsTo
    {
        // SemesterEvaluation 'belongs to' Semester
        return $this->belongsTo(Semester::class);
    }
}