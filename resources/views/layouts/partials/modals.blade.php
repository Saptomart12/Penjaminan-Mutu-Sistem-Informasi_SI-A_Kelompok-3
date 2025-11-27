{{-- Logout Modal --}}
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Anda Yakin Ingin Keluar?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Pilih "Logout" di bawah ini untuk mengakhiri sesi Anda saat ini.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="btn btn-primary" href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();">
                        Logout
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Tambah Tugas Modal --}}
<div class="modal fade" id="tambahTugasModal" tabindex="-1" role="dialog" aria-labelledby="tambahTugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document"> {{-- modal-lg agar lebih lebar --}}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahTugasLabel"><i class="fas fa-plus-circle mr-2"></i> Tambah Tugas Baru</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form action="{{ route('tugas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    {{-- Input Nama Tugas --}}
                    <div class="form-group mb-3">
                        <label for="tugas_namaTugas" class="font-weight-bold">Nama Tugas</label>
                        <input type="text" class="form-control @error('title', 'storeTask') is-invalid @enderror" id="tugas_namaTugas" name="title" value="{{ old('title') }}" placeholder="Contoh: Esai Bab 2" required>
                        @error('title', 'storeTask')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Dropdown Mata Kuliah --}}
                    <div class="form-group mb-3">
                        <label for="tugas_mataKuliah" class="font-weight-bold">Mata Kuliah</label>
                        <select class="form-control @error('course_id', 'storeTask') is-invalid @enderror" id="tugas_mataKuliah" name="course_id" required>
                            <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                            @isset($courses)
                                @forelse($courses as $course)
                                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                        {{ $course->course_name }}
                                    </option>
                                @empty
                                    <option value="" disabled>Belum ada mata kuliah di semester ini.</option>
                                @endforelse
                            @else
                                <option value="" disabled>Gagal memuat mata kuliah.</option>
                            @endisset
                        </select>
                         @error('course_id', 'storeTask')
                            <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                    </div>
                    
                    {{-- Input Tenggat Waktu --}}
                    <div class="form-group mb-3">
                        <label for="tugas_deadline" class="font-weight-bold">Tenggat Waktu</label>
                        <input type="datetime-local" class="form-control @error('deadline', 'storeTask') is-invalid @enderror" id="tugas_deadline" name="deadline" value="{{ old('deadline') }}" required>
                         @error('deadline', 'storeTask')
                            <div class="invalid-feedback">{{ $message }}</div>
                         @enderror
                    </div>


                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"> <i class="fas fa-save mr-1"></i> Simpan Tugas</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Akhiri Semester --}}
<div class="modal fade" id="akhiriSemesterModal" tabindex="-1" role="dialog" aria-labelledby="akhiriSemesterLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="akhiriSemesterLabel">Konfirmasi Akhiri Semester</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mengakhiri semester ini?</p>
                <p><strong>Tindakan ini tidak dapat dibatalkan.</strong> Sistem akan mengarsipkan semua tugas Anda dan menghasilkan laporan evaluasi akhir.</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                {{-- TODO: Buat route dan controller untuk logic ini --}}
                <form action="{{-- route('semester.end') --}}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Ya, Akhiri Semester</button>
                </form>
            </div>
        </div>
    </div>
</div>