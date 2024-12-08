@extends('kerangka.master')

@section('title', 'Edit Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Edit Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <form action="{{ route('loans_item.update', $loans_items->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="item_id" class="form-label">Pilih Barang</label>
                        <select class="form-select @error('item_id') is-invalid @enderror" id="item_id" name="item_id">
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}" 
                                        {{ $item->id == $loans_items->item_id ? 'selected' : '' }}>
                                    {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('item_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="borrower_id" class="form-label">Pilih Peminjam</label>
                        <select class="form-select @error('borrower_id') is-invalid @enderror" id="borrower_id" name="borrower_id">
                            @foreach ($borrowers as $borrower)
                                <option value="{{ $borrower->id }}" 
                                        {{ $borrower->id == $loans_items->borrower_id ? 'selected' : '' }}>
                                    {{ $borrower->nama_peminjam }}
                                </option>
                            @endforeach
                        </select>
                        @error('borrower_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                        <input type="number" class="form-control @error('jumlah_pinjam') is-invalid @enderror" id="jumlah_pinjam" name="jumlah_pinjam" min="1"
                               value="{{ old('jumlah_pinjam', $loans_items->jumlah_pinjam) }}">
                        @error('jumlah_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="datetime-local" class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam" name="tanggal_pinjam"
                               value="{{ old('tanggal_pinjam', \Carbon\Carbon::parse($loans_items->tanggal_pinjam)->format('Y-m-d\TH:i')) }}">
                        @error('tanggal_pinjam')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="datetime-local" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali"
                               value="{{ old('tanggal_kembali', \Carbon\Carbon::parse($loans_items->tanggal_kembali)->format('Y-m-d\TH:i')) }}">
                        @error('tanggal_kembali')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman</label>
                        <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman" name="tujuan_peminjaman" rows="3">{{ old('tujuan_peminjaman', $loans_items->tujuan_peminjaman) }}</textarea>
                        @error('tujuan_peminjaman')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('loans_item.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div> 
    </div>
@endsection
