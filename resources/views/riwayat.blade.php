@extends('layouts.app')

@section('title', 'Riwayat Semester')

@section('content')

<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Riwayat Kinerja Akademik</h1>
    </div>

    {{-- Alert Sukses (jika redirect dari "Akhiri Semester") --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Arsip Semester Selesai</h6>
                </div>
                <div class="card-body">
                    
                    {{-- Accordion Wrapper --}}
                    <div id="accordionRiwayat">

                        @forelse ($riwayatSemesters as $semester)
                            <div class="card">
                                {{-- Header Accordion (Judul Semester) --}}
                                <div class="card-header" id="heading{{ $semester->id }}">
                                    <h5 class="mb-0">
                                        {{-- Tampilkan yang pertama (terbaru) terbuka, sisanya collapsed --}}
                                        <button class="btn btn-link btn-block text-left {{ $loop->first ? '' : 'collapsed' }}" 
                                                data-toggle="collapse" 
                                                data-target="#collapse{{ $semester->id }}" 
                                                aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                                aria-controls="collapse{{ $semester->id }}">
                                            <strong>Semester {{ $semester->semester_number }}</strong> 
                                            (IPS: <span class="font-weight-bold text-primary">{{ $semester->final_ip ? number_format($semester->final_ip, 2) : 'N/A' }}</span>)
                                        </button>
                                    </h5>
                                </div>

                                {{-- Konten Accordion (Detail Evaluasi) --}}
                                <div id="collapse{{ $semester->id }}" 
                                     class="collapse {{ $loop->first ? 'show' : '' }}" 
                                     aria-labelledby="heading{{ $semester->id }}" 
                                     data-parent="#accordionRiwayat">
                                    <div class="card-body">
                                        {{-- Cek apakah ada data evaluasi --}}
                                        @if ($semester->evaluation)
                                            <div class="row">
                                                {{-- Kolom Kiri: Rangkuman Evaluasi --}}
                                                <div class="col-lg-6">
                                                    <h6 class="font-weight-bold text-warning">Evaluasi Kinerja:</h6>
                                                    <p class="text-gray-700">"{{ $semester->evaluation->evaluation_summary }}"</p>
                                                </div>
                                                {{-- Kolom Kanan: Distribusi Nilai Tugas --}}
                                                <div class="col-lg-6">
                                                    <h6 class="font-weight-bold text-info">Distribusi Nilai Tugas:</h6>
                                                    @php 
                                                        // Ubah JSON/array jadi collection agar lebih mudah di-loop
                                                        $penilaianData = (array) $semester->evaluation->grade_distribution; 
                                                    @endphp
                                                    
                                                    @if(count($penilaianData) > 0 && array_sum($penilaianData) > 0)
                                                        @foreach($penilaianData as $grade => $count)
                                                            @php
                                                                $totalTugasPenilaian = array_sum($penilaianData);
                                                                $percentage = ($totalTugasPenilaian > 0) ? ($count / $totalTugasPenilaian) * 100 : 0;
                                                                $colorClass = ''; $icon = 'fa-info-circle';
                                                                if (stripos($grade, 'Sangat Baik') !== false || stripos($grade, 'A') !== false){ $colorClass = 'success'; $icon = 'fa-check-circle';}
                                                                elseif (stripos($grade, 'Baik') !== false || stripos($grade, 'B') !== false){ $colorClass = 'info'; $icon = 'fa-thumbs-up';}
                                                                elseif (stripos($grade, 'Cukup') !== false || stripos($grade, 'C') !== false){ $colorClass = 'primary'; $icon = 'fa-info-circle';}
                                                                else {$colorClass = 'danger'; $icon = 'fa-times-circle';}
                                                            @endphp
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-{{$colorClass}}"><i class="fas {{$icon}} mr-1"></i> {{ $grade }}</span>
                                                                <span class="font-weight-bold">{{ $count }} Tugas</span>
                                                            </div>
                                                            <div class="progress mb-3" style="height: 5px;">
                                                                <div class="progress-bar bg-{{$colorClass}}" role="progressbar" style="width: {{ $percentage }}%"></div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-center text-muted small">Tidak ada data tugas yang dinilai untuk semester ini.</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-center text-muted">Data evaluasi untuk semester ini tidak ditemukan.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Tampil jika $riwayatSemesters kosong --}}
                            <div class="card">
                                <div class="card-body text-center text-muted">
                                    Belum ada riwayat semester yang diarsipkan.
                                </div>
                            </div>
                        @endforelse
                        
                    </div> {{-- Akhir #accordionRiwayat --}}
                </div>
            </div>
        </div>
    </div>

</div>

@endsection