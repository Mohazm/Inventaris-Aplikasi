@extends('kerangka.master')

@section('content')
@if (session('success'))
<div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-header">
        <i class="bx bx-bell me-2"></i>
        <div class="me-auto fw-semibold">Sukses</div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        {{ session('success') }}
    </div>
</div>
@endif

<div class="container my-5">
    <h1 class="text-center mb-4">Daftar Aktivitas</h1>
    <hr>

    <!-- Form Pencarian -->
    <form action="{{ route('admin.activities.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari aktivitas atau pengguna..."
                value="{{ request('search') }}">
            <button class="btn btn-primary" type="submit">Cari</button>
        </div>
    </form>

    <!-- Tabel Aktivitas -->
    <div class="row g-4">
        @foreach ($activities as $activity)
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title d-flex align-items-center">
                        <i class="bi bi-person-circle icon"></i>{{ $activity->user->name ?? 'Tidak Diketahui' }}
                    </h5>
                    <p class="card-text text-muted mb-2">
                        <i class="bi bi-calendar-event icon"></i>{{ $activity->created_at->format('d-m-Y H:i') }}
                    </p>
                    <p class="card-text">{{ $activity->activitas }}</p>
                    <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus aktivitas ini?')">
                            <i class="bi bi-trash3"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection
