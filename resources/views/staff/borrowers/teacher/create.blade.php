@extends('kerangka.staff')

@section('content')
    <h1>Tambah Guru</h1>

    <form action="{{ route('stafteacher.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
@endsection
