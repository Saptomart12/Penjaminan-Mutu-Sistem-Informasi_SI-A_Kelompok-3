@extends('layouts.app')

@section('title', 'Akhiri Semester')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Formulir Akhir Semester</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Batal
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Input Nilai Akhir Semester {{ $activeSemester->semester_number }}</h6>
        </div>
        <div class="card-body">
            <p>Silakan masukkan **Indeks Prestasi (IP)** (skala 0.00 - 4.00) untuk setiap mata kuliah di bawah ini. Data ini akan digunakan untuk menghitung IP Semester (IPS) final Anda dan menghasilkan laporan evaluasi.</p>
            
            <form action="{{ route('semester.finalize.store') }}" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Mata Kuliah</th>
                                <th>SKS</th>
                                <th width="25%">Input IP (0.00 - 4.00)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                <tr>
                                    <td>{{ $course->course_name }}</td>
                                    <td class="text-center">{{ $course->sks ?? '?' }}</td>
                                    <td>
                                        <input type="number" 
                                               step="0.01" 
                                               min="0" 
                                               max="4" 
                                               class="form-control @error('grades.'.$course->id) is-invalid @enderror" 
                                               name="grades[{{ $course->id }}]" 
                                               value="{{ old('grades.'.$course->id) }}"
                                               placeholder="Contoh: 3.50" 
                                               required>
                                        @error('grades.'.$course->id)
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada mata kuliah di semester ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tampilkan error SKS atau DB --}}
                @if (session('sks_error'))
                    <div class="alert alert-danger">{{ session('sks_error') }}</div>
                @endif
                @error('db_error')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                
                <hr>
                <div class="text-right">
                    <button type="submit" class="btn btn-danger btn-lg" 
                        {{ $courses->isEmpty() ? 'disabled' : '' }} 
                        onclick="return confirm('PERHATIAN! Anda akan mengarsipkan semester ini dan TIDAK BISA menambah/mengedit tugas lagi. Lanjutkan?')">
                        <i class="fas fa-archive mr-2"></i>
                        Selesaikan & Arsipkan Semester
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection