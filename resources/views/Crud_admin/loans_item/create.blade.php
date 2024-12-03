@extends('kerangka.master')

@section('title', 'Tambah Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Tambah Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <form action="{{ route('loans_item.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="item_id" class="form-label">Pilih Barang</label>
                        <select class="form-select" id="item_id" name="item_id" required>
                            <option value="" disabled selected>-- Pilih Barang --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }} (Stok: {{ $item->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="tendik_id" class="form-label">Pilih Peminjam</label>
                        <select class="form-select" id="tendik_id" name="tendik_id" required>
                            <option value="" disabled selected>-- Pilih Peminjam --</option>
                            @foreach ($tendiks as $tendik)
                                <option value="{{ $tendik->id }}">{{ $tendik->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                        <input type="number" class="form-control" id="jumlah_pinjam" name="jumlah_pinjam" min="1" 
                               value="{{ old('jumlah_pinjam') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="datetime-local" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" 
                               value="{{ old('tanggal_pinjam') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="datetime-local" class="form-control" id="tanggal_kembali" name="tanggal_kembali" 
                               value="{{ old('tanggal_kembali') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman</label>
                        <textarea class="form-control" id="tujuan_peminjaman" name="tujuan_peminjaman" rows="3" required>{{ old('tujuan_peminjaman') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('loans_item.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>
@endsection
