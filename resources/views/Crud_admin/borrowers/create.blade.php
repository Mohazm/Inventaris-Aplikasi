@extends('kerangka.master')

@section('content')
    <div class="container">
        <h1 class="mb-4">Tambah Peminjam</h1>

        <form id="borrower-form">
            @csrf
            <div class="mb-3">
                <label for="borrower_type" class="form-label">Tipe Peminjam</label>
                <select class="form-control @error('borrower_type') is-invalid @enderror" id="borrower_type" name="borrower_type">
                    <option value="student" {{ old('borrower_type') == 'student' ? 'selected' : '' }}>Siswa</option>
                    <option value="teacher" {{ old('borrower_type') == 'teacher' ? 'selected' : '' }}>Guru</option>
                </select>
                @error('borrower_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="borrower_id" class="form-label">Pilih Siswa atau Guru</label>
                <select class="form-control @error('borrower_id') is-invalid @enderror" id="borrower_id" name="borrower_id">
                    <!-- Option for student and teacher will be dynamically filled -->
                </select>
                @error('borrower_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
                <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}">
                @error('nama_peminjam')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="no_telp" class="form-label">No Telepon</label>
                <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}">
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
            const borrowerTypeInput = document.getElementById('borrower_type');
            const borrowerIdInput = document.getElementById('borrower_id');
            const borrowerNameInput = document.getElementById('nama_peminjam');
            const borrowerPhoneInput = document.getElementById('no_telp');

            // Fungsi untuk memuat siswa atau guru berdasarkan tipe peminjam
            function loadBorrowerOptions(type) {
                fetch(`/api/${type}s`) // Menyesuaikan endpoint API untuk mengakses data siswa atau guru
                    .then(response => response.json())
                    .then(data => {
                        let options = '';
                        data.forEach(item => {
                            options += `<option value="${item.id}">${item.name}</option>`;
                        });
                        borrowerIdInput.innerHTML = options;
                    })
                    .catch(error => console.error('Error loading borrowers:', error));
            }

            // Tangani perubahan tipe peminjam
            borrowerTypeInput.addEventListener('change', (e) => {
                const type = e.target.value;
                loadBorrowerOptions(type);
            });

            // Inisialisasi dengan memuat data siswa terlebih dahulu
            loadBorrowerOptions('student');

            // Tangani submit form via AJAX
            form.addEventListener('submit', (e) => {
                e.preventDefault(); // Cegah pengiriman form biasa

                const name = borrowerNameInput.value.trim();
                const phone = borrowerPhoneInput.value.trim();
                const borrowerType = borrowerTypeInput.value;
                const borrowerId = borrowerIdInput.value;

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

                if (!borrowerId) {
                    alert('Pilih siswa atau guru terlebih dahulu.');
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
                        borrower_type: borrowerType,
                        borrower_id: borrowerId,
                        nama_peminjam: name,
                        no_telp: phone,
                    }),
                })
                .then(response => response.json())
                .then(data => {
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
