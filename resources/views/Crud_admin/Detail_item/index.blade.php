@extends('kerangka.master')

@section('content')
<div class="container my-5">
    <h1 class="text-center mb-4">Detail Barang</h1>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Kode Barang</th>
                <th scope="col">Kondisi</th>
                <th scope="col">Deskripsi</th>
                <th scope="col">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detail_items as $detailItem)
            <tr>
                <td>{{ $detailItem->kode_barang }}</td>
                <td>{{ $detailItem->kondisi_barang }}</td>
                <td>{{ $detailItem->deskripsi ?? 'Tidak ada deskripsi' }}</td>
                <td>
                    <a href="{{ route('details.edit', ['kode_barang' => $detailItem->kode_barang]) }}" class="btn btn-primary btn-sm">
                        Cek Barang
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Menampilkan navigasi pagination jika ada lebih dari satu halaman --}}
    {{ $detail_items->links() }}

    <!-- Tombol Kembali ke Daftar Produk -->
    <div class="text-center mt-4">
        <a href="{{ route('Items.index') }}" class="btn btn-secondary">Kembali ke Daftar Produk</a>
    </div>
</div>
@endsection
