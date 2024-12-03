@extends('kerangka.master')

@section('content')
<div class="container">
    <h1>Edit Tendik</h1>
    <form action="{{ route('tendiks.update', $tendik->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Nama</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $tendik->name) }}" required>
            @error('name')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="mb-3">
            <label for="jabatan" class="form-label">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control" value="{{ old('jabatan', $tendik->jabatan) }}" required>
            @error('jabatan')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('tendiks.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
