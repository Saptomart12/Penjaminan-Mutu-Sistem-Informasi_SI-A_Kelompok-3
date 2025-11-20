{{-- Menggunakan layout induk dari layouts/app.blade.php --}}
@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Kelola Mata Kuliah & Jadwal')

{{-- Memasukkan konten utama --}}
@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Mata Kuliah & Jadwal</h1>
            <p class="mb-0 text-gray-600">Kelola mata kuliah dan lihat jadwal Anda untuk semester ini.</p>
        </div>
        {{-- Tampilkan nomor semester aktif --}}
        <span class="badge badge-pill badge-primary px-3 py-2">
            Semester Aktif: {{ $activeSemester ? $activeSemester->semester_number : 'Belum Ditentukan' }}
        </span>
    </div>

    {{-- ALERT UNTUK PESAN SUKSES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

     {{-- ALERT UNTUK ERROR VALIDASI UMUM (jika perlu) --}}
     {{-- Cek error bag default, bukan error bag modal edit/delete --}}
     @if ($errors->any() && !$errors->hasBag('updateForm') && !$errors->hasBag('deleteForm'))
         <div class="alert alert-danger alert-dismissible fade show" role="alert">
             Gagal memproses. Silakan periksa input Anda pada form yang relevan.
             <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
     @endif


    {{-- Bagian Daftar Mata Kuliah --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-book-open mr-2"></i> Mata Kuliah Terdaftar Semester Ini</h6>
                    {{-- Tombol Tambah hanya muncul jika ada semester aktif --}}
                    @if($activeSemester)
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahMkModal">
                            <i class="fas fa-plus fa-sm mr-1"></i> Tambah Mata Kuliah
                        </button>
                    @else
                         <span class="text-danger small">Silakan aktifkan semester terlebih dahulu untuk menambah mata kuliah.</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped" id="mataKuliahTable" width="100%" cellspacing="0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Nama Mata Kuliah</th>
                                    <th class="text-center">Jumlah Tugas</th> {{-- Nanti akan kita buat dinamis --}}
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Loop data $courses dari controller --}}
                                @forelse ($courses as $course) {{-- Hapus $index --}}
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}.</td> {{-- Gunakan $loop->iteration --}}
                                    <td>{{ $course->course_name }}</td>
                                    {{-- TODO: Hitung jumlah tugas nanti pakai relasi $course->tasks_count --}}
                                    <td class="text-center">0</td>
                                    <td class="text-center">
                                        {{-- Tombol Edit: Target modal ditambah ID course --}}
                                        <button class="btn btn-info btn-circle btn-sm" title="Edit" data-toggle="modal" data-target="#editModal{{ $course->id }}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </button>
                                        {{-- Tombol Hapus: Target modal ditambah ID course --}}
                                        <button class="btn btn-danger btn-circle btn-sm" title="Hapus" data-toggle="modal" data-target="#deleteModal{{ $course->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                {{-- Pesan jika $courses kosong --}}
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Belum ada mata kuliah ditambahkan untuk semester ini.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel Jadwal --}}
    <hr class="my-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-calendar-alt mr-2"></i> Jadwal Mata Kuliah Semester Ini</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="jadwalTable" width="100%" cellspacing="0">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th>Kode MK</th>
                                    <th>Mata Kuliah</th>
                                    <th>Dosen Pengampu</th>
                                    <th class="text-center">SKS</th>
                                    <th class="text-center">Ruangan</th>
                                    <th>Jam</th>
                                </tr>
                            </thead>
                            {{-- ========================================================= --}}
                            {{--                 PERUBAHAN KODE DI SINI                    --}}
                            {{-- ========================================================= --}}
                            <tbody>
                                {{-- Loop data $courses dari controller (data yang sama) --}}
                                @forelse ($courses as $course)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}.</td>
                                    <td>{{ $course->course_code ?? '-' }}</td>
                                    <td>{{ $course->course_name }}</td>
                                    <td>{{ $course->dosen_name ?? '-' }}</td>
                                    <td class="text-center">{{ $course->sks ?? '-' }}</td>
                                    <td class="text-center">{{ $course->ruangan ?? '-' }}</td>
                                    <td>{{ $course->jam ?? '-' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">Belum ada mata kuliah (dan jadwal) ditambahkan.</td>
                                </tr>
                                @endforelse
                            </tbody>
                            {{-- ========================================================= --}}
                            {{--               AKHIR PERUBAHAN KODE                      --}}
                            {{-- ========================================================= --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Modal Tambah Mata Kuliah (Sudah Lengkap) --}}
@if($activeSemester)
<div class="modal fade" id="tambahMkModal" tabindex="-1" role="dialog" aria-labelledby="tambahMkModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahMkModalLabel"><i class="fas fa-plus-circle mr-2"></i> Tambah Mata Kuliah & Jadwal</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
            </div>
            <form action="{{ route('mata-kuliah.store') }}" method="POST">
                @csrf
                <input type="hidden" name="semester_id" value="{{ $activeSemester->id }}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="modalCourseCode" class="font-weight-bold">Kode MK</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" id="modalCourseCode" name="course_code" value="{{ old('course_code') }}" placeholder="Contoh: IF101">
                            @error('course_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8 form-group mb-3">
                            <label for="modalCourseName" class="font-weight-bold">Nama Mata Kuliah</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="modalCourseName" name="course_name" value="{{ old('course_name') }}" placeholder="Contoh: Pemrograman Web Lanjut" required>
                            @error('course_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label for="modalDosen" class="font-weight-bold">Dosen Pengampu</label>
                            <input type="text" class="form-control @error('dosen_name') is-invalid @enderror" id="modalDosen" name="dosen_name" value="{{ old('dosen_name') }}" placeholder="Contoh: Dr. Budi Santoso, M.Kom.">
                            @error('dosen_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="col-md-4 form-group mb-3">
                            <label for="modalSks" class="font-weight-bold">SKS</label>
                            <input type="number" class="form-control @error('sks') is-invalid @enderror" id="modalSks" name="sks" value="{{ old('sks') }}" placeholder="Contoh: 3" min="0">
                            @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="modalRuangan" class="font-weight-bold">Ruangan</label>
                            <input type="text" class="form-control @error('ruangan') is-invalid @enderror" id="modalRuangan" name="ruangan" value="{{ old('ruangan') }}" placeholder="Contoh: Lab Komp 1">
                            @error('ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="modalJam" class="font-weight-bold">Jam Kuliah</label>
                            <input type="text" class="form-control @error('jam') is-invalid @enderror" id="modalJam" name="jam" value="{{ old('jam') }}" placeholder="Contoh: Senin, 08:00 - 10:30">
                            @error('jam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    @error('semester_id') <div class="alert alert-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"> <i class="fas fa-save mr-1"></i> Simpan </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal Edit & Hapus (Dibuat dinamis di dalam loop @forelse) --}}
@foreach ($courses as $course)
{{-- Modal Edit --}}
<div class="modal fade" id="editModal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $course->id }}" aria-hidden="true">
   <div class="modal-dialog modal-lg" role="document"> {{-- Buat modal jadi 'lg' (besar) --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel{{ $course->id }}"><i class="fas fa-edit mr-2"></i> Edit Mata Kuliah & Jadwal</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{ route('mata-kuliah.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                {{-- ========================================================= --}}
                {{--                 PERUBAHAN KODE DI SINI                    --}}
                {{-- ========================================================= --}}
                <div class="modal-body">
                    {{-- Baris 1: Kode MK & Nama Mata Kuliah --}}
                    <div class="row">
                        <div class="col-md-4 form-group mb-3">
                            <label for="editCourseCode{{ $course->id }}" class="font-weight-bold">Kode MK</label>
                            <input type="text" class="form-control @error('course_code') is-invalid @enderror" id="editCourseCode{{ $course->id }}" name="course_code" value="{{ old('course_code', $course->course_code) }}" placeholder="Contoh: IF101">
                            @error('course_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-8 form-group mb-3">
                            <label for="editCourseName{{ $course->id }}" class="font-weight-bold">Nama Mata Kuliah</label>
                            <input type="text" class="form-control @error('course_name') is-invalid @enderror" id="editCourseName{{ $course->id }}" name="course_name" value="{{ old('course_name', $course->course_name) }}" placeholder="Contoh: Pemrograman Web Lanjut" required>
                            @error('course_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Baris 2: Dosen & SKS --}}
                    <div class="row">
                        <div class="col-md-8 form-group mb-3">
                            <label for="editDosen{{ $course->id }}" class="font-weight-bold">Dosen Pengampu</label>
                            <input type="text" class="form-control @error('dosen_name') is-invalid @enderror" id="editDosen{{ $course->id }}" name="dosen_name" value="{{ old('dosen_name', $course->dosen_name) }}" placeholder="Contoh: Dr. Budi Santoso, M.Kom.">
                            @error('dosen_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                         <div class="col-md-4 form-group mb-3">
                            <label for="editSks{{ $course->id }}" class="font-weight-bold">SKS</label>
                            <input type="number" class="form-control @error('sks') is-invalid @enderror" id="editSks{{ $course->id }}" name="sks" value="{{ old('sks', $course->sks) }}" placeholder="Contoh: 3" min="0">
                            @error('sks') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    {{-- Baris 3: Ruangan & Jam --}}
                     <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label for="editRuangan{{ $course->id }}" class="font-weight-bold">Ruangan</label>
                            <input type="text" class="form-control @error('ruangan') is-invalid @enderror" id="editRuangan{{ $course->id }}" name="ruangan" value="{{ old('ruangan', $course->ruangan) }}" placeholder="Contoh: Lab Komp 1">
                            @error('ruangan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 form-group mb-3">
                            <label for="editJam{{ $course->id }}" class="font-weight-bold">Jam Kuliah</label>
                            <input type="text" class="form-control @error('jam') is-invalid @enderror" id="editJam{{ $course->id }}" name="jam" value="{{ old('jam', $course->jam) }}" placeholder="Contoh: Senin, 08:00 - 10:30">
                            @error('jam') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                {{-- ========================================================= --}}
                {{--               AKHIR PERUBAHAN KODE                      --}}
                {{-- ========================================================= --}}
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="deleteModal{{ $course->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $course->id }}" aria-hidden="true">
   <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $course->id }}"><i class="fas fa-trash mr-2"></i> Konfirmasi Hapus</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
             <div class="modal-body">Yakin ingin menghapus mata kuliah <strong>{{ $course->course_name }}</strong>? Semua tugas terkait akan ikut terhapus. Tindakan ini tidak dapat dibatalkan.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form action="{{ route('mata-kuliah.destroy', $course->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

{{-- Push script jika perlu --}}
@push('scripts')
{{-- <script> console.log('Script Mata Kuliah'); </script> --}}
@endpush