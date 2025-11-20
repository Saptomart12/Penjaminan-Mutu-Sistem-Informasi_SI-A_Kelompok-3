@extends('layouts.app')

@section('title', 'Kelola Semester')

@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Kelola Semester</h1>
        {{-- Tombol Tambah Semester (panggil modal) --}}
        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahSemesterModal">
            <i class="fas fa-plus fa-sm mr-1"></i> Tambah Semester Baru
        </button>
    </div>

    {{-- Alert Sukses/Error --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
        </div>
    @endif
    @if ($errors->any())
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
             Gagal memproses. Silakan periksa input Anda.
             <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
             </ul>
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
         </div>
     @endif

    {{-- Tabel Daftar Semester --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-list-ul mr-2"></i> Daftar Semester Anda</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="semesterTable" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Semester</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">IP Semester</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Data semester akan dilooping di sini --}}
                        @forelse ($semesters as $semester)
                        <tr class="{{ $semester->status == 'active' ? 'table-success font-weight-bold' : '' }}">
                            <td class="text-center">{{ $semester->semester_number }}</td>
                            <td class="text-center">
                                @if ($semester->status == 'active')
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-secondary">Selesai</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $semester->final_ip ? number_format($semester->final_ip, 2) : '-' }}</td>
                            <td class="text-center">
                                @if ($semester->status == 'completed')
                                    {{-- Form untuk Aktifkan Semester --}}
                                    <form action="{{ route('semester.update', $semester->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Aktifkan semester ini? Semester lain akan otomatis Selesai.');">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="activate"> {{-- Tandai aksi --}}
                                        <button type="submit" class="btn btn-success btn-sm" title="Aktifkan Semester Ini">
                                            <i class="fas fa-check"></i> Aktifkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">Sedang Aktif</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data semester. Klik "Tambah Semester Baru" untuk memulai.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Semester --}}
<div class="modal fade" id="tambahSemesterModal" tabindex="-1" role="dialog" aria-labelledby="tambahSemesterModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahSemesterModalLabel"><i class="fas fa-plus-circle mr-2"></i> Tambah Semester Baru</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            </div>
            <form action="{{ route('semester.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="semester_number" class="font-weight-bold">Nomor Semester</label>
                        <input type="number" class="form-control @error('semester_number') is-invalid @enderror" id="semester_number" name="semester_number" value="{{ old('semester_number') }}" placeholder="Contoh: 1" required min="1">
                         @error('semester_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                    </div>
                     <p class="small text-muted">Semester baru yang ditambahkan akan otomatis menjadi semester aktif, dan semester aktif sebelumnya (jika ada) akan ditandai Selesai.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"> <i class="fas fa-save mr-1"></i> Simpan & Aktifkan </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection