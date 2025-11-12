<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="APIPANAS Auth">
    <meta name="author" content="Tim Anda">

    <title>@yield('title') - APIPANAS</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- CSS Tambahan jika perlu (misal bg-image berbeda) --}}
    @stack('styles')

</head>
<body class="bg-gradient-primary">

    <div class="container">

        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">

            <div class="col-xl-10 col-lg-12 col-md-9">

                {{-- Card utama SB Admin 2 --}}
                <div class="card o-hidden border-0 shadow-lg">
                    <div class="card-body p-0">
                        <div class="row">
                            {{-- Area Gambar (Opsional, bisa diatur per halaman) --}}
                            <div class="col-lg-6 d-none d-lg-block @yield('auth_image_class', 'bg-login-image')"></div>

                            {{-- Area Form --}}
                            <div class="col-lg-6">
                                <div class="p-5">
                                    {{-- KONTEN FORM DARI LOGIN/REGISTER AKAN MASUK DI SINI --}}
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    @stack('scripts')

</body>
</html>