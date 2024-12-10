@extends('kerangka.staff')

@section('content')
    <div class="container">
        <h1>Daftar Aktivitas</h1>

        <!-- Pesan sukses atau error -->
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tambah Aktivitas Button -->
        <a href="{{ route('activities.create') }}" class="btn btn-primary mb-3">Tambah Aktivitas</a>

        <!-- Tabel Aktivitas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Pengguna yang Mengisi</th> <!-- Menambahkan kolom pengguna -->
                    <th>Tanggal</th>
                    <th>Aktivitas</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity->user->name }}</td> <!-- Menampilkan nama pengguna -->
                        <td>{{ $activity->tanggal_isi }}</td>
                        <td>{{ $activity->activitas }}</td>
                        <td>
                            <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            
                            <!-- Hapus Aktivitas -->
                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
