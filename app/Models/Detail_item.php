<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_item extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function detailPeminjaman()
    {
        return $this->hasOne(DetailPeminjaman::class, 'detail_item_id');
    }
    public function loanItems()
    {
        return $this->belongsToMany(Loans_item::class, 'detail_item_loan', 'detail_item_id', 'loans_item_id')
            ->withTimestamps();
    }
    
    // public function loanItems()
    // {
    //     return $this->belongsToMany(Loans_item::class, 'detail_item_loan', 'detail_item_id', 'loans_item_id')
    //                 ->withTimestamps();
    // }
}
