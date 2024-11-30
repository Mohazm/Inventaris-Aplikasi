<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use app\Models\Transactions_in;
class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function transactionsIn()
    {
        return $this->hasMany(Transactions_in::class, 'supplier_id');
    }
}
