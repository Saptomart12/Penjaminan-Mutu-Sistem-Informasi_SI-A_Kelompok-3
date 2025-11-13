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
        Schema::create('semester_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('semester_id') // Foreign key ke tabel semesters
                  ->constrained()
                  ->onDelete('cascade'); // Jika semester dihapus, evaluasi ikut terhapus
            $table->text('evaluation_summary'); // Ringkasan evaluasi
            $table->json('grade_distribution'); // Distribusi nilai (format JSON)
            $table->timestamps();

            // Tambahkan unique constraint untuk memastikan 1 semester hanya punya 1 evaluasi
            $table->unique('semester_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('semester_evaluations');
    }
};
