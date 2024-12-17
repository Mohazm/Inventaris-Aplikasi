<!DOCTYPE html>
<html>

<head>
    <title>Peminjaman Terlambat</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Menambahkan sedikit styling untuk ikon */
        .icon {
            font-size: 30px;
            color: #f44336;
        }

        .footer {
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f9f9f9;">
    <!-- Bagian Header -->
    <h2 style="color: #333; text-align: center;">
        <i class="fas fa-exclamation-triangle icon"></i> Peminjaman Terlambat
    </h2>

    <!-- Body Email -->
    <h1>Pemberitahuan Peminjaman Terlambat</h1>
    <p>Halo,
        <strong>{{ $loan->borrower->student ? $loan->borrower->student->name : 'Nama Siswa Tidak Ditemukan' }}</strong>
        dan
        <strong>{{ $loan->borrower->teacher ? $loan->borrower->teacher->name : 'Nama Guru Tidak Ditemukan' }}</strong>,
    </p>
    <p>Anda memiliki peminjaman yang terlambat untuk buku <strong>{{ $loan->item->nama_barang }}</strong> yang
        seharusnya dikembalikan pada <strong>{{ $loan->tanggal_kembali }}</strong>.</p>
    <p>Harap segera mengembalikan buku tersebut untuk menghindari denda.</p>
    <p>Terima kasih!</p>

    <!-- Bagian Penutupan -->
    <p><i class="fas fa-thumbs-up"></i> Terima kasih telah menggunakan layanan kami!</p>

    <!-- Footer -->
    <p class="footer" style="text-align: center;">
        <i class="fas fa-phone-alt"></i> Jika Anda memiliki pertanyaan, silakan hubungi kami di <strong>(021)
            123-4567</strong>.
    </p>
</body>

</html>
