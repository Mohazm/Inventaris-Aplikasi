@extends('kerangka.master')

@section('content')
    <h1>Tambah Guru</h1>

    <form id="teacherForm">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>

    <script>
        // Menangani form submission
        document.getElementById('teacherForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Mencegah reload halaman

            const formData = new FormData(this);
            const csrfToken = document.querySelector('meta[name="csrf-token"]');

            // Mengirim data form menggunakan Fetch API
            fetch("{{ route('teacher.store') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.redirect) {
                    // Mengarahkan ke halaman borrowers.index setelah berhasil
                    window.location.href = data.redirect;
                } else {
                    alert('Gagal menyimpan data. Silakan coba lagi.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim data.');
            });
        });
    </script>
@endsection
