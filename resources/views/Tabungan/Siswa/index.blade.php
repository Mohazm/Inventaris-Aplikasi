@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Tabungan Siswa</h2>
        <a href="{{ route('Tabungan.Siswa.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-600">
            + Tambah Tabungan
        </a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-500 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300 shadow-lg rounded-md">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Kelas</th>
                    <th class="px-4 py-2">Jumlah Tabungan</th>
                    <th class="px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tabungans as $tabungan)
                <tr class="border-b hover:bg-gray-100">
                    <td class="px-4 py-2">{{ $tabungan->nama_siswa }}</td>
                    <td class="px-4 py-2">{{ $tabungan->kelas }}</td>
                    <td class="px-4 py-2">Rp{{ number_format($tabungan->jumlah_tabungan, 0, ',', '.') }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('Tabungan.Siswa.edit', $tabungan->id) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('Tabungan.Siswa.destroy', $tabungan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $tabungans->links() }}
    </div>
</div>
@endsection
