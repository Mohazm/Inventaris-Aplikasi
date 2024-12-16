@extends('kerangka.master')

@section('content')
    <h1>Daftar Guru</h1>
    <a href="{{ route('teacher.create') }}" class="btn btn-primary">Tambah Guru</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Telepon</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teacher as $teacher)
                <tr>
                    <td>{{ $teacher->id }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->email }}</td>
                    <td>{{ $teacher->phone }}</td>
                    <td>
                        <a href="{{ route('teacher.show', $teacher->id) }}" class="btn btn-info">Detail</a>
                        <a href="{{ route('teacher.edit', $teacher->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('teacher.destroy', $teacher->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
