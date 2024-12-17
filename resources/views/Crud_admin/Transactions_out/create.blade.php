@extends('kerangka.master') <!-- Sesuaikan dengan layout Anda -->

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Transaksi Barang Keluar</h1>

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

    <!-- Form Transaksi Barang Keluar -->
    <form action="{{ route('Transactions_out.store') }}" method="POST" id="transaction-form">
        @csrf

        <!-- Tanggal Keluar -->
        <div class="mb-3">
            <label for="tanggal_keluar" class="form-label">Tanggal Keluar</label>
            <input 
                type="date" 
                name="tanggal_keluar" 
                id="tanggal_keluar" 
                class="form-control" 
                value="{{ old('tanggal_keluar', date('Y-m-d')) }}" 
                readonly>
        </div>

        <!-- Barang -->
        <div class="mb-3">
            <label for="item_id" class="form-label">Barang</label>
            <select name="item_id" id="item_id" class="form-select" required>
                <option value="">-- Pilih Barang --</option>
                @foreach ($items as $item)
                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tujuan -->
        <div class="mb-3">
            <label for="tujuan_keluar" class="form-label">Tujuan</label>
            <input 
                type="text" 
                name="tujuan_keluar" 
                id="tujuan_keluar" 
                class="form-control" 
                value="{{ old('tujuan_keluar') }}" 
                required>
        </div>

        <!-- Jumlah -->
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah</label>
            <input 
                type="number" 
                name="jumlah" 
                id="jumlah" 
                class="form-control" 
                value="{{ old('jumlah') }}" 
                min="1" 
                required>
        </div>

        <!-- Kode Barang (Auto-Generated) -->
        <div class="mb-3">
            <input 
                type="hidden" 
                name="kode_barang" 
                id="kode_barang" 
                class="form-control" 
                value="{{ old('kode_barang', 'AUTO') }}" 
                readonly>
        </div>

        <!-- Tombol Simpan -->
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('Transactions_out.index') }}" class="btn btn-secondary">Batal</a>
    </form>

    <!-- Tombol untuk menambah form baru -->
    <button type="button" class="btn btn-success mt-3" id="add-form-btn">Tambah Form Baru</button>

</div>

<script>
    // Tombol untuk menambah form baru
    document.getElementById('add-form-btn').addEventListener('click', function() {
        // Clone the first form to create a new one
        var form = document.getElementById('transaction-form').cloneNode(true);
        form.id = 'new-transaction-form'; // Give the new form a new id

        // Reset the form fields (but preserve the item_id)
        form.reset();
        form.querySelector('[name="item_id"]').value = ''; // Reset item_id
        
        // Tambahkan tombol Hapus di form baru
        var removeButton = document.createElement('button');
        removeButton.type = 'button';
        removeButton.classList.add('btn', 'btn-danger', 'mt-3');
        removeButton.textContent = 'Hapus Form';
        removeButton.addEventListener('click', function() {
            form.remove();
        });

        // Menambahkan tombol hapus ke dalam form
        form.appendChild(removeButton);

        // Append the new form below the current one
        document.querySelector('.container').appendChild(form);

        // Generate the kode_barang when a new form is added
        form.querySelector('#item_id').addEventListener('change', function() {
            var itemName = this.options[this.selectedIndex].text.split(' ')[0]; // Ambil 3 huruf pertama nama barang
            var randomCode = Math.random().toString(36).substring(2, 7).toUpperCase(); // Generate random 5 characters
            var kodeBarang = itemName.substring(0, 3).toUpperCase() + '-' + randomCode; // Combine

            form.querySelector('#kode_barang').value = kodeBarang;
        });
    });

    // Auto-generate Kode Barang for initial form
    document.getElementById('item_id').addEventListener('change', function() {
        var itemName = this.options[this.selectedIndex].text.split(' ')[0]; // Ambil 3 huruf pertama nama barang
        var randomCode = Math.random().toString(36).substring(2, 7).toUpperCase(); // Generate random 5 characters
        var kodeBarang = itemName.substring(0, 3).toUpperCase() + '-' + randomCode; // Combine

        document.getElementById('kode_barang').value = kodeBarang;
    });
</script>
@endsection
