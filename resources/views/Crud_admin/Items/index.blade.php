@extends('kerangka.master')

@section('title', 'Daftar Produk')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Daftar Produk</h4>

        {{-- Notifikasi Sukses --}}
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
            .table-hover tbody tr:hover {
                background-color: rgba(0, 123, 255, 0.1);
            }

            .badge {
                font-size: 0.9rem;
                padding: 0.4em 0.6em;
                text-transform: capitalize;
            }

            .table img {
                border-radius: 10px;
                width: 70px;
                height: 70px;
                object-fit: cover;
                transition: transform 0.2s;
            }

            .table img:hover {
                transform: scale(1.1);
            }

            .btn-action {
                margin: 0 5px;
            }

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
        </style>

        <div class="row mb-3">
            <div class="col-md-8">
                <form method="GET" action="{{ route('Items.index') }}" class="d-flex">
                    <select name="Kondisi_barang" id="Kondisi_barang" class="form-select me-2">
                        <option value="">-- Filter Kondisi Barang --</option>
                        <option value="normal" {{ request('Kondisi_barang') === 'normal' ? 'selected' : '' }}>Normal
                        </option>
                        <option value="barang rusak" {{ request('Kondisi_barang') === 'barang rusak' ? 'selected' : '' }}>
                            Barang Rusak
                        </option>
                    </select>
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('Items.index') }}" class="btn btn-secondary">Reset</a>
                </form>
            </div>
        </div>

        <a href="{{ route('Items.create') }}" class="btn btn-primary mb-3">Tambah Produk</a>
        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Total Barang:</strong></p>
                <ul>
                    <li>Normal: {{ $countNormal }}</li>
                    <li>Barang Rusak: {{ $countRusak }}</li>
                </ul>
            </div>
        </div>


        <div class="card shadow-sm border-light">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kategori</th>
                            <th>Status Peminjaman</th>
                            <th>Stok</th>
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
                                <td>
                                    <span
                                        class="badge
                                        @if ($item->status_pinjaman === 'Belum di Atur') bg-warning
                                        @elseif($item->status_pinjaman === 'bisa di pinjam')
                                            bg-success
                                        @elseif($item->status_pinjaman === 'tidak bisa di pinjam')
                                            bg-danger
                                        @elseif($item->status_pinjaman === null || $item->status_pinjaman === '')
                                            bg-secondary @endif">
                                        {{ ucfirst($item->status_pinjaman ?? 'Belum di Atur') }}
                                    </span>
                                </td>


                                <td>{{ $item->stock }}</td>
                                <td>
                                    <img src="{{ asset('storage/' . $item->photo_barang) }}" alt="Gambar Produk">
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('Items.edit', $item->id) }}"
                                            class="btn btn-outline-primary btn-sm btn-action">
                                            <i class="bx bx-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('Items.destroy', $item->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm btn-action"
                                                onclick="return confirm('Yakin ingin menghapus produk ini?')">
                                                <i class="bx bx-trash"></i> Hapus
                                            </button>
                                        </form>
                                        <a href="{{ route('details.index', ['itemId' => $item->id]) }}" class="btn btn-outline-info btn-sm btn-action">
                                            <i class="bx bx-show"></i> Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Tidak ada data produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
