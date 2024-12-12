@extends('kerangka.staff')

@section('content')
    <div class="container">
        <h1>Tambah Aktivitas</h1>

        <!-- Menampilkan pesan error jika ada -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
        <script>
            CKEDITOR.replace('activitas', {
                versionCheck: false
            });
        </script>
        {{-- <script src="//cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script> --}}
        <!-- Form untuk input aktivitas -->
        <form action="{{ route('activities.store') }}" method="POST">
            @csrf

            <!-- Menampilkan pengguna yang sedang login -->
            <div class="form-group">
                <label for="user_id">Pengguna yang Mengisi</label>
                <input type="text" id="user_id" name="user_id" class="form-control" value="{{ auth()->user()->name }}"
                    readonly>
            </div>


            <!-- Menampilkan tanggal pengisian otomatis sebagai hari ini -->
            <div class="form-group">
                <label for="tanggal_isi">Tanggal Pengisian</label>
                <input type="text" id="tanggal_isi" name="tanggal_isi" class="form-control"
                    value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" readonly>
            </div>

            <div class="form-group">
                <label for="activitas">Aktivitas</label>
                <textarea name="activitas" class=" ckeditor form-control" id="activitas" value="{{ old('activitas') }}" cols="30"
                    rows="10">
                  
                </textarea>
                {{-- <input type="text" id="activitas" name="activitas" class="form-control" value="{{ old('activitas') }}" required> --}}
            </div>

            <!-- Logika agar aktivitas hanya bisa diisi sebelum jam 12 malam -->
            @if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::now()->setTime(23, 59)))
                <div class="alert alert-warning mt-3">
                    Aktivitas ini tidak dapat diisi setelah jam 12 malam.
                </div>
                <button type="submit" class="btn btn-success mt-3" disabled>Simpan</button>
            @else
                <button type="submit" class="btn btn-success mt-3">Simpan</button>
            @endif

            <a href="{{ route('activities.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection
