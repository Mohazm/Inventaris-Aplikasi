@extends('kerangka.master')

@section('title', 'Tambah Peminjaman')

@section('content')

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Document</title>
    </head>

    <body>
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4 text-center">Tambah</h4>

            <div class="card shadow-sm border-light">
                <div class="card-body">
                    <form action="{{ route('loans_item.store') }}" method="POST">
                        @csrf

                        <!-- Pilih Barang -->
                        <div class="mb-3">
                            <label for="item_id" class="form-label">Pilih Barang</label>
                            <select class="form-select @error('item_id') is-invalid @enderror" id="item_id"
                                name="item_id">
                                <option value="" disabled selected>-- Pilih Barang --</option>
                                @foreach ($items as $item)
                                    <option value="{{ $item->id }}" {{ old('item_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stock }})
                                        {{-- Status: {{ $item->status_pinjaman }} -
                                        Kondisi: {{ $item->Kondisi_barang }} --}}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_id')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>


                        <select class="form-select" id="borrower_id" name="borrower_id" required>
                            <option value="" disabled selected>-- Pilih atau Tambahkan Peminjam --</option>
                            @foreach ($borrowers as $borrower)
                                @if ($borrower->student)
                                    <option value="{{ $borrower->id }}">Siswa:{{ $borrower->student->name }}</option>
                                @elseif ($borrower->teacher)
                                    <option value="{{ $borrower->id }}">Guru:{{ $borrower->teacher->name }}</option>
                                @endif
                                <p class="small">Not Data</p>
                            @endforeach
                        </select>
                        <button type="button" id="add-Student" class="btn btn-secondary mt-2">Tambah Siswa</button>
                        <button type="button" id="add-Teacher" class="btn btn-secondary mt-2">Tambah Guru</button>


                        <!-- Form untuk tambah peminjam -->
                        <div id="add-student-form" style="display:none;">
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Nama Siswa</label>
                                <input type="text" class="form-control" id="student_name"
                                    placeholder="Masukkan nama siswa">
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

                        <div id="add-teacher-form" style="display:none;">
                            <div class="mb-3">
                                <label for="teacher_name" class="form-label">Nama Guru</label>
                                <input type="text" class="form-control" id="teacher_name"
                                    placeholder="Masukkan nama guru">
                            </div>
                            <div class="mb-3">
                                <label for="teacher_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="teacher_email"
                                    placeholder="Masukkan email guru">
                            </div>
                            <div class="mb-3">
                                <label for="teacher_phone" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" id="teacher_phone"
                                    placeholder="Masukkan nomor telepon guru">
                            </div>
                            <button type="button" id="save-teacher" class="btn btn-primary">Simpan</button>
                        </div>
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                                // Form Elements
                                const addStudentForm = document.getElementById('add-student-form');
                                const addTeacherForm = document.getElementById('add-teacher-form');
                                const addBorrowerForm = document.getElementById('add-borrower-form');

                                const borrowerSelect = document.getElementById('borrower_id');

                                // Buttons
                                const addStudentBtn = document.getElementById('add-Student');
                                const addTeacherBtn = document.getElementById('add-Teacher');
                                const addBorrowerBtn = document.getElementById('add-Borrower');

                                const saveBorrowerBtn = document.getElementById('save-borrower');
                                const saveStudentBtn = document.getElementById('save-student');
                                const saveTeacherBtn = document.getElementById('save-teacher');

                                // Toggle Forms
                                const toggleForm = (form) => {
                                    if (addBorrowerForm) addBorrowerForm.style.display = 'none';
                                    if (addStudentForm) addStudentForm.style.display = 'none';
                                    if (addTeacherForm) addTeacherForm.style.display = 'none';
                                    if (form) form.style.display = 'block';
                                };

                                if (addBorrowerBtn) {
                                    addBorrowerBtn.addEventListener('click', () => toggleForm(addBorrowerForm));
                                }
                                if (addStudentBtn) {
                                    addStudentBtn.addEventListener('click', () => toggleForm(addStudentForm));
                                }
                                if (addTeacherBtn) {
                                    addTeacherBtn.addEventListener('click', () => toggleForm(addTeacherForm));
                                }


                                // Save Student
                                saveStudentBtn.addEventListener('click', () => {
                                    const name = document.getElementById('student_name').value.trim();
                                    const email = document.getElementById('student_email').value.trim();
                                    const phone = document.getElementById('student_phone').value.trim();
                                    const className = document.getElementById('student_class').value.trim();

                                    if (!name || !email || !phone || !className) return alert('Semua field harus diisi.');

                                    fetch('{{ route('stundent.store') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Content-Type': 'application/json',
                                            },
                                            body: JSON.stringify({
                                                name,
                                                email,
                                                phone,
                                                class: className
                                            }),
                                        })
                                        .then((res) => res.json())
                                        .then((data) => {
                                            const option = new Option(data.name, data.id, true, true);
                                            borrowerSelect.add(option);
                                            toggleForm(addBorrowerForm); // Hide all forms
                                        })
                                        .catch((err) => alert('Error: ' + err.message));
                                });

                                // Save Teacher
                                saveTeacherBtn.addEventListener('click', () => {
                                    const name = document.getElementById('teacher_name').value.trim();
                                    const email = document.getElementById('teacher_email').value.trim();
                                    const phone = document.getElementById('teacher_phone').value.trim();

                                    if (!name || !email || !phone) return alert('Semua field harus diisi.');

                                    fetch('{{ route('teacher.store') }}', {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': csrfToken,
                                                'Content-Type': 'application/json',
                                            },
                                            body: JSON.stringify({
                                                name,
                                                email,
                                                phone
                                            }),
                                        })
                                        .then((res) => res.json())
                                        .then((data) => {
                                            const option = new Option(data.name, data.id, true, true);
                                            borrowerSelect.add(option);
                                            toggleForm(addBorrowerForm); // Hide all forms
                                        })
                                        .catch((err) => alert('Error: ' + err.message));
                                });
                            });
                        </script>



                        <!-- Jumlah Pinjam -->
                        <div class="mb-3">
                            <label for="jumlah_pinjam" class="form-label">Jumlah Pinjam</label>
                            <input type="number" class="form-control @error('jumlah_pinjam') is-invalid @enderror"
                                id="jumlah_pinjam" name="jumlah_pinjam" min="1"
                                value="{{ old('jumlah_pinjam') }}">
                            @error('jumlah_pinjam')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Pinjam -->
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam</label>
                            <input type="datetime-local"
                                class="form-control @error('tanggal_pinjam') is-invalid @enderror" id="tanggal_pinjam"
                                name="tanggal_pinjam" value="{{ old('tanggal_pinjam') }}">
                            @error('tanggal_pinjam')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tanggal Kembali -->
                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="datetime-local"
                                class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali"
                                name="tanggal_kembali" value="{{ old('tanggal_kembali') }}">
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

    </body>

    </html>
@endsection
