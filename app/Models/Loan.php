<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'borrower_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tujuan_peminjaman',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'loans_item')
                    ->withPivot('detail_item_id', 'jumlah_pinjam')
                    ->withTimestamps();
    }
}

