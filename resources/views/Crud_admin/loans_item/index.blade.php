@extends('kerangka.master')

@section('title', 'Daftar Peminjaman')

@section('content')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.bootstrap5.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>

    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Daftar Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <a href="{{ route('loans_item.create') }}" class="btn btn-primary">Tambah Peminjaman</a>
                    <form action="{{ route('loans_item.checkOverdue') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-secondary">Perbarui Status Overdue</button>
                    </form>
                </div>
                
                <!-- Tabel -->
                <table id="example" class="table table-bordered table-striped table-sm">
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
                                <td>{{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y, H:i') : '-' }}</td>
                                <td>{{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                <td>
                                    <span class="badge @if ($loan->status === 'loading') bg-warning @elseif($loan->status === 'dipakai') bg-primary @elseif($loan->status === 'selesai') bg-success @elseif($loan->status === 'ditolak') bg-danger @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($loan->status === 'loading')
                                        <form id="accept-form-{{ $loan->id }}" action="{{ route('loans_item.accept', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-success" onclick="confirmAccept({{ $loan->id }})">Terima</button>
                                        </form>
                                        <form action="{{ route('loans_item.cancel', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')">Batal</button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($loan->status !== 'ditolak')
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('loans_item.edit', $loan->id) }}" class="btn btn-sm btn-warning" style="margin-right: 10px;">Edit</a>
                                            <form action="{{ route('loans_item.destroy', $loan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">Delete</button>
                                            </form>
                                        </div>
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
        $(document).ready(function() {
            // Cek jika DataTable sudah diinisialisasi pada tabel #example
            if (!$.fn.DataTable.isDataTable('#example')) {
                $('#example').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        'copy', 'excel', 'pdf', 'colvis'
                    ],
                    language: {
                        url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/Indonesian.json'
                    }
                });
            }
        });

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
