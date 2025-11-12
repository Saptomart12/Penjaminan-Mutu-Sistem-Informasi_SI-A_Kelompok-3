@extends('layouts.guest') {{-- Pakai layout guest --}}

@section('title', 'Buat Akun') {{-- Judul halaman --}}

@section('auth_image_class', 'bg-register-image') {{-- Kelas CSS untuk gambar background --}}

@section('content') {{-- Konten Form --}}
<div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">Buat Akun Baru!</h1>
</div>

{{-- FORM REGISTER --}}
<form class="user" method="POST" action="{{ route('register') }}">
    @csrf

    {{-- Input Nama --}}
    <div class="form-group">
         <input type="text"
               class="form-control form-control-user @error('name') is-invalid @enderror"
               id="exampleName"
               placeholder="Nama Lengkap"
               name="name"
               value="{{ old('name') }}"
               required
               autofocus>
        @error('name')
            <span class="invalid-feedback d-block text-center" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Input Email --}}
    <div class="form-group">
        <input type="email"
               class="form-control form-control-user @error('email') is-invalid @enderror"
               id="exampleInputEmail"
               placeholder="Alamat Email"
               name="email"
               value="{{ old('email') }}"
               required>
        @error('email')
            <span class="invalid-feedback d-block text-center" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>

    {{-- Input Password & Konfirmasi --}}
    <div class="form-group row">
        <div class="col-sm-6 mb-3 mb-sm-0">
            <input type="password"
                   class="form-control form-control-user @error('password') is-invalid @enderror"
                   id="exampleInputPassword"
                   placeholder="Password"
                   name="password"
                   required>
            @error('password')
                {{-- Error password biasanya mencakup error konfirmasi juga --}}
                <span class="invalid-feedback d-block text-center" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="col-sm-6">
            <input type="password"
                   class="form-control form-control-user"
                   id="exampleRepeatPassword"
                   placeholder="Ulangi Password"
                   name="password_confirmation"
                   required>
        </div>
    </div>

    {{-- Tombol Register --}}
    <button type="submit" class="btn btn-primary btn-user btn-block">
        Register Akun
    </button>
</form>
{{-- AKHIR FORM REGISTER --}}

<hr>
{{-- <div class="text-center">
    @if (Route::has('password.request'))
    <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
    @endif
</div> --}}
<div class="text-center">
    <a class="small" href="{{ route('login') }}">Sudah punya akun? Login!</a>
</div>
@endsection