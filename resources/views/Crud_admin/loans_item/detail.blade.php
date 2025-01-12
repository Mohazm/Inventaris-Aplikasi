@extends('kerangka.master')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <div class="container mx-auto mt-8 p-6 max-w-4xl bg-white shadow-lg rounded-lg">
        <!-- Judul -->
        <div class="text-center mb-6 border-b pb-4">
            <h2 class="text-3xl font-semibold text-gray-700">Detail Peminjaman</h2>
        </div>
        <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>

        <!-- Detail Peminjam dan Barang -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
            <!-- Nama Peminjam -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Nama Peminjam</h4>
                <p class="text-lg text-gray-800 font-medium">
                    @if ($loanItem->borrowers)
                        @if ($loanItem->borrowers['borrower_type'] === 'student' && $loanItem->borrowers['student'])
                            {{ $loanItem->borrowers['name'] }} (Student)
                        @elseif ($loanItem->borrowers['borrower_type'] === 'teacher' && $loanItem->borrowers['teacher'])
                            {{ $loanItem->borrowers['name'] }} (Teacher)
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

            <!-- Email -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Email</h4>
                <p class="text-lg text-gray-800 font-medium">
                    @if ($loanItem->borrowers['borrower_type'] === 'student' && $loanItem->borrowers['student'])
                        {{ $loanItem->borrowers['student']['email'] }}
                    @elseif ($loanItem->borrowers['borrower_type'] === 'teacher' && $loanItem->borrowers['teacher'])
                        {{ $loanItem->borrowers['teacher']['email'] }}
                    @else
                        -
                    @endif
                </p>
            </div>

            <!-- Phone -->
            <div class="hover:bg-gray-100 p-4 rounded transition duration-300">
                <h4 class="text-gray-500 uppercase text-sm mb-1">Telepon</h4>
                <p class="text-lg text-gray-800 font-medium">
                    @if ($loanItem->borrowers['borrower_type'] === 'student' && $loanItem->borrowers['student'])
                        {{ $loanItem->borrowers['student']['phone'] }}
                    @elseif ($loanItem->borrowers['borrower_type'] === 'teacher' && $loanItem->borrowers['teacher'])
                        {{ $loanItem->borrowers['teacher']['phone'] }}
                    @else
                        -
                    @endif
                </p>
            </div>
        </div>

        <!-- Barang yang Dipinjam -->
             <!-- Barang yang Dipinjam -->
             @if ($loanItem->detailItems->isEmpty())
             <p class="text-red-500 text-center mt-6">Tidak ada barang yang dipinjam.</p>
         @else
             <div class="mt-6">
                 <h3 class="text-xl font-semibold text-gray-700 mb-4">Barang yang Dipinjam:</h3>
                 <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                     @foreach ($loanItem->detailItems as $detail)
                         <div class="bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transform transition-all duration-300">
                             <div class="p-4">
                                 <h4 class="text-gray-500 uppercase text-sm mb-1">Nama Barang</h4>
                                 <p class="text-lg text-gray-800 font-medium">{{ $detail->item->nama_barang ?? '-' }}</p>
                             </div>
                             <div class="bg-gray-50 p-4">
                                 <h4 class="text-gray-500 uppercase text-sm mb-1">Kode Barang</h4>
                                 <p class="text-lg text-gray-800 font-medium">{{ $detail->kode_barang ?? '-' }}</p>
                             </div>
                         </div>
                     @endforeach
                 </div>
             </div>
         @endif
 
        <!-- Tombol Kembali dengan Ikon -->
        <div class="mt-8 text-center">
            <a href="{{ route('loans_item.index') }}"
               class="inline-block bg-gradient-to-r from-blue-500 to-blue-700 hover:from-blue-600 hover:to-blue-800 text-white font-bold py-2 px-6 rounded transition duration-300">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

        <!-- Tombol Untuk Menampilkan Data Lebih Banyak (Jika Diperlukan) -->
        @if ($loanItem->detailItems->count() > 5)
            <div class="mt-4 text-center">
                <button onclick="window.open('{{ route('loans_item.show', $loanItem->id) }}', '_blank')"
                        class="bg-gray-700 text-white font-bold py-2 px-6 rounded-full hover:bg-gray-800 transition duration-300">
                    <i class="bx bx-fullscreen"></i> Lihat Semua Data
                </button>
            </div>
        @endif
    </div>
@endsection
