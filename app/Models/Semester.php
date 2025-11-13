<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasMany;   // <-- Tambahkan ini
use Illuminate\Database\Eloquent\Relations\HasOne;    // <-- Tambahkan ini

class Semester extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'user_id',
        'semester_number',
        'status',
        'final_ip',
    ];

    /**
     * Mendefinisikan relasi many-to-one ke User.
     * Satu Semester dimiliki oleh satu User.
     */
    public function user(): BelongsTo
    {
        // Semester 'belongs to' User
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke Course.
     * Satu Semester bisa memiliki banyak Mata Kuliah.
     */
    public function courses(): HasMany
    {
        // Semester 'has many' Course
        return $this->hasMany(Course::class);
    }

    /**
     * Mendefinisikan relasi one-to-one ke SemesterEvaluation.
     * Satu Semester memiliki satu Evaluasi.
     */
    public function evaluation(): HasOne
    {
        // Semester 'has one' SemesterEvaluation
        return $this->hasOne(SemesterEvaluation::class);
    }
}
