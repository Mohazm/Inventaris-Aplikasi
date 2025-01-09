<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPeminjaman extends Model
{
    use HasFactory;

    protected $table = 'detail_peminjaman';

    protected $fillable = [
        'loan_id',
        'detail_item_id',
        'item_id',
        'kondisi_barang',
    ];

    public function loan()
    {
        return $this->belongsTo(LoansItem::class, 'loan_id');
    }

    public function detailItem()
    {
        return $this->belongsTo(DetailItem::class, 'detail_item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
