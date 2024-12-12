@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Transaksi Barang Masuk</h1>

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

    <!-- Form utama -->
    <form action="{{ route('Transactions_in.store') }}" method="POST">
        @csrf

        <!-- Container untuk form-group -->
        <div id="form-container">
            <div class="form-group mb-4" id="form-template">
                <!-- Tanggal Masuk -->
                <div class="mb-3">
                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                    <input 
                        type="date" 
                        name="tanggal_masuk[]" 
                        class="form-control" 
                        value="{{ old('tanggal_masuk', date('Y-m-d')) }}" 
                        readonly>
                </div>

                <!-- Barang -->
                <div class="mb-3">
                    <label for="item_id" class="form-label">Barang</label>
                    <select name="item_id[]" class="form-select" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($items as $item)
                            <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Supplier -->
                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier</label>
                    <select name="supplier_id[]" class="form-select" required>
                        <option value="">Pilih Supplier</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->nama_supplier }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jumlah -->
                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah</label>
                    <input 
                        type="number" 
                        name="jumlah[]" 
                        class="form-control" 
                        value="{{ old('jumlah') }}" 
                        required>
                </div>

                <!-- Tombol Hapus -->
                <button type="button" class="btn btn-danger btn-sm remove-form">Hapus</button>
            </div>
        </div>

        <!-- Tombol Tambah Form -->
        <!-- Tombol Simpan dan Batal -->
        <button type="button" id="add-form" class="btn btn-success">+ Tambah Form</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('Transactions_in.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const formContainer = document.getElementById('form-container');
        const addFormButton = document.getElementById('add-form');

        // Tambah form baru
        addFormButton.addEventListener('click', () => {
            const formTemplate = document.getElementById('form-template');
            const clone = formTemplate.cloneNode(true);
            clone.id = ''; // Hapus ID duplikat
            clone.querySelector('.remove-form').addEventListener('click', () => {
                clone.remove();
            });
            formContainer.appendChild(clone);
        });

        // Hapus form pertama
        document.querySelectorAll('.remove-form').forEach(button => {
            button.addEventListener('click', (e) => {
                button.closest('.form-group').remove();
            });
        });
    });
</script>
@endsection
