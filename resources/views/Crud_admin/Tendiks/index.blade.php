@extends('kerangka.master')

@section('content')
<div class="container my-4">
    <h1 class="text-center mb-4">📝Daftar Pemimjam</h1><hr>
    <a href="{{ route('tendiks.create') }}" class="btn btn-primary mb-3">
        <i class="bx bx-plus-circle"></i> Tambah Pemimjam
    </a>

    @if (session('success'))
    <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bx bx-bell me-2"></i>
            <div class="me-auto fw-semibold">Peninjam</div>
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
                return new bootstrap.Toast(toastEl, { delay: 3000 });
            });
            toastList.forEach(toast => toast.show());
        });
    </script>
    @endif

    @if ($tendiks->isEmpty())
        <div class="alert alert-warning text-center">
            <i class="bx bx-info-circle"></i> Tidak ada data Tendik.
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach ($tendiks as $tendik)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center mb-3">
                                <div class="img-rounded bg-primary text-center me-3">
                                    <i class="bx bxs-user text-white" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="card-title mb-0">{{ $tendik->name }}_</h5>
                            </div>
                            <p class="text-muted">
                                <span class="badge bg-info text-dark">{{ $tendik->jabatan }}_</span>
                            </p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('tendiks.edit', $tendik->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bx bx-edit"></i> Edit
                                </a>
                                <form action="{{ route('tendiks.destroy', $tendik->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="bx bx-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .card {
        border-radius: 10px;
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .img-rounded {
        background: linear-gradient(45deg, #6c63ff, #37b4f7);
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection
