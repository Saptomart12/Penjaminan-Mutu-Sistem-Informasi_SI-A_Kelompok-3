@extends('layouts.guest') {{-- Pakai layout guest --}}

@section('title', 'Login') {{-- Judul halaman --}}

@section('auth_image_class', 'bg-login-image') {{-- Kelas CSS untuk gambar background --}}

@section('content') {{-- Konten Form --}}
<div class="text-center">
    <h1 class="h4 text-gray-900 mb-4">Selamat Datang Kembali!</h1>
</div>

@if (session('success'))
<div class="alert alert-success text-center small p-3 mb-3" role="alert">
        {{ session('success') }}
    </div>
@endif

{{-- FORM LOGIN --}}
<form class="user" method="POST" action="{{ route('login') }}">
    @csrf

    @if($errors->any())
        <div class="alert alert-danger text-sm p-2 text-center mb-3">
            Email atau Password salah.
        </div>
    @endif

    {{-- Input Email --}}
    <div class="form-group">
        <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" id="exampleInputEmail" placeholder="Masukkan Alamat Email..." name="email" value="{{ old('email') }}" required autofocus>
        @error('email')
            <span class="invalid-feedback d-block text-center" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Input Password --}}
    <div class="form-group">
        <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" id="exampleInputPassword" placeholder="Password" name="password" required>
        @error('password')
            <span class="invalid-feedback d-block text-center" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    {{-- Remember Me --}}
    <div class="form-group">
        <div class="custom-control custom-checkbox small">
            <input type="checkbox" class="custom-control-input" id="customCheck" name="remember">
            <label class="custom-control-label" for="customCheck">Remember Me</label>
        </div>
    </div>

    {{-- Tombol Login --}}
    <button type="submit" class="btn btn-primary btn-user btn-block">
        Login
    </button>
</form>
{{-- AKHIR FORM LOGIN --}}

<hr>
{{-- <div class="text-center">
    @if (Route::has('password.request'))
    <a class="small" href="{{ route('password.request') }}">Lupa Password?</a>
    @endif
</div> --}}
<div class="text-center">
    <a class="small" href="{{ route('register') }}">Buat Akun Baru!</a>
</div>
@endsection