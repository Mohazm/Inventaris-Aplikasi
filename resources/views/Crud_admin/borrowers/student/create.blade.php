@extends('kerangka.master')

@section('content')
    <style>
        /* Gaya Minimalis dan Elegan */
        form {
         
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;e
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-family: 'Arial', sans-serif;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        input:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.3);
        }
        small {
            color: #e74c3c;
            font-size: 12px;
            display: block;
            margin-top: -10px;
            margin-bottom: 10px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>

    <h1>Tambah Siswa</h1>

    <form action="{{ route('stundent.store') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <small>{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <small>{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="phone">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}">
            @error('phone')
                <small>{{ $message }}</small>
            @enderror
        </div>

        <div>
            <label for="class">Kelas</label>
            <input type="text" id="class" name="class" value="{{ old('class') }}" required>
            @error('class')
                <small>{{ $message }}</small>
            @enderror
        </div>

        <button type="submit">Simpan</button>
    </form>
@endsection
