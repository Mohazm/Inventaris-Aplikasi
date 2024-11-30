@extends('kerangka.master')

@section('title', 'Tambah Supplier')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Tambah Supplier</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
            
                <div class="form-group mb-3">
                    <label for="nama_supplier">Nama Supplier</label>
                    <input type="text" id="nama_supplier" name="nama_supplier" class="form-control" value="{{ old('nama_supplier') }}" required>
                    @error('nama_supplier')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="form-group mb-3">
                    <label for="kontak">Kontak</label>
                    <input type="text" id="kontak" name="kontak" class="form-control" value="{{ old('kontak') }}">
                    @error('kontak')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            
                <div class="form-group mb-3">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="form-control" value="{{ old('alamat') }}" required>
                    @error('alamat')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>      
        </div>
    </div>
</div>
@endsection
