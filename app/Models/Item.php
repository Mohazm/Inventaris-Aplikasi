<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use app\models\Transactions_in;
use app\models\Transactions_out;
use app\models\Transactions_in;
use app\models\Loans_item;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    // protected = $fillables = ['nama_barang'],


    // Item model
    public function cekBarangs()
    {
        return $this->hasMany(CekBarang::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function transactionsIn()
    {
        return $this->hasMany(Transactions_in::class, 'item_id');
    }

    public function transactionsOut()
    {
        return $this->hasMany(Transactions_out::class, 'item_id');
    }

    public function loans()
    {
        return $this->belongsToMany(Loan::class, 'loan_item')
                    ->withPivot('detail_item_id', 'jumlah_pinjam')
                    ->withTimestamps();
    }
    public function loansItems()
    {
        return $this->hasMany(Loans_item::class, 'item_id');
    }
    // Model Item
    public function details()
    {
        return $this->hasMany(Detail_Item::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'item_id');
    }
}
