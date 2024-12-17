@extends('kerangka.master')

@section('content')
    <h1>Edit Guru</h1>

    <form action="{{ route('teacher.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $teacher->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $teacher->email }}" required>
        </div>

        <div class="form-group">
            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" class="form-control" value="{{ $teacher->phone }}">
        </div>

        <button type="submit" class="btn btn-warning">Perbarui</button>
    </form>
@endsection
