@extends('kerangka.master')

@section('title', 'Daftar Peminjaman')

@section('content')
@if (session('error'))
<div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Pemimjam</div>
        <small></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('error') }}
    </div>
</div>
@endif
@if (session('success'))
<div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Pemimjam</div>
        <small></small>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('success') }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'));
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl, {
                delay: 3000
            });
        });
        toastList.forEach(toast => toast.show());
    });
</script>
@endif
<style>
/* Toast/Alert styling */
.toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1055;
    background-color: #28a745;
    color: #fff;
    border-radius: 0.25rem;
}

.toast .toast-body {
    padding: 0.75rem;
}

.toast .close {
    color: #fff;
    opacity: 0.8;
}
.img-rounded {
border-radius: 30px;
width: 100px;
height: 100px;
object-fit: cover;
}

</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4 text-center">📋 Daftar Peminjaman</h4>

    <div class="card shadow-sm border-light">
        <div class="card-body">
            <div class="d-flex justify-content-between mb-3">
                <a href="{{ route('loans_item.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Tambah Peminjaman
                </a>
                <form action="{{ route('loans_item.checkOverdue') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        <i class="bx bx-refresh"></i> Perbarui Status Overdue
                    </button>
                </form>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Peminjam</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Konfirmasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans_items as $index => $loan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $loan->item->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                                <td>{{ $loan->tendik->name ?? 'Pengguna tidak ditemukan' }}</td>
                                <td>{{ $loan->jumlah_pinjam }}</td>
                                <td>{{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y, H:i') : '-' }}</td>
                                <td>{{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y, H:i') : '-' }}</td>
                                <td>
                                    <span class="badge 
                                        @if ($loan->status === 'loading') bg-warning 
                                        @elseif($loan->status === 'dipakai') bg-primary 
                                        @elseif($loan->status === 'selesai') bg-success 
                                        @elseif($loan->status === 'ditolak') bg-danger 
                                        @endif">
                                        {{ ucfirst($loan->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($loan->status === 'loading')
                                      <div class="d-flex justify-content-center">
                                        <form id="accept-form-{{ $loan->id }}" action="{{ route('loans_item.accept', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="button" class="btn btn-sm btn-success me-3" onclick="confirmAccept({{ $loan->id }})">
                                                <i class="bx bx-check-circle"></i> Terima
                                            </button>
                                        </form>
                                        <form action="{{ route('loans_item.cancel', $loan->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')">
                                                <i class="bx bx-x-circle"></i> Batal
                                            </button>
                                        </form>
                                    @else
                                        <span>-</span>
                                    @endif
                                      </div>
                                </td>
                                <td>
                                    @if ($loan->status !== 'ditolak')
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('loans_item.edit', $loan->id) }}" class="btn btn-sm btn-warning me-2">
                                                <i class="bx bx-edit-alt"></i> Edit
                                            </a>
                                            <form action="{{ route('loans_item.destroy', $loan->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                                    <i class="bx bx-trash"></i> Delete
                                                </button>
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
