@extends('kerangka.master')

@section('content')
<div class="container">
    <h2>Tambah User</h2>

    <form action="{{ route('users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" class="form-control" required>
                <option value="staff">Staff</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>
@endsection
