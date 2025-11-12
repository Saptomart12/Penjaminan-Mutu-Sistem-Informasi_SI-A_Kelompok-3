<section>
    <header>
        <h2 class="h5 font-weight-bold text-gray-900">
            {{ __('Ubah Password') }}
        </h2>

        <p class="mt-1 text-muted small">
            {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    {{-- Ganti kelas Tailwind dengan Bootstrap --}}
    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        {{-- Form Group Password Saat Ini --}}
        <div class="form-group mb-3">
            <label for="update_password_current_password" class="form-label font-weight-bold">{{ __('Password Saat Ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control {{ $errors->updatePassword->has('current_password') ? 'is-invalid' : '' }}" autocomplete="current-password">
             @if ($errors->updatePassword->has('current_password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('current_password') }}
                </div>
            @endif
        </div>

        {{-- Form Group Password Baru --}}
        <div class="form-group mb-3">
            <label for="update_password_password" class="form-label font-weight-bold">{{ __('Password Baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control {{ $errors->updatePassword->has('password') ? 'is-invalid' : '' }}" autocomplete="new-password">
             @if ($errors->updatePassword->has('password'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('password') }}
                </div>
            @endif
        </div>

        {{-- Form Group Konfirmasi Password Baru --}}
        <div class="form-group mb-3">
            <label for="update_password_password_confirmation" class="form-label font-weight-bold">{{ __('Konfirmasi Password Baru') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control {{ $errors->updatePassword->has('password_confirmation') ? 'is-invalid' : '' }}" autocomplete="new-password">
             @if ($errors->updatePassword->has('password_confirmation'))
                <div class="invalid-feedback">
                    {{ $errors->updatePassword->first('password_confirmation') }}
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="d-flex align-items-center gap-4">
            <button type="submit" class="btn btn-primary">{{ __('Simpan') }}</button>

            {{-- Pesan Sukses --}}
            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-success ms-3"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>