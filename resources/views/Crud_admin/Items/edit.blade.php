@extends('kerangka.master')

@section('title', 'Edit Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 text-center">Edit Produk</h4>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <form action="{{ route('Items.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Produk -->
                <div class="form-group mb-3">
                    <label for="nama_barang" class="form-label">Nama Produk</label>
                    <input 
                        type="text" 
                        id="nama_barang" 
                        name="nama_barang" 
                        class="form-control @error('nama_barang') is-invalid @enderror" 
                        value="{{ old('nama_barang', $item->nama_barang) }}" 
                        required>
                    @error('nama_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="form-group mb-3">
                    <label for="categories_id" class="form-label">Kategori</label>
                    <select 
                        id="categories_id" 
                        name="categories_id" 
                        class="form-select @error('categories_id') is-invalid @enderror">
                        <option value="" {{ old('categories_id', $item->categories_id) === null ? 'selected' : '' }}>
                            Tidak Ada
                        </option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ old('categories_id', $item->categories_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('categories_id')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Stok -->
                {{-- <div class="form-group mb-3">
                    <label for="stock" class="form-label">Stok</label>
                    <input 
                        type="number" 
                        id="stock" 
                        name="stock" 
                        class="form-control @error('stock') is-invalid @enderror" 
                        value="{{ old('stock', $item->stock) }}" 
                        min="1" 
                        max="99999" 
                        required>
                    @error('stock')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div> --}}
                <select name="status_pinjaman" id="status_pinjaman" class="form-control">
                    <option value="" {{ old('status_pinjaman', $item->status_pinjaman ?? '') == '' ? 'selected' : '' }}>-- Pilih Status --</option>
                    <option value="bisa di pinjam" {{ old('status_pinjaman', $item->status_pinjaman ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="tidak bisa di pinjam" {{ old('status_pinjaman', $item->status_pinjaman ?? '') == 'tidak tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                </select>
                <select name="Kondisi_barang" id="Kondisi_barang" class="form-control">
                    <option value="" {{ old('Kondisi_barang', $item->Kondisi_barang ?? '') == '' ? 'selected' : '' }}>-- Pilih Status --</option>
                    <option value="bisa di pinjam" {{ old('Kondisi_barang', $item->Kondisi_barang ?? '') == 'barang rusak' ? 'selected' : '' }}>barang rusak</option>
                    <option value="tidak bisa di pinjam" {{ old('Kondisi_barang', $item->Kondisi_barang ?? '') == 'normal' ? 'selected' : '' }}>normal</option>
                </select>
                

                <!-- Gambar -->
                <div class="form-group mb-3">
                    <label for="photo_barang" class="form-label">Gambar Produk</label>
                    <input 
                        type="file" 
                        id="photo_barang" 
                        name="photo_barang" 
                        class="form-control @error('photo_barang') is-invalid @enderror">
                    @if ($item->photo_barang)
                        <small class="text-muted d-block mt-2">Gambar saat ini:</small>
                        <img src="{{ asset('storage/' . $item->photo_barang) }}" alt="Gambar Produk" class="img-thumbnail mt-1" style="max-height: 150px; border-radius: 30px;">
                    @endif
                    @error('photo_barang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('Items.index') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
