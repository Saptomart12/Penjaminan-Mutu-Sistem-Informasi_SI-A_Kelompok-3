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
        Schema::table('courses', function (Blueprint $table) {
            // Tambahkan kolom-kolom baru setelah 'course_name'
            $table->string('course_code')->nullable()->after('course_name'); // Kode MK (misal: IF101)
            $table->string('dosen_name')->nullable()->after('course_code');  // Nama Dosen
            $table->integer('sks')->nullable()->after('dosen_name');       // SKS (misal: 3)
            $table->string('ruangan')->nullable()->after('sks');           // Ruangan (misal: Lab Komp 1)
            $table->string('jam')->nullable()->after('ruangan');           // Jam (misal: Senin, 08:00 - 10:30)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['course_code', 'dosen_name', 'sks', 'ruangan', 'jam']);
        });
    }
};
