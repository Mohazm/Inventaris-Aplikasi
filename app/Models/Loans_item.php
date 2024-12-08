<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loans_item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function borrower(){
        return $this->belongsTo(Borrower::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function tendik()
    {
        return $this->belongsTo(Tendik::class, 'tendik_id');
    }

    public function itemReturn()
    {
        return $this->hasOne(ItemReturn::class, 'peminjaman_id');
    }
}
