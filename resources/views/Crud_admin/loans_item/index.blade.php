@extends('kerangka.master')

@section('title', 'Daftar Peminjaman')

@section('content')
    @if (session('error'))
        <div class="bs-toast toast fade show bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <i class="bx bx-bell me-2"></i>
                <div class="me-auto fw-semibold">Peminjaman</div>
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
            <div class="me-auto fw-semibold">Category</div>
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
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    .badge {
        font-size: 0.9rem;
        padding: 0.4em 0.6em;
        text-transform: capitalize;
    }

    .table img {
        border-radius: 10px;
        width: 70px;
        height: 70px;
        object-fit: cover;
        transition: transform 0.2s;
    }

    .table img:hover {
        transform: scale(1.1);
    }

    .btn-action {
        margin: 0 5px;
    }

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
</style>

    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Title -->
        <h4 class="fw-bold py-3 mb-4 text-center">📋 Daftar Peminjaman</h4>

        <!-- Filter Form -->
        <form action="{{ route('loans_item.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                    <select name="tanggal_pinjam" id="tanggal_pinjam" class="form-control">
                        <option value="">Pilih Tanggal Pinjam</option>
                        @foreach ($tanggal_pinjam_options as $tanggal_pinjam)
                            <option value="{{ $tanggal_pinjam }}"
                                {{ request('tanggal_pinjam') == $tanggal_pinjam ? 'selected' : '' }}>
                                {{ $tanggal_pinjam }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                    <select name="tanggal_kembali" id="tanggal_kembali" class="form-control">
                        <option value="">Pilih Tanggal Kembali</option>
                        @foreach ($tanggal_kembali_options as $tanggal_kembali)
                            <option value="{{ $tanggal_kembali }}"
                                {{ request('tanggal_kembali') == $tanggal_kembali ? 'selected' : '' }}>
                                {{ $tanggal_kembali }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bx bx-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>

        <!-- Actions and Table -->
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

                <!-- Table -->
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
                            @forelse ($loans_items as $loan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $loan->item->nama_barang ?? 'Barang tidak ditemukan' }}</td>
                                    <td>
                                        @if ($loan->borrower->student)
                                            {{ $loan->borrower->student->name ?? 'Peminjam siswa tidak ditemukan' }}
                                        @elseif ($loan->borrower->teacher)
                                            {{ $loan->borrower->teacher->name ?? 'Peminjam guru tidak ditemukan' }}
                                        @else
                                            Peminjam tidak ditemukan
                                        @endif
                                    </td>
                                    <td>{{ $loan->jumlah_pinjam }}</td>
                                    <td>{{ $loan->tanggal_pinjam ? \Carbon\Carbon::parse($loan->tanggal_pinjam)->format('d M Y,') : '-' }}</td>
                                    <td>{{ $loan->tanggal_kembali ? \Carbon\Carbon::parse($loan->tanggal_kembali)->format('d M Y,') : '-' }}</td>
                                    <td>
                                        <span class="badge 
                                            @switch($loan->status)
                                                @case('menunggu') bg-warning @break
                                                @case('dipakai') bg-primary @break
                                                @case('selesai') bg-success @break
                                                @case('terlambat') bg-danger @break
                                                @default bg-secondary
                                            @endswitch">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </td>                                    
                                    <td>
                                        @if ($loan->status === 'menunggu')
                                        <form id="form-{{ $loan->id }}" action="{{ route('loans_item.accept', $loan->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success  me-2">
                                                <i class="bx bx-check-circle"></i> Terima
                                            </button>
                                        </form>                                        
                                            <form action="{{ route('loans_item.cancel', $loan->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm btn-danger  me-2"
                                                    onclick="return confirm('Yakin ingin membatalkan peminjaman ini?')">
                                                    <i class="bx bx-x-circle"></i> Batal
                                                </button>
                                            </form>
                                        @else
                                            <span>-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center d-flex  me-2">
                                            @if ($loan->status === 'dipakai' || $loan->status === 'terlambat')
                                                <form action="{{ route('loans_item.return', $loan->id) }}" method="POST"
                                                    class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary  me-2">
                                                        <i class="bx bx-undo"></i> Return
                                                    </button>
                                                </form>
                                            @endif
                                            <a href="{{ route('loans_item.detail', $loan->id) }}"
                                                class="btn btn-sm btn-info me-2">
                                                Detail
                                            </a>
                                            <a href="{{ route('loans_item.edit', $loan->id) }}"
                                                class="btn btn-sm btn-warning me-2">
                                                Edit
                                            </a>
                                            <form action="{{ route('loans_item.destroy', $loan->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger  me-2"
                                                    onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9">Tidak ada data peminjaman.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-success').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault(); // Cegah aksi submit form default
                const formId = this.closest('form').id; // Ambil ID form terdekat
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Peminjaman ini akan diterima!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Terima',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit(); // Submit form jika dikonfirmasi
                    }
                });
            });
        });
    });
</script>

@endsection
