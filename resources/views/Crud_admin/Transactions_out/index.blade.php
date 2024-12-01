@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Transaksi Barang Keluar</h1>

    <!-- Pesan sukses -->
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tabel Transaksi -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tanggal Keluar</th>
                <th>Barang</th>
                <th>Tujuan</th>
                <th>Jumlah</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($transactions_outs as $transaction)
                <tr>
                    <td>{{ $transaction->id }}</td>
                    <td>{{ $transaction->tanggal_keluar }}</td>
                    <td>{{ $transaction->item->nama_barang }}</td>
                    <td>{{ $transaction->tujuan_keluar }}</td>
                    <td>{{ $transaction->jumlah }}</td>
                    <td>
                        <a href="{{ route('Transactions_out.edit', $transaction->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('Transactions_out.destroy', $transaction->id) }}" method="POST" style="display: inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus transaksi ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada transaksi barang keluar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tombol Tambah Transaksi -->
    <a href="{{ route('Transactions_out.create') }}" class="btn btn-primary mt-4">Tambah Transaksi Baru</a>
</div>
@endsection
