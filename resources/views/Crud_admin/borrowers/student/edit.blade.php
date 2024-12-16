@extends('kerangka.master')

@section('content')
    <h1>Edit Siswa</h1>

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ $student->name }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control" value="{{ $student->email }}" required>
        </div>

        <div class="form-group">
            <label for="phone_number">Nomor Telepon</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control" value="{{ $student->phone_number }}">
        </div>

        <button type="submit" class="btn btn-warning">Perbarui</button>
    </form>
@endsection
