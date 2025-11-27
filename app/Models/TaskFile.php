<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'file_name',
        'file_path',
        'file_type',
    ];

    // Relasi: Satu file dimiliki oleh satu tugas
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}