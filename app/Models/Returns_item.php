<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Returns_item extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itemLoan(): BelongsTo
    {
        return $this->belongsTo(ItemLoan::class, 'peminjaman_id');
    }
}
