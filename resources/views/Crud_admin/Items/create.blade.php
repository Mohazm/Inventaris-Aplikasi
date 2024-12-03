@extends('kerangka.master')

@section('title', 'Tambah Produk')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4">Tambah Produk</h4>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('Items.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Nama Produk -->
                <div class="form-group mb-3">
                    <label for="nama_barang">Nama Produk</label>
                    <input type="text" id="nama_barang" name="nama_barang" class="form-control" value="{{ old('nama_barang') }}" required>
                </div>

                <!-- Kategori -->
                <div class="form-group mb-3">
                    <label for="categories_id">Kategori</label>
                    <select id="categories_id" name="categories_id" class="form-control">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stok -->
                <div class="form-group mb-3">
                    <label for="stock">Stok</label>
                    <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}"  max="99999" required>
                </div>

                <!-- Kondisi Barang -->
                <div class="form-group mb-3">
                    <label for="kondisi_barang">Kondisi Barang</label>
                    <select id="kondisi_barang" name="kondisi_barang" class="form-control">
                        <option value="baik" {{ old('kondisi_barang') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak ringan" {{ old('kondisi_barang') == 'rusak ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak berat" {{ old('kondisi_barang') == 'rusak berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>

                <!-- Foto Produk -->
                <div class="form-group mb-3">
                    <label for="photo_barang">Foto Produk</label>
                    <input type="file" id="photo_barang" name="photo_barang" class="form-control" accept="image/*" onchange="previewImage(event)">
                    <div class="mt-3">
                        <img id="preview" src="#" alt="Preview Gambar" style="display: none; max-height: 200px; border-radius: 10px; object-fit: contain;">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    }
</script>
@endsection
