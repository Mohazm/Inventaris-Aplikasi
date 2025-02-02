<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TabunganSiswa extends Model
{
    use HasFactory;

    protected $table = 'tabungan_siswas';

    protected $fillable = [
        'nama_siswa',
        'kelas',
        'alamat',
        'jumlah_yang_di_tabung',
        'jumlah_tabungan',
        'uang_masuk',
        'uang_keluar',
    ];

    protected $casts = [
        'jumlah_yang_di_tabung' => 'float',
        'jumlah_tabungan' => 'float',
        'uang_masuk' => 'float',
        'uang_keluar' => 'float',
    ];
}
