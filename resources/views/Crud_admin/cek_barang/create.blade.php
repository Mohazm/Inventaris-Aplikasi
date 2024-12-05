@extends('kerangka.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Create New Cek Barang</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cekbarang.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="item_id">Item</label>
            <select name="item_id" id="item_id" class="form-control">
                @foreach ($items as $item)
                    <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>  <!-- Assuming 'nama_barang' is the field for the item name -->
                @endforeach
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="kondisi_barang">Kondisi Barang</label>
            <select name="kondisi_barang" id="kondisi_barang" class="form-control">
                <option value="baik">Baik</option>
                <option value="rusak ringan">Rusak Ringan</option>
                <option value="rusak berat">Rusak Berat</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label for="descripsi">Descripsi</label>
            <textarea name="descripsi" id="descripsi" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save</button>
    </form>
</div>
@endsection
