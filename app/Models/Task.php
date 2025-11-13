<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- PASTIKAN INI ADA

class Task extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'deadline',
        'status',
        'score',
    ];

    /**
     * Tipe data cast untuk kolom tertentu.
     */
    protected $casts = [
        'deadline' => 'datetime',
    ];

    /**
     * Relasi: Satu Tugas dimiliki oleh satu Mata Kuliah.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * ===============================================
     * TAMBAHKAN METHOD INI
     * ===============================================
     *
     * Mendefinisikan relasi one-to-many ke TaskFile.
     * Satu Tugas bisa memiliki banyak File.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class);
    }
}
