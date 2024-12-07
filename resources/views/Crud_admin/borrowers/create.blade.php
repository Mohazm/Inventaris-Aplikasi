@extends('kerangka.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Peminjam</h1>

    <form action="{{ route('borrowers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nama_peminjam" class="form-label">Nama Peminjam</label>
            <input type="text" class="form-control @error('nama_peminjam') is-invalid @enderror" id="nama_peminjam" name="nama_peminjam" value="{{ old('nama_peminjam') }}">
            @error('nama_peminjam')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_telp" class="form-label">No Telepon</label>
            <input type="text" class="form-control @error('no_telp') is-invalid @enderror" id="no_telp" name="no_telp" value="{{ old('no_telp') }}" min="1">
            @error('no_telp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
