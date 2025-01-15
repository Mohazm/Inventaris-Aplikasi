@extends('kerangka.master')

@section('title', 'Tambah Peminjaman')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4 text-center">Tambah Peminjaman</h4>

        <div class="card shadow-sm border-light">
            <div class="card-body">
                <form action="{{ route('loans_item.store') }}" method="POST" id="loan-form statusPinjamForm">
                    @csrf

                    <!-- Pilih Barang -->
                    <!-- Pilih Barang -->
                    <div class="mb-3">
                        <label for="item-dropdown">Pilih Barang</label>
                        <select id="item-dropdown" name="item_id" class="form-control">
                            <option value="">-- Pilih Barang --</option>
                            @foreach ($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pilih Detail Barang -->
                    <div class="mb-3">
                        <label for="detail-dropdown">Pilih Detail Barang</label>
                        <select id="detail-dropdown" name="detail_item_ids[]" class="form-control" multiple disabled>
                            <option value="">-- Pilih Detail Barang --</option>
                        </select>
                    </div>

                    <!-- Jumlah Pinjam -->
                    <div class="mb-3">
                        <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                        <input type="number" class="form-control" id="jumlah_pinjam" name="jumlah_pinjam" min="1"
                            readonly>
                    </div>


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const itemDropdown = document.getElementById('item-dropdown');
                            const detailDropdown = document.getElementById('detail-dropdown');
                            const jumlahPinjamInput = document.getElementById('jumlah_pinjam');

                            itemDropdown.addEventListener('change', function() {
                                const itemId = this.value;

                                detailDropdown.innerHTML = '<option value="">-- Pilih Detail Barang --</option>';
                                detailDropdown.disabled = true;
                                jumlahPinjamInput.value = 0;

                                if (itemId) {
                                    fetch(`/items/${itemId}/details`)
                                        .then(response => response.json())
                                        .then(data => {
                                            // Filter data untuk hanya mengambil yang statusnya 'Sedang dipinjam'
                                            const filteredData = data.filter(detail => detail.status_pinjaman ===
                                                'belum di pinjam');

                                            filteredData.forEach(detail => {
                                                const option = document.createElement('option');
                                                option.value = detail.id;
                                                option.textContent =
                                                    `${detail.kode_barang} (${detail.nama_barang})`;
                                                detailDropdown.appendChild(option);
                                            });

                                            // Enable dropdown setelah data diisi
                                            detailDropdown.disabled = false;
                                        })
                                        .catch(error => console.error('Error fetching details:', error));
                                }

                            });

                            detailDropdown.addEventListener('change', function() {
                                const selectedOptions = Array.from(detailDropdown.selectedOptions);
                                jumlahPinjamInput.value = selectedOptions.length;
                            });

                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('statusPinjamForm');
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        e.preventDefault();
                                        const formData = new FormData(form);

                                        fetch('/update-status', {
                                                method: 'POST',
                                                body: formData,
                                            })
                                            .then(response => response.json())
                                            .then(data => {
                                                if (data.success) {
                                                    alert(data.message);
                                                    Array.from(detailDropdown.selectedOptions).forEach(
                                                        option => {
                                                            option.textContent =
                                                                `${option.textContent} - Status: Sedang dipinjam`;
                                                        });
                                                } else {
                                                    alert(data.message || 'Gagal memperbarui status pinjaman.');
                                                }
                                            })
                                            .catch(error => {
                                                console.error('Error:', error);
                                                alert('Terjadi kesalahan.');
                                            });
                                    });
                                } else {
                                    console.error('Form dengan ID "statusPinjamForm" tidak ditemukan.');
                                }
                            });
                        });
                    </script>


                    <!-- Pilih atau Tambah Peminjam -->
                    <select class="form-select" id="borrower_id" name="borrower_id" required>
                        <option value="" disabled selected>-- Pilih atau Tambahkan Peminjam --</option>
                        @forelse ($borrowers as $borrower)
                            @if ($borrower->student)
                                <option value="{{ $borrower->id }}">Siswa: {{ $borrower->student->name }}</option>
                            @elseif ($borrower->teacher)
                                <option value="{{ $borrower->id }}">Guru: {{ $borrower->teacher->name }}</option>
                            @endif
                        @empty
                            <option value="" disabled>Tidak ada data peminjam</option>
                        @endforelse
                    </select>
                    <button type="button" id="add-Student" class="btn btn-secondary mt-2">Tambah Siswa</button>
                    <button type="button" id="add-Teacher" class="btn btn-secondary mt-2">Tambah Guru</button>
                    <meta name="csrf-token" content="{{ csrf_token() }}">

                    <!-- Student Form -->
                    <div id="add-student-form" class="form-container" style="display:none;">
                        <div class="mb-3">
                            <label for="student_name" class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" id="student_name" placeholder="Masukkan nama siswa">
                        </div>
                        <div class="mb-3">
                            <label for="student_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="student_email"
                                placeholder="Masukkan email siswa">
                        </div>
                        <div class="mb-3">
                            <label for="student_phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="student_phone"
                                placeholder="Masukkan nomor telepon siswa">
                        </div>
                        <div class="mb-3">
                            <label for="student_class" class="form-label">Kelas</label>
                            <input type="text" class="form-control" id="student_class"
                                placeholder="Masukkan kelas siswa">
                        </div>
                        <button type="button" id="save-student" class="btn btn-primary">Simpan</button>
                    </div>

                    <!-- Teacher Form -->
                    <div id="add-teacher-form" class="form-container" style="display:none;">
                        <div class="mb-3">
                            <label for="teacher_name" class="form-label">Nama Guru</label>
                            <input type="text" class="form-control" id="teacher_name" placeholder="Masukkan nama guru">
                        </div>
                        <div class="mb-3">
                            <label for="teacher_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="teacher_email" placeholder="Masukkan email guru">
                        </div>
                        <div class="mb-3">
                            <label for="teacher_phone" class="form-label">Nomor Telepon</label>
                            <input type="text" class="form-control" id="teacher_phone"
                                placeholder="Masukkan nomor telepon guru">
                        </div>
                        <button type="button" id="save-teacher" class="btn btn-primary">Simpan</button>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            const borrowerSelect = document.getElementById('borrower_id');
                            const addStudentBtn = document.getElementById('add-Student');
                            const addTeacherBtn = document.getElementById('add-Teacher');
                            const studentForm = document.getElementById('add-student-form');
                            const teacherForm = document.getElementById('add-teacher-form');

                            // Form Toggle
                            const toggleForm = (form) => {
                                document.querySelectorAll('.form-container').forEach(f => f.style.display = 'none');
                                if (form) form.style.display = 'block';
                            };

                            addStudentBtn.addEventListener('click', () => toggleForm(studentForm));
                            addTeacherBtn.addEventListener('click', () => toggleForm(teacherForm));

                            // Save Student
                            document.getElementById('save-student').addEventListener('click', () => {
                                const name = document.getElementById('student_name').value.trim();
                                const email = document.getElementById('student_email').value.trim();
                                const phone = document.getElementById('student_phone').value.trim();
                                const className = document.getElementById('student_class').value.trim();

                                if (!name || !email || !phone || !className) {
                                    alert('Semua field harus diisi.');
                                    return;
                                }

                                fetch('{{ route('stundent.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            name,
                                            email,
                                            phone,
                                            class: className
                                        })
                                    })
                                    .then(res => {
                                        if (!res.ok) throw new Error('Gagal menyimpan siswa.');
                                        return res.json();
                                    })
                                    .then(data => {
                                        borrowerSelect.add(new Option(`Siswa: ${data.name}`, data.id, true, true));
                                        alert('Siswa berhasil ditambahkan!');
                                        toggleForm(null);
                                    })
                                    .catch(err => alert(err.message));
                            });

                            // Save Teacher
                            document.getElementById('save-teacher').addEventListener('click', () => {
                                const name = document.getElementById('teacher_name').value.trim();
                                const email = document.getElementById('teacher_email').value.trim();
                                const phone = document.getElementById('teacher_phone').value.trim();

                                if (!name || !email || !phone) {
                                    alert('Semua field harus diisi.');
                                    return;
                                }

                                fetch('{{ route('teacher.store') }}', {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': csrfToken,
                                            'Content-Type': 'application/json'
                                        },
                                        body: JSON.stringify({
                                            name,
                                            email,
                                            phone
                                        })
                                    })
                                    .then(res => {
                                        if (!res.ok) throw new Error('Gagal menyimpan guru.');
                                        return res.json();
                                    })
                                    .then(data => {
                                        borrowerSelect.add(new Option(`Guru: ${data.name}`, data.id, true, true));
                                        alert('Guru berhasil ditambahkan!');
                                        toggleForm(null);
                                    })
                                    .catch(err => alert(err.message));
                            });
                        });
                    </script>
                    <!-- Tanggal Pinjam -->
                    <div class="mb-3">
                        <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                        <input type="date" class="form-control @error('tanggal_pinjam') is-invalid @enderror"
                            id="tanggal_pinjam" name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}">
                        @error('tanggal_pinjam')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tanggal Kembali -->
                    <div class="mb-3">
                        <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                        <input type="date" class="form-control @error('tanggal_kembali') is-invalid @enderror"
                            id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali') }}">
                        @error('tanggal_kembali')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Tujuan Peminjaman -->
                    <div class="mb-3">
                        <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman</label>
                        <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman"
                            name="tujuan_peminjaman" rows="3">{{ old('tujuan_peminjaman') }}</textarea>
                        @error('tujuan_peminjaman')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('loans_item.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Script Section -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const borrowerSelect = document.getElementById('borrower_id');
            const studentForm = document.getElementById('add-student-form');
            const teacherForm = document.getElementById('add-teacher-form');

            document.getElementById('add-student').addEventListener('click', () => {
                studentForm.style.display = studentForm.style.display === 'none' ? 'block' : 'none';
                teacherForm.style.display = 'none';
            });

            document.getElementById('add-teacher').addEventListener('click', () => {
                teacherForm.style.display = teacherForm.style.display === 'none' ? 'block' : 'none';
                studentForm.style.display = 'none';
            });

            // Example AJAX submission (refactor backend as needed)
            async function saveBorrower(url, data) {
                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(data),
                    });
                    if (!response.ok) throw new Error('Gagal menyimpan data.');
                    const result = await response.json();
                    borrowerSelect.add(new Option(result.name, result.id, true, true));
                    alert('Peminjam berhasil ditambahkan!');
                } catch (error) {
                    alert(error.message);
                }
            }
        });
    </script>
@endsection
