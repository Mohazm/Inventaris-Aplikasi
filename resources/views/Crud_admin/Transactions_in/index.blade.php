@extends('kerangka.master')

@section('content')
<div class="container">
    <h1 class="mb-4 text-center">Riwayat Transaksi Barang Masuk</h1>

    <!-- Pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Filter berdasarkan kategori -->
    <form action="{{ route('Transactions_in.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>
            </div>
        </div>
    </form>

    <a href="{{ route('Transactions_in.create') }}" class="btn btn-primary mb-4">Tambah Transaksi Baru</a>

    <!-- Kartu Transaksi -->
    <div class="row g-3">
        @forelse ($transaction_ins as $transaction)
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="card border-light shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-2">{{ $transaction->item->nama_barang }}</h5>
                        {{-- <p class="mb-1 text-muted">
                            <small><strong>ID:</strong> {{ $transaction->id }}</small>
                        </p> --}}
                        <p class="mb-1 text-muted">
                            <small><strong>Tanggal:</strong> {{ $transaction->tanggal_masuk }}</small>
                        </p>
                        <p class="mb-1 text-muted">
                            <small><strong>Supplier:</strong> {{ $transaction->supplier->nama_supplier }}</small>
                        </p>
                        <p class="mb-3 text-muted">
                            <small><strong>Jumlah:</strong> {{ $transaction->jumlah }}</small>
                        </p>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="{{ route('Transactions_in.edit', $transaction->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('Transactions_in.destroy', $transaction->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">Tidak ada transaksi barang masuk.</div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $transaction_ins->links() }}
    </div>
</div>
@endsection
