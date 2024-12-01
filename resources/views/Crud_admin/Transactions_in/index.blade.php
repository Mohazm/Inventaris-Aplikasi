@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Transaksi Barang Masuk</h1>

    <!-- Pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabel Transaksi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Masuk</th>
                <th>Nama Barang</th>
                <th>Supplier</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transaction_ins as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->tanggal_masuk }}</td>
                    <td>{{ $transaction->item->nama_barang }}</td>
                    <td>{{ $transaction->supplier->nama_supplier }}</td>
                    <td>{{ $transaction->jumlah }}</td>
                    <td>
                        <a href="{{ route('Transactions_in.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('Transactions_in.destroy', $transaction->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi barang masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    {{ $transaction_ins->links() }}

    <a href="{{ route('Transactions_in.create') }}" class="btn btn-primary mt-4">Tambah Transaksi Baru</a>
</div>
@endsection
