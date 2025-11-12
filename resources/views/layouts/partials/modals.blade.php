{{-- ============================================= --}}
{{--           MODAL TAMBAH TUGAS (SPESIFIK)       --}}
{{-- ============================================= --}}
<div class="modal fade" id="tambahTugasModal" tabindex="-1" role="dialog" aria-labelledby="tambahTugasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
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
                    
                    {{-- Dropdown Mata Kuliah Dinamis --}}
                    <div class="form-group mb-3">
                        <label for="tugas_mataKuliah" class="font-weight-bold">Mata Kuliah</label>
                        <select class="form-control @error('course_id', 'storeTask') is-invalid @enderror" id="tugas_mataKuliah" name="course_id" required>
                            <option value="" disabled selected>-- Pilih Mata Kuliah --</option>
                            
                            {{-- $courses didapat dari DashboardController --}}
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

                    {{-- Input Deskripsi (Trix Editor) --}}
                    <div class="form-group mb-0">
                        <label for="tugas_deskripsi_input" class="font-weight-bold">Deskripsi (Opsional)</label>
                        <input id="tugas_deskripsi_input" type="hidden" name="description" value="{{ old('description') }}">
                        <trix-editor 
                            input="tugas_deskripsi_input" 
                            class="form-control @error('description', 'storeTask') is-invalid @enderror" 
                            style="min-height: 150px;"
                            placeholder="Tulis catatan, link, atau detail tugas di sini...">
                        </trix-editor>
                        @error('description', 'storeTask')
                            <div class="invalid-feedback d-block">{{ $message }}</div> 
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