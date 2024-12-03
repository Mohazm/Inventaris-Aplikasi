@extends('kerangka.staff')

@section('content')
<div class="container">
    <h1>Daftar Tendik</h1>

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
            </tr>
        </thead>
        <tbody>
            @forelse ($tendiks as $tendik)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tendik->name }}</td>
                    <td>{{ $tendik->jabatan }}</td>
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
