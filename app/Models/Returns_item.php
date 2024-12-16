<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns_item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itemLoan()
    {
        return $this->belongsTo(Loans_item::class, 'peminjaman_id');
    }
}
