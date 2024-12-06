@extends('kerangka.staff')

@section('content')
    <div class="container my-5">
        <h1 class="text-center mb-4 text-primary fw-bold">📋 Daftar Tendik</h1>

        {{-- Toast for Success Message --}}
        @if (session('success'))
            <div class="position-fixed top-0 end-0 p-3" style="z-index: 1055;">
                <div class="toast align-items-center text-white bg-success border-0 show shadow" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            🎉 {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var toastEl = document.querySelector('.toast');
                    var toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                    toast.show();
                });
            </script>
        @endif

        {{-- Styling --}}
        <style>
            .table thead th {
                background-color: #6c63ff;
                color: #fff;
                text-align: center;
                vertical-align: middle;
            }

            .table tbody tr:hover {
                background-color: #f9f9f9;
            }

            .empty-state {
                font-style: italic;
                color: #aaa;
                text-align: center;
                padding: 20px;
                border: 2px dashed #ddd;
                border-radius: 10px;
            }

            /* Adjust column widths */
            .col-no {
                width: 10%;
                text-align: center;
            }

            .col-nama {
                width: 45%;
            }

            .col-jabatan {
                width: 45%;
            }
        </style>

        {{-- Table --}}
        <div class="table-responsive shadow rounded">
            <table class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th class="col-no">No</th>
                        <th class="col-nama">Nama</th>
                        <th class="col-jabatan">Jabatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tendiks as $tendik)
                        <tr>
                            <td class="col-no text-center fw-bold">{{ $loop->iteration }}</td>
                            <td class="col-nama">
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-light-primary me-3 rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                        <span class="fw-bold text-uppercase">{{ substr($tendik->name, 0, 1) }}</span>
                                    </div>
                                    <span>{{ $tendik->name }}</span>
                                </div>
                            </td>
                            <td class="col-jabatan">{{ $tendik->jabatan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty-state">Tidak ada data yang tersedia</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
