@extends('kerangka.master')

@section('content')
    <div class="container">
        <h1 class="mb-4 text-center">Daftar Peminjam</h1><hr>

        @if (session('success'))
            <div class="bs-toast toast fade show bg-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <i class="bx bx-bell me-2"></i>
                    <div class="me-auto fw-semibold">Notifikasi</div>
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
            /* Toast Styling */
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

            /* Card Styling */
            .card {
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease-in-out;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.2rem;
                font-weight: bold;
            }

            .card-text {
                margin-bottom: 1rem;
            }

            .img-rounded {
                border-radius: 50%;
                width: 100px;
                height: 100px;
                object-fit: cover;
                margin-bottom: 1rem;
            }

            .btn-sm {
                font-size: 0.875rem;
            }

            /* Button Styling */
            .btn-primary {
                background-color: #007bff;
                border-color: #007bff;
            }

            .btn-warning {
                background-color: #ffc107;
                border-color: #ffc107;
            }

            .btn-danger {
                background-color: #dc3545;
                border-color: #dc3545;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .col-md-4 {
                    flex: 0 0 100%;
                    max-width: 100%;
                }
            }
        </style>

        <a href="{{ route('borrowers.create') }}" class="btn btn-primary mb-3">Tambah Peminjam</a>

        <div class="row">
            @foreach ($borrowers as $borrower)
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-center mb-3">
                                <div class="img-rounded bg-primary d-flex justify-content-center align-items-center">
                                    <i class="bx bxs-user text-white" style="font-size: 3rem;"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-center">{{ $borrower->nama_peminjam }}</h5>
                            <p class="card-text text-center"><strong>No Telepon:</strong> {{ $borrower->no_telp }}</p>
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('borrowers.edit', $borrower) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('borrowers.destroy', $borrower) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
