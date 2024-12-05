<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CekBarang extends Model
{
    protected $table = 'cek_barang';

    // CekBarang belongs to Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');  // Linking to the item_id
    }

    protected $fillable = [
        'item_id', 'kondisi_barang', 'descripsi'
    ];
}
