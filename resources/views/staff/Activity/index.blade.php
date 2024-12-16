@extends('kerangka.staff')

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
        /* General Styling */
        .container {
            max-width: 1200px;
        }

        /* Toast Styling */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1055;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background: linear-gradient(145deg, #ffffff, #f3f4f6);
            box-shadow: 5px 5px 10px #d1d5db, -5px -5px 10px #ffffff;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 10px 10px 20px #d1d5db, -10px -10px 20px #ffffff;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 0.95rem;
            color: #555;
        }

        .btn {
            font-size: 0.9rem;
            font-weight: bold;
            border-radius: 20px;
            transition: all 0.3s;
        }

        .btn-warning {
            background-color: #ffca28;
            color: #fff;
        }

        .btn-warning:hover {
            background-color: #ffb300;
        }

        .btn-danger {
            background-color: #f44336;
            color: #fff;
        }

        .btn-danger:hover {
            background-color: #d32f2f;
        }

        .icon {
            font-size: 1.5rem;
            color: #888;
            margin-right: 10px;
        }
    </style>

    <div class="container my-5">
        <h1 class="text-center mb-4">Daftar Aktivitas</h1>
        <hr>
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Tambah Aktivitas Button -->
        <div class="d-flex mb-4">
            <a href="{{ route('activities.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i> Tambah Aktivitas
            </a>
        </div>

        <!-- Card List -->
        <div class="row g-4">
            @forelse ($activities as $activity)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title d-flex align-items-center">
                                <i class="bi bi-person-circle icon"></i>{{ $activity->user->name }}
                            </h5>
                            <p class="card-text text-muted mb-2">
                                <i class="bi bi-calendar-event icon"></i>{{ $activity->tanggal_isi }}
                            </p>
                            <p class="card-text">{{ $activity->activitas }}</p>
                            <div class="d-flex justify-content-between mt-3">
                                <a href="{{ route('activities.edit', $activity->id) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
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
                </div>
            @empty
                <div class="text-center wqw" style="border:2px solid blue;">Activity Masih Kosong</div>
            @endforelse
        </div>
    </div>
    <style>
        .wqw {
            margin-top: -40px;
            border-radius: 50px;
            border: 2px solid blue;
            padding: 10px;
            margin: 20px;
            font-size: 18px;
            color: blue;
            text-align: center;
            /* transform: rotate(calc(1.700deg)); */
            /* Menggunakan calc() dengan satuan deg */
        }
    </style>
@endsection
