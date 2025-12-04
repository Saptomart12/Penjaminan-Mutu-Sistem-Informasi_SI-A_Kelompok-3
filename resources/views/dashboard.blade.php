{{-- Menggunakan layout induk --}}
@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Dashboard')

{{-- Memasukkan konten utama --}}
@section('content')
<div class="container-fluid">

    {{-- Page Heading & Welcome Message --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Selamat Datang Kembali, {{ Auth::user()->name }}!</h1>
            <p class="mb-0 text-gray-600">
                @if($activeSemester)
                    Pantau progres akademik Anda di <strong>Semester {{ $activeSemester->semester_number }}</strong>.
                @else
                    {{-- Arahkan ke halaman kelola semester jika belum ada semester aktif --}}
                    Silakan <a href="{{ route('semester.index') }}">tambahkan atau aktifkan semester</a> untuk memulai.
                @endif
            </p> 
        </div>
        @if($activeSemester) {{-- Hanya tampilkan tombol jika ada semester aktif --}}
        <a href="{{ route('semester.finalize.form') }}" class="btn btn-sm btn-danger shadow-sm">
            <i class="fas fa-flag-checkered fa-sm text-white-50"></i> Akhiri Semester
        </a>
        @endif
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
             <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span> </button>
         </div>
     @endif

    {{-- Konten 5 Fragmen --}}
    <div class="row">

        {{-- Kolom Kiri (Lebih Besar): Aksi Utama & Visualisasi --}}
        <div class="col-xl-8 col-lg-7">

            {{-- FRAGMEN 1: KELOLA TUGAS (Dengan Tabs & Filter) --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tasks mr-2"></i> Daftar Tugas Semester Ini</h6>
                    @if($activeSemester)
                    <a href="#" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahTugasModal" title="Tambah Tugas Baru">
                        <i class="fas fa-plus fa-sm mr-1"></i> Tambah Tugas
                    </a>
                    @endif
                </div>
                
                <div class="card-body">
                    @if($activeSemester) {{-- Hanya tampilkan jika semester aktif --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        {{-- Navigasi TABS untuk Status Tugas (dengan hitungan) --}}
                        <ul class="nav nav-tabs" id="taskStatusTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="semua-tab" data-toggle="tab" href="#semua" role="tab" aria-controls="semua" aria-selected="true">Semua ({{ $allTasks->count() }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pending-tab" data-toggle="tab" href="#pending" role="tab" aria-controls="pending" aria-selected="false">Belum Selesai ({{ $pendingTasks->count() }})</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="graded-tab" data-toggle="tab" href="#graded" role="tab" aria-controls="graded" aria-selected="false">Sudah Dinilai ({{ $gradedTasks->count() }})</a>
                            </li>
                        </ul>
                        
                        {{-- Dropdown FILTER per Mata Kuliah (dengan ID untuk JS) --}}
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" id="filterDropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter fa-sm mr-1"></i> 
                                <span id="filter-button-text">Filter Mata Kuliah</span>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="filterDropdownButton">
                                <a class="dropdown-item task-filter-trigger" href="#" data-course-id="all" data-course-name="Tampilkan Semua">
                                    Tampilkan Semua
                                </a>
                                <div class="dropdown-divider"></div>
                                @forelse($courses as $course)
                                    <a class="dropdown-item task-filter-trigger" href="#" data-course-id="{{ $course->id }}" data-course-name="{{ $course->course_name }}">
                                        {{ $course->course_name }}
                                    </a>
                                @empty
                                     <a class="dropdown-item disabled" href="#">Belum ada MK</a>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Konten TAB (Memanggil partial task-table) --}}
                    <div class="tab-content" id="taskStatusTabsContent">
                        {{-- TAB 1: SEMUA TUGAS --}}
                        <div class="tab-pane fade show active" id="semua" role="tabpanel" aria-labelledby="semua-tab">
                            @include('layouts.partials.task-table', ['tasks' => $allTasks, 'tabName' => 'all'])
                        </div>
                        {{-- TAB 2: BELUM SELESAI --}}
                        <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                             @include('layouts.partials.task-table', ['tasks' => $pendingTasks, 'tabName' => 'pending'])
                        </div>
                        {{-- TAB 3: SUDAH DINILAI --}}
                        <div class="tab-pane fade" id="graded" role="tabpanel" aria-labelledby="graded-tab">
                             @include('layouts.partials.task-table', ['tasks' => $gradedTasks, 'tabName' => 'graded'])
                        </div>
                    </div>
                    @else
                    {{-- Tampil jika tidak ada semester aktif --}}
                    <p class="text-center text-muted p-4">Belum ada semester aktif. Silakan <a href="{{ route('semester.index') }}">kelola semester</a> untuk memulai.</p>
                    @endif
                </div>
            </div>

            {{-- FRAGMEN 4: Visualisasi --}}
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-line mr-2"></i> Visualisasi Kinerja Semester Ini</h6>
                </div>
                <div class="card-body">
                    <p class="text-gray-700">Diagram ini memvisualisasikan progres penyelesaian tugas dan rata-rata nilai yang Anda peroleh sejauh ini.</p>
                    <div class="chart-area pt-4 pb-2">
                        <div class="text-center text-muted placeholder-chart" style="height: 250px; display: flex; align-items: center; justify-content: center; border: 1px dashed #ccc; border-radius: 5px;">
                           [Grafik Kinerja Akan Tampil di Sini] 📈
                        </div>
                        <canvas id="visualisasiDiagramKualitas" style="display: none;"></canvas>
                    </div>
                </div>
            </div>

        </div> {{-- Akhir col-xl-8 --}}

        {{-- Kolom Kanan (Lebih Kecil): Prediksi & Refleksi --}}
        <div class="col-xl-4 col-lg-5">
            {{-- FRAGMEN 5: Prediksi IP --}}
            <div class="card border-left-success shadow mb-4">
                 <div class="card-body">
                     <div class="row no-gutters align-items-center mb-2">
                         <div class="col mr-2">
                             <div class="text-xs font-weight-bold text-success text-uppercase mb-1"> Simulasi & Prediksi</div>
                             <div class="h5 mb-0 font-weight-bold text-gray-800">Estimasi IP Semester</div>
                         </div>
                         <div class="col-auto"> <i class="fas fa-bullseye fa-3x text-gray-300"></i> </div>
                     </div>
                     <div class="text-center my-3 py-3 bg-light rounded">
                         <h1 class="display-4 font-weight-bold text-success mb-0">{{ $prediksiIp ?? 'N/A' }}</h1>
                     </div>
                     <small class="text-gray-600 d-block text-center">Berdasarkan {{ $gradedTasks->count() }} tugas yang telah dinilai.</small>
                 </div>
            </div>

            {{-- FRAGMEN 2: Evaluasi --}}
            <div class="card shadow mb-4">
                 <div class="card-header py-3 bg-warning">
                    <h6 class="m-0 font-weight-bold text-white"><i class="fas fa-lightbulb mr-2"></i> Fokus dari Semester Lalu</h6>
                 </div>
                 <div class="card-body">
                    @if($evaluationData)
                        <p class="text-gray-700">"{{ $evaluationData->evaluation_summary }}"</p>
                    @else
                        <p class="text-center text-gray-600">Belum ada evaluasi dari semester lalu.</p>
                    @endif
                    <a href="#" class="btn btn-sm btn-outline-warning mt-2">Lihat Detail Riwayat &rarr;</a>
                 </div>
            </div>

            {{-- FRAGMEN 3: Penilaian Ringkas --}}
            <div class="card shadow mb-4">
                 <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-medal mr-2"></i> Rangkuman Nilai Semester Lalu</h6>
                 </div>
                 <div class="card-body">
                    @if($penilaianData && count($penilaianData) > 0)
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
                        <p class="text-center text-gray-600">Belum ada data penilaian.</p>
                    @endif
                 </div>
            </div>
        </div> {{-- Akhir col-xl-4 --}}
    </div> {{-- Akhir row --}}

</div> {{-- Akhir container-fluid --}}

{{-- Panggil modals di akhir section content --}}
@include('layouts.partials.modals')

@endsection

{{-- Push script khusus untuk chart dashboard --}}
@push('scripts')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        {{-- Script Chart.js (ambil data dari $visualisasiData) --}}
        var ctx = document.getElementById("visualisasiDiagramKualitas");
        var visualisasiData = @json($visualisasiData ?? ['labels' => [], 'data' => []]); 

        if(ctx && visualisasiData.labels && visualisasiData.labels.length > 0) { 
             ctx.style.display = 'block';
             document.querySelector('.placeholder-chart').style.display = 'none';
             var myLineChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: visualisasiData.labels,
                    datasets: [{
                        label: "Nilai",
                        lineTension: 0.3,
                        backgroundColor: "rgba(78, 115, 223, 0.05)",
                        borderColor: "rgba(78, 115, 223, 1)",
                        pointRadius: 3,
                        pointBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointBorderColor: "rgba(78, 115, 223, 1)",
                        pointHoverRadius: 3,
                        pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                        pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: visualisasiData.data,
                    }],
                },
                options: {
                    maintainAspectRatio: false, layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } }, scales: { xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }], yAxes: [{ ticks: { maxTicksLimit: 5, padding: 10, suggestedMin: 50, suggestedMax: 100 }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }], }, legend: { display: false }, tooltips: { mode: 'index', intersect: false, callbacks: { label: function(tooltipItem, chart) { var label = chart.datasets[tooltipItem.datasetIndex].label || ''; if (label) { label += ': '; } label += parseFloat(tooltipItem.yLabel).toFixed(2); return label; } } }
                }
            });
        } else if (ctx) {
             document.querySelector('.placeholder-chart').style.display = 'flex';
             ctx.style.display = 'none';
        }

        {{-- ============================================= --}}
        {{--         SCRIPT FILTER JAVASCRIPT            --}}
        {{-- ============================================= --}}
        document.addEventListener('DOMContentLoaded', function () {
            
            const filterButtons = document.querySelectorAll('.task-filter-trigger');
            const allTaskRows = document.querySelectorAll('.task-row'); // Target semua <tr>
            const filterButtonText = document.getElementById('filter-button-text');

            filterButtons.forEach(function (button) {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); 
                    const filterId = this.dataset.courseId;
                    const filterName = this.dataset.courseName;

                    filterButtonText.textContent = filterName;

                    allTaskRows.forEach(function (row) {
                        const rowCourseId = row.dataset.courseId;

                        if (filterId === 'all') {
                            row.style.display = ''; // Tampilkan
                        } else if (rowCourseId === filterId) {
                            row.style.display = ''; // Tampilkan
                        } else {
                            row.style.display = 'none'; // Sembunyikan
                        }
                    });
                });
            });
        });
    </script>
@endpush