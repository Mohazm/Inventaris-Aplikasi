@extends('kerangka.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Cek Barang</h1>

    <!-- Display validation errors if any -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('cekbarang.update', $id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="items_id">Item</label>
            <select name="items_id" id="items_id" class="form-control">
                @foreach ($items as $item)
                    <option value="{{ $item->id }}" {{ $id->items_id == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_barang }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="kondisi_barang">Kondisi Barang</label>
            <select name="kondisi_barang" id="kondisi_barang" class="form-control">
                <option value="baik" {{ $id->kondisi_barang == 'baik' ? 'selected' : '' }}>Baik</option>
                <option value="rusak ringan" {{ $id->kondisi_barang == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                <option value="rusak berat" {{ $id->kondisi_barang == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
            </select>
        </div>

        <div class="form-group">
            <label for="descripsi">Descripsi</label>
            <textarea name="descripsi" id="descripsi" class="form-control" rows="4">{{ $id->descripsi }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
