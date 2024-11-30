@extends('kerangka.master')

@section('title', 'Daftar Kategori')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Daftar Kategori</h4>
        <hr>
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
        </style>
        <a href="{{ route('category.create') }}" class="btn btn-primary mb-3">Tambah Kategori</a>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <table class="table table-striped table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($catego as $p)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="text-muted">{{ $p->name }}</td>
                                <td>
                                    <a href="{{ route('category.edit', $p->id) }}"
                                        class="btn btn-outline-primary btn-sm">Edit</a>
                                    <form action="{{ route('category.destroy', $p->id) }}" method="POST"
                                        style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@section('scripts')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: "Data ini tidak bisa dikembalikan setelah dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection

@endsection
