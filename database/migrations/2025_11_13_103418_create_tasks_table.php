<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id') // Foreign key ke tabel courses
                  ->constrained()
                  ->onDelete('cascade'); // Jika mata kuliah dihapus, tugas ikut terhapus
            $table->string('title'); // Judul tugas
            $table->text('description')->nullable(); // Deskripsi tugas (boleh kosong)
            $table->dateTime('deadline'); // Tenggat waktu
            $table->enum('status', ['pending', 'completed', 'graded'])->default('pending'); // Status tugas
            $table->decimal('score', 5, 2)->nullable(); // Nilai tugas (misal: 85.50), boleh kosong
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
