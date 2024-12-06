@extends('kerangka.master')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center text-primary fw-bold">Transaksi Barang Masuk</h1>
    
    <!-- Pesan sukses -->
    @if (session('success'))
    <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Barang Masuk</div>
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
    .img-rounded {
border-radius: 30px;
width: 100px;
height: 100px;
object-fit: cover;
}

</style>


    <!-- Filter berdasarkan kategori -->
    <form action="{{ route('Transactions_in.index') }}" method="GET" class="mb-4">
        <div class="row justify-content-center">    
            <!-- Filter Supplier -->
            <div class="col-md-4">
                <select name="supplier" class="form-select border-light shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Supplier</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ request('supplier') == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>
    

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('StafTransactions_in.create') }}" class="btn btn-primary rounded-pill shadow-sm">
            + Tambah Transaksi Baru
        </a>
    </div>

    <!-- Kartu Transaksi -->
    <div class="row g-4">
        @forelse ($transaction_ins as $transaction)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3 text-truncate">{{ $transaction->item->nama_barang }}</h5>
                        <p class="mb-2 text-muted">
                            <strong>Tanggal:</strong> {{ $transaction->tanggal_masuk }}
                        </p>
                        <p class="mb-2 text-muted">
                            <strong>Supplier:</strong> {{ $transaction->supplier->nama_supplier }}
                        </p>
                        <p class="mb-3 text-muted">
                            <strong>Jumlah:</strong> {{ $transaction->jumlah }}
                        </p>
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('Transactions_in.edit', $transaction->id) }}" class="btn btn-sm btn-outline-warning shadow-sm">
                                ✏️ Edit
                            </a>
                            <form action="{{ route('Transactions_in.destroy', $transaction->id) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
                                    🗑 Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-light text-center border shadow-sm py-5">
                    <p class="mb-0 fw-bold text-muted">Tidak ada transaksi barang masuk.</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $transaction_ins->links('pagination::bootstrap-5') }}
    </div>
</div>

<style>
    /* Custom card styles */
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border-radius: 15px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .btn {
        border-radius: 20px;
        font-size: 0.85rem;
    }

    .alert {
        font-size: 0.95rem;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endsection
