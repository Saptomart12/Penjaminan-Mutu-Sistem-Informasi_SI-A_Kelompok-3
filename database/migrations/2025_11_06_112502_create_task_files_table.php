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
        Schema::create('task_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->string('file_name'); // Nama asli file, misal "Esai.docx"
            $table->string('file_path'); // Path file di storage, misal "task_files/abc123.docx"
            $table->string('file_type')->nullable(); // misal: 'pdf', 'jpg', 'docx'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_files');
    }
};
