@extends('layouts.app')

@section('title', 'Detail Tugas: ' . $task->title)

{{-- Style khusus untuk modal preview & Trix --}}
@push('styles')
<style>
    /* Modal Preview */
    #previewModalBody { max-height: 75vh; overflow-y: auto; }
    #previewModalBody iframe, #previewModalBody img { width: 100%; border-radius: .25rem; }
    .file-preview-trigger { text-decoration: none !important; font-weight: 700; color: #4e73df; }
    .file-preview-trigger:hover { color: #2e59d9; text-decoration: underline !important; }
    
    /* Tabs */
    .card-header-tabs { margin-left: -0.25rem; margin-right: -0.25rem; }
    .card-header-tabs .nav-link { border: 0; color: #858796; }
    .card-header-tabs .nav-link.active { color: #4e73df; border-bottom: 2px solid #4e73df; font-weight: 700; background: none; }
    
    /* Styling Trix Content */
    .trix-content { line-height: 1.6; }
    .trix-content ul { list-style-type: disc; margin-left: 1.5rem; padding-left: 0.5rem; }
    .trix-content ol { list-style-type: decimal; margin-left: 1.5rem; padding-left: 0.5rem; }
    .trix-content blockquote { border-left: 4px solid #e3e6f0; padding-left: 1rem; margin-left: 0; font-style: italic; color: #858796; }
    .trix-content pre { background-color: #f8f9fc; border: 1px solid #e3e6f0; padding: 1rem; border-radius: .25rem; font-family: 'Courier New', Courier, monospace; overflow-x: auto; }
</style>
@endpush


@section('content')
<div class="container-fluid">

    {{-- Tombol Kembali --}}
    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary btn-sm mb-3">
        <i class="fas fa-arrow-left mr-2"></i>
        Kembali ke Dashboard
    </a>

    {{-- Judul Tugas --}}
    <h1 class="h3 mb-4 text-gray-800">{{ $task->title }}</h1>

    {{-- Info Card (MK, Dosen, Tenggat) --}}
    <div class="row">
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body"> <div class="row no-gutters align-items-center"> <div class="col mr-2"> <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"> Mata Kuliah</div> <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $task->course->course_name }}</div> </div> <div class="col-auto"> <i class="fas fa-book fa-2x text-gray-300"></i> </div> </div> </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body"> <div class="row no-gutters align-items-center"> <div class="col mr-2"> <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Dosen Pengampu</div> <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $task->course->dosen_name ?? 'N/A' }}</div> </div> <div class="col-auto"> <i class="fas fa-user-tie fa-2x text-gray-300"></i> </div> </div> </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body"> <div class="row no-gutters align-items-center"> <div class="col mr-2"> <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"> Tenggat Waktu</div> <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ \Carbon\Carbon::parse($task->deadline)->isoFormat('dddd, D MMM YYYY') }} </div> <div class="text-xs font-weight-bold text-gray-600 mt-1"> Pukul {{ \Carbon\Carbon::parse($task->deadline)->isoFormat('HH:mm') }} </div> </div> <div class="col-auto"> <i class="fas fa-calendar-alt fa-2x text-gray-300"></i> </div> </div> </div>
            </div>
        </div>
    </div>

    {{-- Alert (Pesan Sukses/Error) --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0"> @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach </ul>
        </div>
    @endif

    {{-- Konten Utama (Deskripsi & File) --}}
    <div class="row">

        {{-- Kolom Kiri: Form Editor Deskripsi --}}
        <div class="col-lg-7">
            <form action="{{ route('tugas.update.description', $task->id) }}" method="POST">
                @csrf
                @method('PATCH') {{-- Pakai PATCH untuk update sebagian --}}
                
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-info-circle mr-2"></i>Deskripsi & Catatan Tugas</h6>
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-save fa-sm mr-1"></i> Simpan Deskripsi
                        </button>
                    </div>
                    <div class="card-body">
                        {{-- Trix Editor untuk deskripsi --}}
                        <input id="task_description" type="hidden" name="description" value="{{ old('description', $task->description) }}">
                        <trix-editor 
                            input="task_description" 
                            class="form-control @error('description') is-invalid @enderror" 
                            style="min-height: 300px;"
                            placeholder="Tulis catatan, link, checklist, atau detail tugas di sini...">
                        </trix-editor>
                        @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        {{-- Kolom Kanan: Upload & Daftar File --}}
        <div class="col-lg-5">
            {{-- Form Upload --}}
            <div class="card shadow mb-4 border-left-success">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success"><i class="fas fa-upload mr-2"></i>Unggah File Lampiran</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('tugas.files.store', $task->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file_upload" class="small">Pilih file (Maks: 5MB)</label>
                            <input type="file" class="form-control-file @error('file_upload') is-invalid @enderror" id="file_upload" name="file_upload" required>
                             @error('file_upload')
                                <div class="invalid-feedback">{{ $message }}</div>
                             @enderror
                        </div>
                        <button type="submit" class="btn btn-success btn-block">Unggah</button>
                    </form>
                </div>
            </div>

            {{-- Daftar File Terlampir --}}
            <div class="card shadow mb-4">
                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-paperclip mr-2"></i>File Terlampir ({{ $task->files->count() }})</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($task->files as $file)
                            @php 
                                $url = Storage::url($file->file_path); // URL Download (Direct)
                                $previewUrl = route('tugas.files.preview', $file->id); // URL Preview (Streaming)
                                $fileType = strtolower($file->file_type);
                            @endphp
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <a href="#" class="file-preview-trigger" data-toggle="modal" data-target="#filePreviewModal"
                                       data-file-name="{{ $file->file_name }}"
                                       data-file-url="{{ $url }}"
                                       data-preview-url="{{ $previewUrl }}"
                                       data-file-type="{{ $fileType }}">
                                        
                                        @if(in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) <i class="fas fa-file-image mr-2"></i>
                                        @elseif($fileType == 'pdf') <i class="fas fa-file-pdf mr-2"></i>
                                        @elseif(in_array($fileType, ['doc', 'docx'])) <i class="fas fa-file-word mr-2"></i>
                                        @else <i class="fas fa-file-alt mr-2"></i>
                                        @endif
                                        {{ Str::limit($file->file_name, 25) }}
                                    </a>
                                </div>
                                <div class="btn-group" role="group">
                                    <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <form action="{{ route('tugas.files.destroy', $file->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted px-0">Belum ada file terlampir.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>


    {{-- Modal Preview (Satu untuk Semua) --}}
    <div class="modal fade" id="filePreviewModal" tabindex="-1" role="dialog" aria-labelledby="previewModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModalTitle">Preview File</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="previewModalBody">
                    <p class="text-center text-muted">Memuat preview...</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
{{-- Script untuk mengisi modal preview secara dinamis --}}
<script>
    $(document).ready(function() {
        $('#filePreviewModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var fileName = button.data('file-name');
            var directUrl = button.data('file-url');
            var previewUrl = button.data('preview-url');
            var fileType = button.data('file-type');
            var modal = $(this);
            var modalBody = modal.find('#previewModalBody');
            
            modal.find('#previewModalTitle').text(fileName);
            modalBody.html('<div class="text-center p-5"><div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div><p class="text-muted mt-2">Memuat preview...</p></div>');
            
            var contentHtml = '';
            
            // 1. Tipe Gambar & PDF (Gunakan Rute Streaming)
            if (['jpg', 'jpeg', 'png', 'gif', 'bmp', 'pdf'].includes(fileType)) {
                contentHtml = '<iframe src="' + previewUrl + '" width="100%" height="600px" style="border: none;"></iframe>';
            } 
            // 2. Tipe Dokumen (Pakai Google Docs Viewer + URL Direct)
            else if (['doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx'].includes(fileType)) {
                var googleViewerUrl = 'https://docs.google.com/gview?url=' + encodeURIComponent(directUrl) + '&embedded=true';
                contentHtml = '<iframe src="' + googleViewerUrl + '" width="100%" height="600px" style="border: none;"></iframe>';
            } 
            // 3. Tipe file lain
            else {
                contentHtml = '<div class="text-center p-5">' +
                                  '<i class="fas fa-file-archive fa-3x text-muted"></i>' +
                                  '<p class="text-muted small mt-2">Preview tidak tersedia untuk file <b>.' + fileType + '</b>.<br>Silakan klik "Download" untuk melihatnya.</p>' +
                              '</div>';
            }
            modalBody.html(contentHtml);
        });

        // Hentikan iframe saat modal ditutup
        $('#filePreviewModal').on('hidden.bs.modal', function () {
            $(this).find('#previewModalBody').html('<p class="text-center text-muted">Memuat preview...</p>');
        });
    });
</script>
@endpush