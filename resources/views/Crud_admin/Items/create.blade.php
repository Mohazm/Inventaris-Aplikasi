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
                        <input type="text" id="nama_barang" name="nama_barang" class="form-control"
                            value="{{ old('nama_barang') }}" required>
                    </div>

                    <!-- Kategori -->
                    <div class="form-group mb-3">
                        <label for="categories_id">Kategori</label>
                        <select id="categories_id" name="categories_id" class="form-control" required>
                            <option value="" selected>Pilih atau Tambah Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('categories_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Foto Produk -->
                    <div class="form-group mb-3">
                        <label for="photo_barang">Foto Produk</label>
                        <input type="file" id="photo_barang" name="photo_barang" class="form-control" accept="image/*"
                            onchange="previewImage(event)">
                        <div class="mt-3">
                            <img id="preview" src="#" alt="Preview Gambar"
                                style="display: none; max-height: 200px; border-radius: 10px; object-fit: contain;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>

    <!-- CDN jQuery 3.6.0 -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
    <script>
        var $jq = jQuery.noConflict(); // Menggunakan $jq sebagai pengganti $

        $jq(document).ready(function() {
            // Inisialisasi Select2 pada elemen dengan id #categories_id
            $jq('#categories_id').select2({
                tags: true, // Mengaktifkan fitur tagging
                placeholder: 'Pilih atau Tambah Kategori', // Placeholder
                allowClear: true, // Mengizinkan untuk menghapus pilihan
                createTag: function(params) {
                    const term = $jq.trim(params
                        .term); // Mengambil teks kategori yang dimasukkan pengguna
                    if (term === '') {
                        return null; // Jika input kosong, jangan buat tag baru
                    }
                    // Mengembalikan tag baru sebagai opsi
                    return {
                        id: term, // ID adalah teks yang dimasukkan
                        text: term, // Teks yang ditampilkan
                        newOption: true // Menandai sebagai tag baru
                    };
                },
                templateResult: function(data) {
                    const $result = $jq('<span></span>');
                    $result.text(data.text);
                    if (data.newOption) {
                        $result.append(
                            ' <em>(Tambah Baru)</em>'); // Menambahkan keterangan untuk kategori baru
                    }
                    return $result;
                }
            });

            // Styling langsung untuk Select2
            $jq('#categories_id').data('select2').$dropdown.addClass('custom-select2-dropdown');
            $jq('#categories_id').data('select2').$container.css({
                "height": "38px",
                "border-radius": "5px",
                "border": "1px solid #ced4da",
                "padding": "6px"
            });

            $jq('.select2-container--default .select2-selection--single .select2-selection__rendered').css({
                "color": "#495057",
                "line-height": "24px"
            });

            $jq('.select2-container--default .select2-selection--single .select2-selection__arrow').css({
                "height": "36px"
            });
        });


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
