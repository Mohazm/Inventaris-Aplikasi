@extends('kerangka.master')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto mt-8 p-6 max-w-4xl bg-white shadow-lg rounded-lg">
        <!-- Judul -->
        <div class="text-center mb-6 border-b pb-4">
            <h2 class="text-3xl font-semibold text-gray-700">Detail Peminjaman</h2>
        </div>

        <!-- Detail Card -->
        <div class="grid grid-cols-2 gap-6 border-t pt-6">
            <!-- Nama Peminjam -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Nama Peminjam</h4>
                <p class="text-lg text-gray-800 font-medium">
                    @if ($loanItem->borrower)
                        @if ($loanItem->borrower->student)
                            {{ $loanItem->borrower->student->name }} (Student)
                        @elseif($loanItem->borrower->teacher)
                            {{ $loanItem->borrower->teacher->name }} (Teacher)
                        @else
                            -
                        @endif
                    @else
                        -
                    @endif
                </p>
            </div>

            <!-- Tanggal Pinjam -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Tanggal Pinjam</h4>
                <p class="text-lg text-gray-800 font-medium">{{ $loanItem->tanggal_pinjam }}</p>
            </div>

            <!-- Tanggal Kembali -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Tanggal Kembali</h4>
                <p class="text-lg text-gray-800 font-medium">{{ $loanItem->tanggal_kembali }}</p>
            </div>
        </div>

        <!-- Detail Barang yang Dipinjam -->
        @if ($loanItem->details->isEmpty())
            <p class="text-red-500">Tidak ada barang yang dipinjam.</p>
        @else
          @foreach ($loanItem->details as $detail)
                <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                    <h4 class="text-gray-500 uppercase text-sm mb-1">Nama Barang</h4>
                    <p class="text-lg text-gray-800 font-medium">{{ $detail->nama_barang }}</p>
                </div>
                <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                    <h4 class="text-gray-500 uppercase text-sm mb-1">Kode Barang</h4>
                    <p class="text-lg text-gray-800 font-medium">{{ $detail->kode_barang }}</p>
                </div>
            @endforeach
        @endif


        <!-- Tombol Kembali -->
        <div class="mt-8 text-center">
            <a href="{{ route('loans_item.index') }}"
                class="inline-block bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded transition duration-300">
                Kembali
            </a>
        </div>
    </div>

@endsection
