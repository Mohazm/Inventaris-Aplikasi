@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="fw-bold">📦 Daftar Transaksi Barang Keluar</h1>
            <p class="text-muted">Kelola dan pantau pengeluaran barang dengan mudah.</p>
        </div>

        <!-- Pesan sukses -->
        @if (session('success'))
            <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Barang Keluar</div>
                    <small></small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    🎉 {{ session('success') }}
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

            /* Button & Table Styling */
            .table-responsive {
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                border-radius: 8px;
            }

            .table th,
            .table td {
                vertical-align: middle;
            }

            .btn-outline-danger,
            .btn-outline-warning {
                border-radius: 25px;
                font-size: 0.85rem;
            }

            .btn-outline-danger:hover,
            .btn-outline-warning:hover {
                border-color: #dc3545;
            }
        </style>

        <a href="{{ route('Transactions_out.create') }}" class="btn btn-primary btn-lg mb-3 shadow">
            <i class="bi bi-plus-circle"></i> Tambah Transaksi Baru
        </a>
        <form method="GET" action="{{ route('transactions.export', date('m')) }}" class="mb-3">
            <button type="submit" class="btn btn-success">
                <i class="bi bi-download"></i> Ekspor ke Excel
            </button>
        </form>


        <!-- Tabel Transaksi -->
        <div class="table-responsive shadow-lg rounded">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tanggal Keluar</th>
                        <th>Barang</th>
                        <th>Tujuan</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions_outs as $transaction)
                        <tr class="align-middle">
                            <td>
                                <span class="badge bg-primary rounded-pill">{{ $transaction->id }}</span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($transaction->tanggal_keluar)->format('d M Y') }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="bi bi-box-seam text-secondary fs-4"></i>
                                    </div>
                                    <span class="fw-bold">{{ $transaction->item->nama_barang }}</span>
                                </div>
                            </td>
                            <td>{{ $transaction->tujuan_keluar }}</td>
                            <td>
                                <span class="badge bg-success">{{ $transaction->jumlah }}</span>
                            </td>
                            <td>
                                <div class="d-flex gap-2 justify-content-center">
                                    <a href="{{ route('Transactions_out.edit', $transaction->id) }}"
                                        class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <form action="{{ route('Transactions_out.destroy', $transaction->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                <i class="bi bi-archive"></i> Tidak ada transaksi barang keluar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->

        {{ $transactions_outs->links() }}

    </div>
@endsection
