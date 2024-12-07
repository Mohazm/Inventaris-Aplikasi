@extends('kerangka.master')

@section('title', 'Tambah Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Tambah Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <form action="{{ route('loans_item.store') }}" method="POST">
                    @csrf

                    <!-- Pilih Barang -->
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Pilih Barang</label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="item_id" name="item_id">
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

                    <!-- Pilih Peminjam -->
                    <div class="mb-3">
                        <label for="tendik_id" class="form-label">Pilih Peminjam</label>
                        <select class="form-select @error('tendik_id') is-invalid @enderror" id="tendik_id" name="tendik_id">
                            <option value="" disabled selected>-- Pilih Peminjam --</option>
                            @foreach ($tendiks as $tendik)
                                <option value="{{ $tendik->id }}" {{ old('tendik_id') == $tendik->id ? 'selected' : '' }}>
                                    {{ $tendik->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tendik_id')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jumlah Pinjam -->
                    <div class="mb-3">
                        <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                        <input type="number" class="form-control @error('jumlah_pinjam') is-invalid @enderror" id="jumlah_pinjam" name="jumlah_pinjam" min="1" value="{{ old('jumlah_pinjam') }}">
                        @error('jumlah_pinjam')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="datetime-local" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}">
                        @error('tanggal_pinjam')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="datetime-local" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}">
                        @error('tanggal_kembali')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tujuan Peminjaman -->
                    <div class="mb-3">
                        <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman</label>
                        <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman" name="tujuan_peminjaman" rows="3">{{ old('tujuan_peminjaman') }}</textarea>
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
@endsection
