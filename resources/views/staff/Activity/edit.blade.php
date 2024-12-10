@extends('kerangka.staff')

@section('content')
    <div class="container">
        <h1>Edit Aktivitas</h1>

        <!-- Menampilkan pesan error jika ada -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form untuk edit aktivitas -->
        <form action="{{ route('activities.update', $activity->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <!-- Menampilkan nama pengguna yang mengisi -->
            <div class="form-group">
                <label for="user_id">Pengguna yang Mengisi</label>
                <input type="text" id="user_id" class="form-control" value="{{ $activity->user->name }}" readonly>
            </div>

            <!-- Menampilkan tanggal pengisian -->
            <div class="form-group">
                <label for="tanggal_isi">Tanggal Pengisian</label>
                <input type="text" id="tanggal_isi" class="form-control" value="{{ $activity->tanggal_isi }}" readonly>
            </div>

            <div class="form-group">
                <label for="activitas">Aktivitas</label>
                <input type="text" id="activitas" name="activitas" class="form-control" value="{{ old('activitas', $activity->activitas) }}" required>
            </div>


            <!-- Logika agar aktivitas tidak bisa diedit setelah jam 12 malam -->
            @if (\Carbon\Carbon::now()->isAfter(\Carbon\Carbon::parse($activity->tanggal_isi)->setTime(23, 59)))
                <div class="alert alert-warning mt-3">
                    Aktivitas ini tidak dapat diedit setelah jam 12 malam.
                </div>
                <button type="submit" class="btn btn-success mt-3" disabled>Perbarui</button>
            @else
                <button type="submit" class="btn btn-success mt-3">Perbarui</button>
            @endif

            <a href="{{ route('activities.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection
