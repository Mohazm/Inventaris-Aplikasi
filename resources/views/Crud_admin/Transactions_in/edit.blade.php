@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Transaksi Barang Masuk</h1>

    <!-- Menampilkan error validasi -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Transactions_in.update', $transactions_ins->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
            <input type="date" name="tanggal_masuk" id="tanggal_masuk" class="form-control" value="{{ $transactions_ins->tanggal_masuk }}" required>
        </div>

        <div class="mb-3">
            <label for="item_id" class="form-label">Barang</label>
            <select name="item_id" id="item_id" class="form-select" required>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}" {{ $transactions_ins->item_id == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="form-select" required>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $transactions_ins->supplier_id == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama_supplier }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" value="{{ $transactions_ins->jumlah }}" min="0.01" step="0.01" required>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('Transactions_in.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
