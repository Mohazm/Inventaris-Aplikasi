@extends('kerangka.master')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Peminjam</h1>

        <form id="borrower-form">
            @csrf
            <div class="mb-3">
                <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" id="nama_peminjam"
                    name="nama_peminjam" value="{{ old('nama_peminjam') }}">
                @error('nama_peminjam')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                    name="no_telp" value="{{ old('no_telp') }}">
                @error('no_telp')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" id="save-borrower" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('borrower-form');
            const borrowerNameInput = document.getElementById('nama_peminjam');
            const borrowerPhoneInput = document.getElementById('no_telp');

            // Tangani submit form via AJAX
            form.addEventListener('submit', (e) => {
                e.preventDefault(); // Cegah pengiriman form biasa

                const name = borrowerNameInput.value.trim();
                const phone = borrowerPhoneInput.value.trim();

                // Validasi client-side
                if (!name) {
                    alert('Nama peminjam harus diisi.');
                    return;
                }

                if (!phone) {
                    alert('Nomor telepon harus diisi.');
                    return;
                }

                if (phone.length !== 12) {
                    alert('Nomor telepon harus terdiri dari 12 digit.');
                    return;
                }

                // Kirim data dengan AJAX menggunakan fetch
                fetch('{{ route('borrowers.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nama_peminjam: name,
                        no_telp: phone,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Menampilkan data yang dikirim dari server

                    if (data.id) {
                        // Jika data berhasil disimpan, arahkan pengguna ke halaman daftar peminjam
                        window.location.href = "{{ route('borrowers.index') }}";
                    } else {
                        alert('Terjadi kesalahan saat menyimpan peminjam.');
                    }
                })
                .catch(err => alert('Terjadi kesalahan: ' + err.message));
            });
        });
    </script>
@endsection
