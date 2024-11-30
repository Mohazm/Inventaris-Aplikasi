@extends('kerangka.master')

@section('title', 'Daftar Produk')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Daftar Produk</h4>

        @if (session('success'))
            <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Category</div>
                    <small></small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var toastElList = [].slice.call(document.querySelectorAll('.toast'));
                    var toastList = toastElList.map(function(toastEl) {
                        return new bootstrap.Toast(toastEl, {
                            delay: 3000
                        });
                    });
                    toastList.forEach(toast => toast.show());
                });
            </script>
        @endif
        <style>
            /* Toast/Alert styling */
            .toast {
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 1055;
                background-color: #28a745;
                color: #fff;
                border-radius: 0.25rem;
            }

            .toast .toast-body {
                padding: 0.75rem;
            }

            .toast .close {
                color: #fff;
                opacity: 0.8;
            }
            .img-rounded {
    border-radius: 30px;
    width: 100px;
    height: 100px;
    object-fit: cover;
}

        </style>

        <a href="{{ route('Items.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Kondisi</th>
                            <th>Gambar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->nama_barang }}</td>
                                <td>{{ $item->category->name ?? 'Tidak Ada' }}</td>
                                <td>{{ $item->stock }}</td>
                                <td>{{ ucfirst($item->kondisi_barang) }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $item->photo_barang) }}" alt="Gambar Produk"
                                        style="border-radius: 30px; width: 100px; height: 100px; object-fit: cover;">
                                </td>

                                <td>
                                    <a href="{{ route('Items.edit', $item->id) }}"
                                        class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form action="{{ route('Items.destroy', $item->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
