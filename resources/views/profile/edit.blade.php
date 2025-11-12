{{-- Menggunakan layout SB Admin 2 --}}
@extends('layouts.app')

{{-- Judul Halaman --}}
@section('title', 'Edit Profil')

{{-- Konten Utama --}}
@section('content')
<div class="container-fluid">

    {{-- Page Heading --}}
    <h1 class="h3 mb-4 text-gray-800">Edit Profil</h1>

    {{-- 
        Breeze biasanya membagi form ke partials. 
        Kita include partials tersebut di sini, dibungkus card Bootstrap 
        agar lebih cocok dengan SB Admin 2.
    --}}
    
    <div class="row">
        {{-- Update Profile Information Form --}}
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-primary">Informasi Profil</h6>
                </div>
                <div class="card-body">
                     {{-- Include partial Breeze. Styling di dalamnya mungkin perlu disesuaikan (Tailwind -> Bootstrap) --}}
                     @include('profile.partials.update-profile-information-form') 
                </div>
            </div>
        </div>

        {{-- Update Password Form --}}
        <div class="col-lg-6 mb-4">
             <div class="card shadow">
                 <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-primary">Ubah Password</h6>
                 </div>
                 <div class="card-body">
                      {{-- Include partial Breeze --}}
                      @include('profile.partials.update-password-form')
                 </div>
             </div>
        </div>

         {{-- Delete Account Section --}}
         <div class="col-lg-12 mb-4"> {{-- Buat full width --}}
             <div class="card shadow border-left-danger"> {{-- Aksen Merah --}}
                <div class="card-header py-3">
                     <h6 class="m-0 font-weight-bold text-danger">Hapus Akun</h6>
                </div>
                <div class="card-body">
                     {{-- Include partial Breeze --}}
                     @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

{{-- Jika halaman profile butuh script khusus, push di sini --}}
{{-- @push('scripts')
    <script>...</script>
@endpush --}}