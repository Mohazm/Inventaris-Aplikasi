@extends('kerangka.master')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Selamat Datang Card -->
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Selamat Datang Alhan 🎉</h5>
                                <p class="mb-4">
                                    Selamat bekerja, nikmati harimu dengan lebih baik!
                                </p>
                                <a href="javascript:;" id="data" class="btn btn-sm btn-outline-primary">Lihat Data</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                    alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png"
                                    data-app-light-img="illustrations/man-with-laptop-light.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Cards -->
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-center justify-content-center">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}"
                                            alt="chart success" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-center">Data User</span>
                                <h3 class="card-title mb-2 text-center">{{ $users }}</h3>
                                <small class="text-success fw-semibold d-flex justify-content-center">
                                    <i class="bx bx-up-arrow-alt"></i> Data
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-center justify-content-center">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{ asset('/assets/img/icons/unicons/wallet-info.png') }}"
                                            alt="Credit Card" class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1 text-center">Data Produk</span>
                                <h3 class="card-title mb-2 text-center">$4,679</h3>
                                <small class="text-success fw-semibold d-flex justify-content-center">
                                    <i class="bx bx-up-arrow-alt"></i> +28.42%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Barang Masuk & Keluar -->
            <div class="col-12 col-lg-7 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-12">
                            <h5 class="card-header m-0 me-2 pb-3">Barang Masuk & Keluar</h5>
                            <div class="card-body">
                                <div id="barangChart" style="height: 300px;"></div>
                                <div class="mt-3">
                                    <span class="fw-semibold">Barang Masuk:</span> <span id="barangMasuk">100</span> items
                                </div>
                                <div>
                                    <span class="fw-semibold">Barang Keluar:</span> <span id="barangKeluar">80</span> items
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kalender -->
            <div class="col-lg-5 mb-4 order-2 order-md-3 order-lg-2">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-lg-12">
                            <h5 class="card-header m-0 me-2 pb-3">Kalender</h5>
                            <div class="card-body">
                                <div class="today">
                                    <div class="fs-5 mb-2 text-center bg-primary text-white today-piece top day"></div>
                                    <div class="fs-3 text-center today-piece middle month"></div>
                                    <div class="fs-3 mb-2 text-center today-piece middle date"></div>
                                    <div class="fs-5 text-center bg-primary text-white today-piece bottom year"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Kalender
            const today = new Date();
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            document.querySelector('.day').textContent = days[today.getDay()];
            document.querySelector('.month').textContent = months[today.getMonth()];
            document.querySelector('.date').textContent = today.getDate();
            document.querySelector('.year').textContent = today.getFullYear();

            // Data for chart
            const dataBarangMasuk = 100;  // Example data
            const dataBarangKeluar = 80;  // Example data

            // Update text content with data
            document.getElementById('barangMasuk').textContent = dataBarangMasuk;
            document.getElementById('barangKeluar').textContent = dataBarangKeluar;

            // Chart creation
            const ctx = document.getElementById('barangChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Barang Masuk', 'Barang Keluar'],
                    datasets: [{
                        label: 'Jumlah Barang',
                        data: [dataBarangMasuk, dataBarangKeluar],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection
