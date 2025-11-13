<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi massal.
     */
    protected $fillable = [
        'semester_id',
        'course_name',
        'course_code', // <-- TAMBAHKAN INI
        'dosen_name',  // <-- TAMBAHKAN INI
        'sks',         // <-- TAMBAHKAN INI
        'ruangan',     // <-- TAMBAHKAN INI
        'jam',         // <-- TAMBAHKAN INI
    ];

    /**
     * Mendefinisikan relasi many-to-one ke Semester.
     */
    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    /**
     * Mendefinisikan relasi one-to-many ke Task.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }
}
