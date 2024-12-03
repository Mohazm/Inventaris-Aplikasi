<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tendik extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function itemLoans()
    {
        return $this->hasMany(Loans_item::class, 'tendik_id');
    }
}
