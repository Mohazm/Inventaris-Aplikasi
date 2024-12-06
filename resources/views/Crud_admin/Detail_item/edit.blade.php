<!-- resources/views/Crud_admin/DetailItems/edit.blade.php -->
@extends('kerangka.master')

@section('content')
<div class="container">
    <h1>Cek Barang: {{ $detail_items->kode_barang }}</h1>

    <form action="{{ route('details.update', ['kode_barang' => $detail_items->kode_barang]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="kondisi_barang">Kondisi Barang</label>
            <select name="kondisi_barang" id="kondisi_barang" class="form-control" required>
                <option value="Normal" {{ $detail_items->kondisi_barang == 'Normal' ? 'selected' : '' }}>Normal</option>
                <option value="Rusak" {{ $detail_items->kondisi_barang == 'Rusak' ? 'selected' : '' }}>Rusak</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="deskripsi">Deskripsi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4">{{ $detail_items->deskripsi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>

    <a href="{{ route('details.index', ['itemId' => $detail_items->item_id]) }}" class="btn btn-secondary mt-3">Kembali</a>
</div>
@endsection
