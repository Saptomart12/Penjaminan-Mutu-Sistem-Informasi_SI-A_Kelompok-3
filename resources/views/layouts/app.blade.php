<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Aplikasi Pembantu Nilai Akademis (APIPANAS)">
    <meta name="author" content="Tim Anda">

    <title>@yield('title', 'APIPANAS') - APIPANAS</title>

    {{-- Aset CSS --}}
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- ============================================= --}}
    {{--             TAMBAHKAN CSS TRIX INI            --}}
    {{-- ============================================= --}}
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
    {{-- CSS untuk sembunyikan tombol 'upload file' Trix (karena kita punya sistem sendiri) --}}
    <style>
        trix-toolbar [data-trix-button-group="file-tools"] {
            display: none;
        }
    </style>
    {{-- ============================================= --}}

    {{-- Tempat untuk CSS tambahan dari halaman anak --}}
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">

        {{-- Memanggil Sidebar --}}
        @include('layouts.partials.sidebar')

        {{-- Content Wrapper --}}
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">

                {{-- Memanggil Topbar --}}
                @include('layouts.partials.topbar')

                {{-- Area Konten Utama dari Halaman Anak --}}
                @yield('content')

            </div>

            {{-- Memanggil Footer --}}
            @include('layouts.partials.footer')

        </div>
        {{-- End of Content Wrapper --}}
    </div>
    {{-- End of Page Wrapper --}}

    @include('layouts.partials.modals')

    {{-- Scroll to Top Button (Bagian dari modals.blade.php di layout lama, pindah ke sini lebih baik) --}}
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Aset JS Umum --}}
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>

    @stack('scripts')

</body>
</html>