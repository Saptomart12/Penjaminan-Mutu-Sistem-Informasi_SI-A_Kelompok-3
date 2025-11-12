{{-- Menggunakan layout induk SB Admin 2 --}}
@extends('layouts.app')

{{-- Mengatur judul halaman --}}
@section('title', 'Dashboard')

{{-- Memasukkan konten utama --}}
@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    {{-- Alert Sukses --}}
    <div class="alert alert-success" role="alert">
        <h4 class="alert-heading">Berhasil Login!</h4>
        <p>Anda berhasil masuk ke halaman dashboard. Ini membuktikan bahwa fitur <b>Login</b> dan <b>Register</b> (via Laravel Breeze) sudah berfungsi dengan benar.</p>
        <hr>
    </div>

    {{-- 
    Di sinilah nanti kita akan menempatkan
    5 fragmen (Tugas, Visualisasi, Prediksi IP, dll)
    --}}

</div>
@endsection