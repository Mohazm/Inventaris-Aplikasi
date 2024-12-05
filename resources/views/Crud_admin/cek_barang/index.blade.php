@extends('kerangka.master') <!-- assuming you have a layout -->

@section('content')
    <div class="container mt-5">
        <h1 class="mb-4 text-center">Daftar Cek Barang</h1>

        <!-- Display success message if any -->
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

        <!-- Button to go to create page -->

        <a href="{{ route('cekbarang.create') }}" class="btn btn-primary">Tambah Cek Barang Baru</a>

        {{-- <a href="{{ route('cekbarang.create') }}" class="btn btn-primary">Tambah Cek Barang Baru</a> --}}


        <!-- Cek Barang Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Item</th>
                        <th>Kondisi Barang</th>
                        <th>Descripsi</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cekBarang as $cek)
                        <tr>
                            <td>{{ $cek->id }}</td>
                            <td>{{ $cek->item->nama_barang }}</td> <!-- Assuming the Item model has a 'name' attribute -->
                            <td>
                                @if ($cek->kondisi_barang == 'baik')
                                    <span class="badge bg-success">Baik</span>
                                @elseif($cek->kondisi_barang == 'rusak ringan')
                                    <span class="badge bg-warning">Rusak Ringan</span>
                                @elseif($cek->kondisi_barang == 'rusak berat')
                                    <span class="badge bg-danger">Rusak Berat</span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>

                            <td>{{ $cek->descripsi }}</td>
                            <td class="text-center">
                                <a href="{{ route('cekbarang.show', $cek) }}" class="btn btn-info btn-sm">Lihat</a>
                                <a href="{{ route('cekbarang.edit', $cek->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('cekbarang.destroy', $cek) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Apakah Anda yakin?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
