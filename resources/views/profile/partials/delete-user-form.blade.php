<section class="space-y-6"> {{-- Ganti dengan class margin Bootstrap jika perlu --}}
    <header>
        <h2 class="h5 font-weight-bold text-gray-900">
            {{ __('Hapus Akun') }}
        </h2>

        <p class="mt-1 text-muted small">
            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Sebelum menghapus akun Anda, harap unduh data atau informasi apa pun yang ingin Anda simpan.') }}
        </p>
    </header>

    {{-- Tombol Hapus Akun (Memanggil Modal Bootstrap) --}}
    <button
        type="button" {{-- Ganti type submit ke button --}}
        class="btn btn-danger" {{-- Ganti kelas Tailwind ke Bootstrap --}}
        data-toggle="modal" {{-- Atribut Bootstrap untuk modal --}}
        data-target="#confirm-user-deletion" {{-- Target ID Modal Bootstrap --}}
    >
        {{ __('Hapus Akun') }}
    </button>

    {{-- Modal Konfirmasi Bootstrap (Ganti Modal Breeze) --}}
    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form method="post" action="{{ route('profile.destroy') }}" class="p-0"> {{-- Form dipindah ke sini --}}
                @csrf
                @method('delete')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title font-weight-bold text-gray-900" id="confirmUserDeletionLabel">
                            {{ __('Apakah Anda yakin ingin menghapus akun Anda?') }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="mt-1 text-muted small">
                            {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
                        </p>

                        {{-- Input Password Konfirmasi --}}
                        <div class="mt-3 form-group">
                            <label for="password" class="sr-only">{{ __('Password') }}</label> {{-- sr-only untuk aksesibilitas --}}
                            <input
                                id="password"
                                name="password"
                                type="password"
                                class="form-control {{ $errors->userDeletion->has('password') ? 'is-invalid' : '' }}"
                                placeholder="{{ __('Password') }}"
                            />
                             @if ($errors->userDeletion->has('password'))
                                <div class="invalid-feedback mt-2"> {{-- Tambah mt-2 --}}
                                    {{ $errors->userDeletion->first('password') }}
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger ms-3"> {{-- Tambah margin kiri --}}
                            {{ __('Hapus Akun') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>