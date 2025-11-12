<section>
    <header>
        {{-- Menggunakan kelas Bootstrap untuk heading --}}
        <h2 class="h5 font-weight-bold text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>

        <p class="mt-1 text-muted small">
            {{ __("Perbarui informasi profil akun Anda dan alamat email.") }}
        </p>
    </header>

    {{-- Form action sudah benar dari Breeze --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- Ganti kelas Tailwind dengan Bootstrap --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-4">
        @csrf
        @method('patch')

        {{-- Form Group untuk Nama --}}
        <div class="form-group mb-3">
            <label for="name" class="form-label font-weight-bold">{{ __('Nama') }}</label>
            <input id="name" name="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            {{-- Error message Bootstrap --}}
            @if ($errors->has('name'))
                <div class="invalid-feedback">
                    {{ $errors->first('name') }}
                </div>
            @endif
        </div>

        {{-- Form Group untuk Email --}}
        <div class="form-group mb-3">
            <label for="email" class="form-label font-weight-bold">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email', $user->email) }}" required autocomplete="username">
             @if ($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors->first('email') }}
                </div>
            @endif

            {{-- Info Verifikasi Email (jika perlu) --}}
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="btn btn-link p-0 m-0 align-baseline text-decoration-none"> {{-- Styling tombol link Bootstrap --}}
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-weight-bold text-sm text-success">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

            {{-- Pesan Sukses (jika ada) --}}
            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success ms-3" {{-- Tambah margin kiri --}}
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>