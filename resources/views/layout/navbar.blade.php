<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <style>
        .clock,
        .date {
            font-size: 16px;
            color: #000;
            /* Sesuaikan warna dengan tema navbar */
        }

        .navbar-time-container {
            display: flex;
            align-items: center;
            gap: 10px;
        }
    </style>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center navbar-time-container">
                <span>Tanggal :</span>
                <div class="date"></div>
                <span>| Jam :</span>
                <div class="clock"></div>
            </div>
        </div>
        <ul class="navbar-nav flex-row align-items-center ms-auto">

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="{{ asset('/assets/img/avatars/6.png') }}" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('/assets/img/avatars/1.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <span class="fw-semibold d-block">{{ Auth::user()->role }}</span>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); 
                 document.getElementById('logout-form').submit();"
                            role="button">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateClockAndDate() {
            const time = new Date();

            // Waktu (jam, menit, detik)
            let hours = time.getHours();
            let minutes = time.getMinutes();
            let seconds = time.getSeconds();
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12 || 12; // Format 12 jam
            const formattedTime = `${pad(hours)}:${pad(minutes)}:${pad(seconds)} ${ampm}`;

            // Tanggal (hari, bulan, tahun)
            const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
            const months = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus",
                "September", "Oktober", "November", "Desember"
            ];
            const day = days[time.getDay()];
            const date = time.getDate();
            const month = months[time.getMonth()];
            const year = time.getFullYear();
            const formattedDate = `${day}, ${date} ${month} ${year}`;

            // Update elemen di DOM
            document.querySelector('.clock').textContent = formattedTime;
            document.querySelector('.date').textContent = formattedDate;
        }

        function pad(value) {
            return value < 10 ? '0' + value : value;
        }

        updateClockAndDate();
        setInterval(updateClockAndDate, 1000);
    });
</script>
