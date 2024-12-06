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
}
