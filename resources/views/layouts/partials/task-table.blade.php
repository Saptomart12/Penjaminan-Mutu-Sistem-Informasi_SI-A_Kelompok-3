{{-- File: resources/views/layouts/partials/task-table.blade.php --}}

<div class="table-responsive">
    <table class="table table-hover table-striped" width="100%" cellspacing="0">
        <thead class="thead-light">
            <tr>
                <th class="text-center">Status</th>
                <th>Nama Tugas</th>
                <th>Mata Kuliah</th>
                <th class="text-center">Tenggat</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tasks as $task)
                @php
                    $deadline = \Carbon\Carbon::parse($task->deadline);
                    $now = \Carbon\Carbon::now();
                    $isGraded = ($task->status == 'graded');
                    $isPending = ($task->status == 'pending');
                    $isOverdue = $deadline->isPast() && !$deadline->isToday();
                    $isToday = $deadline->isToday();
                    $isTomorrow = $deadline->isTomorrow();
                    $diffInDays = $now->startOfDay()->diffInDays($deadline->startOfDay(), false);
                @endphp
                
                <tr class="task-row align-middle" data-course-id="{{ $task->course_id }}">
                    
                    {{-- Kolom Status --}}
                    <td class="text-center">
                        @if ($isGraded) <span class="badge badge-success">Dinilai</span>
                        @elseif ($isOverdue) <span class="badge badge-danger">Terlewat</span>
                        @else <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    
                    {{-- Kolom Nama Tugas (Link ke Detail) --}}
                    <td>
                        <a href="{{ route('tugas.show', $task->id) }}" class="text-decoration-none">
                            <strong class="{{ $isOverdue ? 'text-danger' : ($isGraded ? 'text-gray-600' : 'text-dark') }}" 
                                    style="{{ $isGraded ? 'text-decoration: line-through;' : '' }}">
                                {{ $task->title }}
                            </strong>
                        </a>
                    </td>
                    
                    {{-- Kolom Mata Kuliah --}}
                    <td><small class="text-muted">{{ $task->course->course_name ?? 'N/A' }}</small></td>
                    
                    {{-- Kolom Tenggat --}}
                    <td class="text-center">
                        @if ($isGraded) <small class="text-muted">{{ $deadline->isoFormat('D MMM YYYY') }}</small>
                        @elseif ($isToday) <span class="badge badge-danger badge-pill">Hari Ini!</span>
                        @elseif ($isTomorrow) <span class="badge badge-danger badge-pill">Besok!</span>
                        @elseif ($isOverdue) <span class="badge badge-danger badge-pill">Terlewat {{ $deadline->diffForHumans() }}</span>
                        @elseif (abs($diffInDays) <= 7) <span class="badge badge-warning badge-pill">{{ abs($diffInDays) }} Hari Lagi</span>
                        @else <span class="badge badge-info badge-pill">{{ $deadline->isoFormat('D MMM') }}</span>
                        @endif
                    </td>
                    
                    {{-- Kolom Aksi --}}
                    <td class="text-center">
                        @if ($isPending)
                            {{-- Tombol Selesai --}}
                            <a href="#" class="btn btn-success btn-circle btn-sm" title="Tandai Selesai & Nilai"
                               data-toggle="modal" data-target="#selesaiTugasModal{{ $task->id }}">
                                <i class="fas fa-check"></i>
                            </a>
                            {{-- Tombol Edit --}}
                            <a href="#" class="btn btn-info btn-circle btn-sm" title="Edit" 
                               data-toggle="modal" data-target="#editTugasModal{{ $task->id }}">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            {{-- Tombol Hapus --}}
                            <a href="#" class="btn btn-danger btn-circle btn-sm" title="Hapus" 
                               data-toggle="modal" data-target="#hapusTugasModal{{ $task->id }}">
                                <i class="fas fa-trash"></i>
                            </a>
                        @elseif ($isGraded)
                            <span class="badge badge-success" style="font-size: 0.9rem;">{{ number_format($task->score, 2) }}</span>
                        @endif
                    </td>
                </tr>

                {{-- ========================================================= --}}
                {{--              MODAL-MODAL AKSI (DI DALAM LOOP)             --}}
                {{-- ========================================================= --}}
                @if ($isPending)
                    {{-- 1. Modal Tandai Selesai (Input Nilai) --}}
                    <div class="modal fade" id="selesaiTugasModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="selesaiTugasLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="selesaiTugasLabel{{ $task->id }}"><i class="fas fa-check-circle mr-2"></i> Input Nilai Tugas</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                </div>
                                <form action="{{ route('tugas.update', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <p>Tandai <strong>"{{ $task->title }}"</strong> selesai dan masukkan nilai (0-100).</p>
                                        <div class="form-group">
                                            <label for="score{{ $task->id }}" class="font-weight-bold">Nilai Tugas</label>
                                            <input type="number" step="0.01" class="form-control @error('score', 'scoreTask'.$task->id) is-invalid @enderror" id="score{{ $task->id }}" name="score" value="{{ old('score') }}" placeholder="Contoh: 85.50" required min="0" max="100">
                                            @error('score', 'scoreTask'.$task->id)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success"> <i class="fas fa-save mr-1"></i> Simpan Nilai</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Modal Edit Tugas (BARU) --}}
                    <div class="modal fade" id="editTugasModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="editTugasLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editTugasLabel{{ $task->id }}"><i class="fas fa-edit mr-2"></i> Edit Detail Tugas</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                </div>
                                {{-- Form mengarah ke route 'tugas.updateDetails' --}}
                                <form action="{{ route('tugas.updateDetails', $task->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH') {{-- Method untuk update sebagian --}}
                                    <div class="modal-body">
                                        {{-- Nama Tugas --}}
                                        <div class="form-group mb-3">
                                            <label for="edit_title_{{ $task->id }}" class="font-weight-bold">Nama Tugas</label>
                                            <input type="text" class="form-control @error('title', 'editTask'.$task->id) is-invalid @enderror" id="edit_title_{{ $task->id }}" name="title" value="{{ old('title', $task->title) }}" required>
                                            @error('title', 'editTask'.$task->id) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        {{-- Mata Kuliah --}}
                                        <div class="form-group mb-3">
                                            <label for="edit_course_id_{{ $task->id }}" class="font-weight-bold">Mata Kuliah</label>
                                            <select class="form-control @error('course_id', 'editTask'.$task->id) is-invalid @enderror" id="edit_course_id_{{ $task->id }}" name="course_id" required>
                                                <option value="" disabled>-- Pilih Mata Kuliah --</option>
                                                @isset($courses) {{-- Loop $courses dari DashboardController --}}
                                                    @foreach($courses as $course)
                                                        <option value="{{ $course->id }}" 
                                                            {{-- Pilih $course->id jika itu adalah course_id tugas, ATAU jika itu old_input --}}
                                                            {{ old('course_id', $task->course_id) == $course->id ? 'selected' : '' }}>
                                                            {{ $course->course_name }}
                                                        </option>
                                                    @endforeach
                                                @endisset
                                            </select>
                                            @error('course_id', 'editTask'.$task->id) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        {{-- Tenggat Waktu --}}
                                        <div class="form-group mb-3">
                                            <label for="edit_deadline_{{ $task->id }}" class="font-weight-bold">Tenggat Waktu</label>
                                            {{-- Format value agar bisa dibaca input datetime-local --}}
                                            <input type="datetime-local" class="form-control @error('deadline', 'editTask'.$task->id) is-invalid @enderror" id="edit_deadline_{{ $task->id }}" name="deadline" value="{{ old('deadline', $task->deadline->format('Y-m-d\TH:i')) }}" required>
                                            @error('deadline', 'editTask'.$task->id) <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                         {{-- Deskripsi (dihapus dari sini, diedit di halaman detail) --}}
                                    </div>
                                    <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-info"> <i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Modal Hapus Tugas --}}
                    <div class="modal fade" id="hapusTugasModal{{ $task->id }}" tabindex="-1" role="dialog" aria-labelledby="hapusTugasLabel{{ $task->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="hapusTugasLabel{{ $task->id }}"><i class="fas fa-trash mr-2"></i> Konfirmasi Hapus</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button>
                                </div>
                                <div class="modal-body">Yakin ingin menghapus <strong>"{{ $task->title }}"</strong>? Tindakan ini tidak dapat dibatalkan.</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                                    <form action="{{ route('tugas.destroy', $task->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- ========================================================= --}}
                
            @empty
                {{-- Pesan @empty --}}
                <tr>
                    <td colspan="5" class="text-center text-muted p-4">
                        @if($tabName == 'pending')
                            Tidak ada tugas yang belum selesai. Kerja bagus!
                        @elseif($tabName == 'graded')
                            Belum ada tugas yang dinilai.
                        @else
                            Belum ada tugas ditambahkan untuk semester ini.
                        @endif
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>