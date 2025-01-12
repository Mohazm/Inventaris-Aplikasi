<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Loans_item extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function validateStatusChange($newStatus)
    {
        $validStatuses = ['dipakai', 'di kembalikan']; // Status yang valid
        return in_array($newStatus, $validStatuses);
    }


    public function borrowers()
    {
        return $this->belongsTo(Borrower::class, 'borrower_id');
    }

    // Relasi ke tabel Items
    public function items()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
    public function itemReturn()
    {
        return $this->hasOne(Returns_item::class, 'peminjaman_id');
    }
    public function detailPeminjaman()
    {
        return $this->hasMany(DetailPeminjaman::class, 'loan_id', 'id');
    }
    public function details()
    {
        return $this->hasMany(LoanItemDetail::class, 'loan_item_id'); // Pastikan 'loan_item_id' adalah nama kolom relasi yang tepat
    }

    public function detailItems()
    {
        return $this->belongsToMany(Detail_item::class, 'detail_item_loan', 'loans_item_id', 'detail_item_id')
            ->withTimestamps();
    }
        
}
