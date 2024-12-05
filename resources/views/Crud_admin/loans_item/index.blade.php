@extends('kerangka.master')

@section('title', 'Daftar Peminjaman')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Daftar Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <a href="{{ route('loans_item.create') }}" class="btn btn-primary mb-3">Tambah Peminjaman</a>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Jumlah Pinjam</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans_items as $index => $loan)
                            <tr class="text-center">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loan->item->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                                <td>{{ $loan->tendik->name ?? 'Pengguna tidak ditemukan' }}</td>
                                <td>{{ $loan->jumlah_pinjam }}</td>
                                <td>
                                    {{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    {{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') : '-' }}
                                </td>
                                <td>
                                    <span class="badge 
                                        @if($loan->status === 'loading') bg-warning 
                                        @elseif($loan->status === 'dipakai') bg-primary 
                                        @elseif($loan->status === 'selesai') bg-success 
                                        @elseif($loan->status === 'ditolak') bg-danger 
                                        @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($loan->status === 'loading')
                                    <form id="accept-form-{{ $loan->id }}" action="{{ route('loans_item.accept', $loan->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="confirmAccept({{ $loan->id }})">Terima</button>
                                    </form>
                                        <form action="{{ route('loans_item.cancel', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')">Batal</button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($loan->status !== 'ditolak')
                                        <a href="{{ route('loans_item.edit', $loan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                        <form action="{{ route('loans_item.destroy', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">Delete</button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function confirmAccept(loanId) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat membatalkan setelah tindakan ini!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Terima!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('accept-form-' + loanId).submit();
                }
            });
        }
    </script>
    
@endsection
