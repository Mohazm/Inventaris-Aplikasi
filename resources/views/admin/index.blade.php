@extends('kerangka.master')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <!-- Selamat Datang Card -->
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-6">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Selamat Datang {{ Auth::user()->role }}
                                    {{ Auth::user()->name }} 🎉</h5>
                                <p class="mb-4">
                                    Selamat bekerja, nikmati harimu dengan lebih baik!
                                </p>
                                <a href="javascript:;" id="data" class="btn btn-sm btn-outline-primary">Lihat Data</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140"
                                    alt="View Badge User" class="img-fluid" />
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
                            <div class="card-body text-center">
                                <div class="avatar mb-3">
                                    <img src="{{ asset('/assets/img/icons/unicons/chart-success.png') }}"
                                        alt="chart success" class="rounded" />
                                </div>
                                <span class="fw-semibold d-block mb-1">Data Karyawan</span>
                                <h3 class="card-title mb-2">{{ $users }}</h3>
                                <small class="text-success fw-semibold">
                                    <i class="bx bx-up-arrow-alt"></i> Data
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="avatar mb-3">
                                    <img src="{{ asset('/assets/img/icons/unicons/wallet-info.png') }}" alt="Credit Card"
                                        class="rounded" />
                                </div>
                                <span class="fw-semibold d-block mb-1">Data Produk</span>
                                <h3 class="card-title mb-2">$4,679</h3>
                                <small class="text-success fw-semibold">
                                    <i class="bx bx-up-arrow-alt"></i> +28.42%
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Barang Masuk -->
            <!-- Informasi Barang Masuk -->
            <!-- Informasi Barang Masuk -->
            <div class="col-lg-6 order-2 mb-4">
                <div class="card">
                    <h5 class="card-header">Informasi Barang Masuk</h5>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary btn-sm" onclick="changeMonth(-1)">&#8592; Bulan
                                Sebelumnya</button>
                            <span id="monthLabel"
                                class="fw-bold">{{ \Carbon\Carbon::parse($currentMonth . '-01')->format('F Y') }}</span>
                            <button class="btn btn-primary btn-sm" onclick="changeMonth(1)">Bulan Berikutnya
                                &#8594;</button>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Masuk</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactionsins as $in)
                                    <tr>
                                        <td>{{ $in->item->nama_barang }}</td>
                                        <td>{{ $in->jumlah }}</td>
                                        <td>{{ $in->tanggal_masuk }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $transactionsins->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Barang Keluar -->
            <div class="col-lg-6 order-2 mb-4">
                <div class="card">
                    <h5 class="card-header">Informasi Barang Keluar</h5>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <button class="btn btn-primary btn-sm" onclick="changeMonth(-1)">&#8592; Bulan
                                Sebelumnya</button>
                            <span id="monthLabel"
                                class="fw-bold">{{ \Carbon\Carbon::parse($currentMonth . '-01')->format('F Y') }}</span>
                            <button class="btn btn-primary btn-sm" onclick="changeMonth(1)">Bulan Berikutnya
                                &#8594;</button>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Nama Barang</th>
                                    <th>Jumlah</th>
                                    <th>Tanggal Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactionsouts as $out)
                                    <tr>
                                        <td>{{ $out->item->nama_barang }}</td>
                                        <td>{{ $out->jumlah }}</td>
                                        <td>{{ $out->tanggal_keluar }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $transactionsins->links() }}
                        </div>
                    </div>
                </div>
            </div>


            <!-- Total Revenue -->
            <div class="col-12 col-lg-7 order-2 mb-4">
                <div class="card">
                    <h5 class="card-header">Total Revenue</h5>
                    <div class="card-body">
                        <canvas id="totalRevenueChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Kalender -->
            <div class="col-lg-5 mb-4 order-2">
                <div class="card">
                    <h5 class="card-header">Kalender</h5>
                    <div class="card-body text-center">
                        <div class="today">
                            <div class="fs-5 mb-2 bg-primary text-white today-piece top day"></div>
                            <div class="fs-3 today-piece middle month"></div>
                            <div class="fs-3 mb-2 today-piece middle date"></div>
                            <div class="fs-5 bg-primary text-white today-piece bottom year"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function changeMonth(direction) {
            const currentMonth = "{{ request('month', \Carbon\Carbon::now()->format('Y-m')) }}";
            const [year, month] = currentMonth.split('-').map(Number);
            const newDate = new Date(year, month - 1 + direction, 1); // Update bulan
            const newMonth = `${newDate.getFullYear()}-${String(newDate.getMonth() + 1).padStart(2, '0')}`;
            window.location.href = `?month=${newMonth}`;
        }
    </script>

    <script>
        // Data Barang Masuk
        const dataBarangMasuk = {
            "2024-12": [{
                    nama: "Barang A",
                    jumlah: 20,
                    tanggal: "2024-12-09"
                },
                {
                    nama: "Barang B",
                    jumlah: 15,
                    tanggal: "2024-12-10"
                }
            ],
            "2025-01": [{
                    nama: "Barang C",
                    jumlah: 10,
                    tanggal: "2025-01-05"
                },
                {
                    nama: "Barang D",
                    jumlah: 25,
                    tanggal: "2025-01-20"
                }
            ]
        };

        // Data Barang Keluar
        const dataBarangKeluar = {
            "2024-12": [{
                    nama: "Barang X",
                    jumlah: 20,
                    tanggal: "2024-12-09"
                },
                {
                    nama: "Barang Y",
                    jumlah: 15,
                    tanggal: "2024-12-10"
                }
            ],
            "2025-01": [{
                nama: "Barang Z",
                jumlah: 30,
                tanggal: "2025-01-15"
            }]
        };

        // Fungsi Render Barang
        function renderTable(data, month, dataBodyId, monthLabelId) {
            const dataSet = data[month] || [];
            const dataBody = document.getElementById(dataBodyId);
            const monthLabel = document.getElementById(monthLabelId);

            dataBody.innerHTML = dataSet
                .map(item => `
            <tr>
                <td>${item.nama}</td>
                <td>${item.jumlah}</td>
                <td>${item.tanggal}</td>
            </tr>
        `)
                .join("");

            const [year, monthNum] = month.split("-");
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
                "Oktober", "November", "Desember"
            ];
            monthLabel.textContent = `${monthNames[parseInt(monthNum) - 1]} ${year}`;
        }

        // Barang Masuk
        let currentMonthMasuk = "2024-12";
        document.getElementById("prevMonthMasuk").addEventListener("click", () => {
            currentMonthMasuk = getPreviousMonth(currentMonthMasuk);
            renderTable(dataBarangMasuk, currentMonthMasuk, "dataBodyMasuk", "monthLabelMasuk");
        });
        document.getElementById("nextMonthMasuk").addEventListener("click", () => {
            currentMonthMasuk = getNextMonth(currentMonthMasuk);
            renderTable(dataBarangMasuk, currentMonthMasuk, "dataBodyMasuk", "monthLabelMasuk");
        });

        // Barang Keluar
        let currentMonthKeluar = "2024-12";
        document.getElementById("prevMonthKeluar").addEventListener("click", () => {
            currentMonthKeluar = getPreviousMonth(currentMonthKeluar);
            renderTable(dataBarangKeluar, currentMonthKeluar, "dataBodyKeluar", "monthLabelKeluar");
        });
        document.getElementById("nextMonthKeluar").addEventListener("click", () => {
            currentMonthKeluar = getNextMonth(currentMonthKeluar);
            renderTable(dataBarangKeluar, currentMonthKeluar, "dataBodyKeluar", "monthLabelKeluar");
        });

        // Fungsi untuk Mendapatkan Bulan Sebelumnya dan Berikutnya
        function getPreviousMonth(currentMonth) {
            const [year, month] = currentMonth.split("-").map(Number);
            const prevMonth = new Date(year, month - 2); // Bulan sebelumnya
            return `${prevMonth.getFullYear()}-${String(prevMonth.getMonth() + 1).padStart(2, "0")}`;
        }

        function getNextMonth(currentMonth) {
            const [year, month] = currentMonth.split("-").map(Number);
            const nextMonth = new Date(year, month); // Bulan berikutnya
            return `${nextMonth.getFullYear()}-${String(nextMonth.getMonth() + 1).padStart(2, "0")}`;
        }

        // Render Awal
        renderTable(dataBarangMasuk, currentMonthMasuk, "dataBodyMasuk", "monthLabelMasuk");
        renderTable(dataBarangKeluar, currentMonthKeluar, "dataBodyKeluar", "monthLabelKeluar");
    </script>
    <!-- Scripts -->



    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('totalRevenueChart').getContext('2d');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli'],
                datasets: [{
                    label: 'Pendapatan',
                    data: [{{ $revenueIn }}, {{ $revenueOut }}, 0, 0, 0, 0, 0],  // Gunakan data yang dikirim dari controller
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Total Revenue'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
    </script>
@endsection
