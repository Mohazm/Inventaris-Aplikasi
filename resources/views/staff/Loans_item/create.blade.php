@extends('kerangka.staff')

@section('title', 'Tambah Peminjaman')

@section('content')

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4 text-center">Tambah</h4>

            <div class="card shadow-sm border-light">
                <div class="card-body">
                    <form action="{{ route('loans_item.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Barang -->
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Pilih Barang</label>
                            <select class="form-select @error('item_id') is-invalid @enderror" id="item_id"
                                name="item_id">
                                <option value="" disabled selected>-- Pilih Barang --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="borrower_id" class="form-label">Pilih Peminjam</label>
                            <select class="form-select" id="borrower_id" name="borrower_id">
                                <option value="" disabled selected>-- Pilih atau Tambahkan Peminjam --</option>
                                @foreach ($borrowers as $borrower)
                                    <option value="{{ $borrower->id }}">{{ $borrower->nama_peminjam }}</option>
                                @endforeach
                            </select>
                            <button type="button" id="add-borrower" class="btn btn-secondary mt-2">Tambah Peminjam</button>
                        </div>

                        <!-- Form untuk tambah peminjam -->
                        <div id="add-borrower-form" style="display:none;">
                            <div class="mb-3">
                                <label for="borrower_name" class="form-label">Nama Peminjam Baru</label>
                                <input type="text" class="form-control" id="borrower_name"
                                    placeholder="Masukkan nama peminjam">
                            </div>
                            <div class="mb-3">
                                <label for="borrower_phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="borrower_phone"
                                    placeholder="Masukkan nomor telepon">
                            </div>
                            <button type="button" id="save-borrower" class="btn btn-primary">Simpan</button>
                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const addBorrowerForm = document.getElementById('add-borrower-form');
                                const borrowerSelect = document.getElementById('borrower_id');
                                const addBorrowerBtn = document.getElementById('add-borrower');
                                const saveBorrowerBtn = document.getElementById('save-borrower');
                                const borrowerNameInput = document.getElementById('borrower_name');
                                const borrowerPhoneInput = document.getElementById('borrower_phone');

                                // Tampilkan form tambah peminjam
                                addBorrowerBtn.addEventListener('click', () => {
                                    addBorrowerForm.style.display = 'block';
                                });

                                // Simpan peminjam baru via Ajax
                                saveBorrowerBtn.addEventListener('click', () => {
                                    const name = borrowerNameInput.value.trim();
                                    const phone = borrowerPhoneInput.value.trim();
                                    if (!name) return alert('Nama peminjam harus diisi.');

                                    fetch('{{ route('borrowers.store') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                'Content-Type': 'application/json'
                                            },
                                            body: JSON.stringify({
                                                nama_peminjam: name,
                                                no_telp: phone,
                                            }),
                                        })
                                        .then(response => {
                                            if (!response.ok) {
                                                // Tangani kesalahan respons, bisa jadi HTML atau error lainnya
                                                return response.text().then(text => {
                                                    throw new Error('Terjadi kesalahan: ' + text);
                                                });
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            const option = new Option(data.nama_peminjam, data.id, true, true);
                                            borrowerSelect.add(option);
                                            borrowerNameInput.value = '';
                                            borrowerPhoneInput.value = '';
                                            addBorrowerForm.style.display = 'none';
                                        })
                                        .catch(err => alert('Terjadi kesalahan: ' + err.message));
                                });


                            });
                        </script>



                        <!-- Jumlah Pinjam -->
                        <div class="mb-3">
                            <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                            <input type="number" class="form-control @error('jumlah_pinjam') is-invalid @enderror"
                                id="jumlah_pinjam" name="jumlah_pinjam" min="1" value="{{ old('jumlah_pinjam') }}">
                            @error('jumlah_pinjam')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Pinjam -->
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="datetime-local" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                                id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}">
                            @error('tanggal_pinjam')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="datetime-local" class="form-control @error('tanggal_kembali') is-invalid @enderror"
                                id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}">
                            @error('tanggal_kembali')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tujuan Peminjaman -->
                        <div class="mb-3">
                            <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman</label>
                            <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman"
                                name="tujuan_peminjaman" rows="3">{{ old('tujuan_peminjaman') }}</textarea>
                            @error('tujuan_peminjaman')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('loans_item.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>

    </body>

    </html>
@endsection
