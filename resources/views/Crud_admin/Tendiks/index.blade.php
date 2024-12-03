@extends('kerangka.master')

@section('content')
<div class="container">
    <h1>Daftar Tendik</h1>
    <a href="{{ route('tendiks.create') }}" class="btn btn-primary mb-3">Tambah Tendik</a>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($tendiks as $tendik)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tendik->name }}</td>
                    <td>{{ $tendik->jabatan }}</td>
                    <td>
                        <a href="{{ route('tendiks.edit', $tendik->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('tendiks.destroy', $tendik->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
