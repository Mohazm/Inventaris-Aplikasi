@extends('kerangka.staff')

@section('content')
    <h1 class="text-center mt-4 mb-3" style="position: relative;">Tambah Guru</h1>
    <hr>

    <div class="container">
        <form id="teacherForm" class="border p-4 rounded shadow-sm">
            @csrf
            <div class="form-group mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="phone" class="form-label">Nomor Telepon</label>
                <input type="text" id="phone" name="phone" class="form-control">
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-100">Simpan</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('teacherForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Mencegah reload halaman

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Mengirim data form menggunakan Fetch API
            fetch("{{ route('stafteacher.store') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        // Jika status bukan 200-299
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.redirect) {
                        // Mengarahkan ke halaman index setelah berhasil
                        window.location.href = data.redirect;
                    } else {
                        alert(data.message || 'Gagal menyimpan data. Silakan coba lagi.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengirim data. Silakan periksa koneksi Anda atau coba lagi.');
                });
        });
    </script>
@endsection
