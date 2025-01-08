<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class LoanItemDetail extends Model
{
    use HasFactory;

    protected $fillable = ['loan_item_id', 'kode_barang', 'nama_barang'];

    public function loanItem()
    {
        return $this->belongsTo(Loans_Item::class, 'loan_item_id');
    }

    public function detailItem()
    {
        return $this->belongsTo(Detail_item::class, 'kode_barang', 'kode_barang');
    }
}
